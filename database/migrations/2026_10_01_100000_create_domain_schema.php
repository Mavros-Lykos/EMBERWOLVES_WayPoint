<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Execute raw PostgreSQL schema from db_schema.sql + enhancements
        DB::unprepared("
            -- 1. Enums
            CREATE TYPE order_status AS ENUM ('pending', 'allocated', 'deferred', 'loaded', 'in_transit', 'delivered', 'failed');
            CREATE TYPE trip_status AS ENUM ('planned', 'loading', 'dispatched', 'completed');
            CREATE TYPE temp_req AS ENUM ('ambient', 'chilled');
            CREATE TYPE vehicle_type AS ENUM ('truck', 'van');
            CREATE TYPE dock_type AS ENUM ('rear_dock', 'street', 'mall_bay');
            CREATE TYPE parking_constraint AS ENUM ('normal', 'van_only', 'mall_dock');
            CREATE TYPE deferral_reason_code AS ENUM ('NO_REEFER_VAN', 'CAPACITY_EXCEEDED', 'TIME_BUDGET', 'DEPOT_MISMATCH', 'VAN_ONLY_NO_VAN', 'VOLUME_OVER_ANY_VEHICLE', 'NO_REEFER_CAPACITY', 'CAPACITY', 'WINDOW', 'EQUITY_CHOICE');

            -- Users enhancements (assumes users table exists via Laravel scaffold)
            ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'driver';
            ALTER TABLE users ADD COLUMN depot VARCHAR(50);
            ALTER TABLE users ADD COLUMN vehicle_id VARCHAR(10); -- for drivers
            ALTER TABLE users ADD COLUMN outlet_id VARCHAR(10); -- for store managers

            -- 2. Reference Data
            CREATE TABLE outlets (
                outlet_id VARCHAR(10) PRIMARY KEY,
                brand VARCHAR(50) NOT NULL,
                district VARCHAR(50) NOT NULL,
                depot VARCHAR(50) NOT NULL,
                dock_type dock_type NOT NULL,
                parking_constraint parking_constraint NOT NULL,
                mall_window VARCHAR(20),
                window_open_time TIME NOT NULL,
                window_close_time TIME NOT NULL
            );

            CREATE TABLE vehicles (
                vehicle_id VARCHAR(10) PRIMARY KEY,
                type vehicle_type NOT NULL,
                temp temp_req NOT NULL,
                weight_cap_kg DECIMAL(10, 2) NOT NULL,
                volume_cap_m3 DECIMAL(10, 3) NOT NULL,
                fuel_type VARCHAR(20) NOT NULL,
                km_per_l DECIMAL(5, 2) NOT NULL,
                weekly_fuel_quota_l DECIMAL(10, 2) NOT NULL,
                depot VARCHAR(50) NOT NULL
            );

            -- 3. Trips
            CREATE TABLE trips (
                trip_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                vehicle_id VARCHAR(10) NOT NULL REFERENCES vehicles(vehicle_id),
                trip_number INT NOT NULL CHECK (trip_number IN (1, 2)),
                operation_date DATE NOT NULL,
                brand VARCHAR(50) NOT NULL,
                district VARCHAR(50) NOT NULL,
                status trip_status NOT NULL DEFAULT 'planned',
                total_weight_kg DECIMAL(10,2) DEFAULT 0,
                total_volume_m3 DECIMAL(10,3) DEFAULT 0,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
                updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
                UNIQUE(vehicle_id, operation_date, trip_number)
            );

            -- 4. Orders
            CREATE TABLE orders (
                order_ref VARCHAR(20) PRIMARY KEY,
                outlet_id VARCHAR(10) NOT NULL REFERENCES outlets(outlet_id),
                order_date DATE NOT NULL,
                placed_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
                temp_requirement temp_req NOT NULL,
                order_units INT NOT NULL,
                order_weight_kg DECIMAL(10, 2) NOT NULL,
                order_volume_m3 DECIMAL(10, 3) NOT NULL,
                status order_status NOT NULL DEFAULT 'pending',
                deferral_reason deferral_reason_code,
                trip_id UUID REFERENCES trips(trip_id),
                deferred_yesterday BOOLEAN DEFAULT FALSE,
                days_since_last_served INT DEFAULT 0,
                urgency_flag BOOLEAN DEFAULT FALSE,
                CHECK (status != 'deferred' OR deferral_reason IS NOT NULL)
            );

            -- 5. Route Tracking
            CREATE TABLE route_legs (
                leg_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                trip_id UUID NOT NULL REFERENCES trips(trip_id),
                seq INT NOT NULL,
                from_point VARCHAR(20) NOT NULL,
                to_outlet VARCHAR(10) NOT NULL REFERENCES outlets(outlet_id),
                planned_depart_time TIMESTAMP WITH TIME ZONE,
                planned_arrival_time TIMESTAMP WITH TIME ZONE,
                actual_depart_time TIMESTAMP WITH TIME ZONE,
                actual_arrival_time TIMESTAMP WITH TIME ZONE,
                leave_outlet_time TIMESTAMP WITH TIME ZONE,
                distance_km DECIMAL(10, 2),
                reefer_temp_celsius DECIMAL(4,1),
                UNIQUE(trip_id, seq)
            );

            -- Enhancements
            CREATE TABLE deferral_log (
                id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                order_ref VARCHAR(20) NOT NULL REFERENCES orders(order_ref),
                decided_by_user_id BIGINT REFERENCES users(id),
                reason_code deferral_reason_code NOT NULL,
                deferred_date DATE NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
            );

            CREATE TABLE delivery_confirmations (
                id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                route_leg_id UUID NOT NULL REFERENCES route_legs(leg_id),
                signature_data TEXT,
                photo_path VARCHAR(255),
                confirmed_units INT,
                discrepancy_note TEXT,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
            );

            CREATE TABLE sync_mutations (
                id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                route_leg_id UUID NOT NULL REFERENCES route_legs(leg_id),
                mutation_type VARCHAR(50),
                local_timestamp TIMESTAMP WITH TIME ZONE,
                payload_json JSONB,
                status VARCHAR(20) DEFAULT 'pending',
                created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
            );

            CREATE TABLE loading_exceptions (
                id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                trip_id UUID NOT NULL REFERENCES trips(trip_id),
                order_ref VARCHAR(20) NOT NULL REFERENCES orders(order_ref),
                exception_type VARCHAR(50),
                quantity_expected INT,
                quantity_actual INT,
                reason TEXT,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
            );

            -- Triggers
            CREATE OR REPLACE FUNCTION check_trip_brand_district() RETURNS TRIGGER AS $$
            DECLARE
                t_brand VARCHAR;
                t_district VARCHAR;
                o_brand VARCHAR;
                o_district VARCHAR;
            BEGIN
                IF NEW.trip_id IS NOT NULL THEN
                    SELECT brand, district INTO t_brand, t_district FROM trips WHERE trip_id = NEW.trip_id;
                    SELECT brand, district INTO o_brand, o_district FROM outlets WHERE outlet_id = NEW.outlet_id;
                    
                    IF t_brand != o_brand OR t_district != o_district THEN
                        RAISE EXCEPTION 'Order brand/district does not match trip brand/district';
                    END IF;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER enforce_trip_grouping
            BEFORE INSERT OR UPDATE ON orders
            FOR EACH ROW EXECUTE FUNCTION check_trip_brand_district();

            CREATE OR REPLACE FUNCTION check_reefer_requirement() RETURNS TRIGGER AS $$
            DECLARE
                v_temp temp_req;
            BEGIN
                IF NEW.trip_id IS NOT NULL THEN
                    SELECT v.temp INTO v_temp 
                    FROM trips t JOIN vehicles v ON t.vehicle_id = v.vehicle_id 
                    WHERE t.trip_id = NEW.trip_id;
                    
                    IF NEW.temp_requirement = 'chilled' AND v_temp != 'reefer' THEN
                        RAISE EXCEPTION 'Chilled orders require a reefer vehicle';
                    END IF;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER enforce_reefer_requirement
            BEFORE INSERT OR UPDATE ON orders
            FOR EACH ROW EXECUTE FUNCTION check_reefer_requirement();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS enforce_reefer_requirement ON orders;
            DROP FUNCTION IF EXISTS check_reefer_requirement();
            DROP TRIGGER IF EXISTS enforce_trip_grouping ON orders;
            DROP FUNCTION IF EXISTS check_trip_brand_district();

            DROP TABLE IF EXISTS loading_exceptions;
            DROP TABLE IF EXISTS sync_mutations;
            DROP TABLE IF EXISTS delivery_confirmations;
            DROP TABLE IF EXISTS deferral_log;
            DROP TABLE IF EXISTS route_legs;
            DROP TABLE IF EXISTS orders;
            DROP TABLE IF EXISTS trips;
            DROP TABLE IF EXISTS vehicles;
            DROP TABLE IF EXISTS outlets;

            ALTER TABLE users DROP COLUMN role;
            ALTER TABLE users DROP COLUMN depot;
            ALTER TABLE users DROP COLUMN vehicle_id;
            ALTER TABLE users DROP COLUMN outlet_id;

            DROP TYPE IF EXISTS deferral_reason_code;
            DROP TYPE IF EXISTS parking_constraint;
            DROP TYPE IF EXISTS dock_type;
            DROP TYPE IF EXISTS vehicle_type;
            DROP TYPE IF EXISTS temp_req;
            DROP TYPE IF EXISTS trip_status;
            DROP TYPE IF EXISTS order_status;
        ");
    }
};
