-- Tech-Triathlon 2026: Core Domain Model Schema
-- Target: PostgreSQL 15+
-- Author: Senior Architect

-- 1. Enums for State Machines (Activity A2.2)
CREATE TYPE order_status AS ENUM ('pending', 'allocated', 'deferred', 'loaded', 'in_transit', 'delivered', 'failed');
CREATE TYPE trip_status AS ENUM ('planned', 'loading', 'dispatched', 'completed');
CREATE TYPE temp_req AS ENUM ('ambient', 'chilled');
CREATE TYPE vehicle_type AS ENUM ('truck', 'van');
CREATE TYPE dock_type AS ENUM ('rear_dock', 'street', 'mall_bay');
CREATE TYPE parking_constraint AS ENUM ('normal', 'van_only', 'mall_dock');
CREATE TYPE deferral_reason_code AS ENUM ('NO_REEFER_VAN', 'CAPACITY_EXCEEDED', 'TIME_BUDGET', 'DEPOT_MISMATCH', 'VAN_ONLY_NO_VAN');

-- 2. Reference Data (Activity A2.1)
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

-- 3. Transactional Data: Trips (The Allocation Unit)
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
    
    -- Ensure a vehicle only does a max of 2 trips per day
    UNIQUE(vehicle_id, operation_date, trip_number)
);

-- 4. Transactional Data: Orders
CREATE TABLE orders (
    order_ref VARCHAR(20) PRIMARY KEY,
    outlet_id VARCHAR(10) NOT NULL REFERENCES outlets(outlet_id),
    order_date DATE NOT NULL,
    temp_requirement temp_req NOT NULL,
    order_units INT NOT NULL,
    order_weight_kg DECIMAL(10, 2) NOT NULL,
    order_volume_m3 DECIMAL(10, 3) NOT NULL,
    status order_status NOT NULL DEFAULT 'pending',
    deferral_reason deferral_reason_code,
    trip_id UUID REFERENCES trips(trip_id),
    deferred_yesterday BOOLEAN DEFAULT FALSE,
    days_since_last_served INT DEFAULT 0,
    
    -- Ensure deferred orders have a reason
    CHECK (status != 'deferred' OR deferral_reason IS NOT NULL)
);

-- 5. Transactional Data: Route Tracking
CREATE TABLE route_legs (
    leg_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    trip_id UUID NOT NULL REFERENCES trips(trip_id),
    seq INT NOT NULL,
    from_point VARCHAR(20) NOT NULL, -- 'DEPOT' or outlet_id
    to_outlet VARCHAR(10) NOT NULL REFERENCES outlets(outlet_id),
    planned_depart_time TIMESTAMP WITH TIME ZONE,
    planned_arrival_time TIMESTAMP WITH TIME ZONE,
    actual_depart_time TIMESTAMP WITH TIME ZONE,
    actual_arrival_time TIMESTAMP WITH TIME ZONE,
    leave_outlet_time TIMESTAMP WITH TIME ZONE,
    distance_km DECIMAL(10, 2),
    
    -- ML Predictions (A2.5)
    pred_service_min DECIMAL(5, 2),
    pred_late_prob DECIMAL(3, 2),
    
    UNIQUE(trip_id, seq)
);

-- 6. Demand Forecasts (A2.5)
CREATE TABLE demand_forecasts (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    depot VARCHAR(50) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    iso_year INT NOT NULL,
    iso_week INT NOT NULL,
    pred_total_volume_m3 DECIMAL(10, 2) NOT NULL,
    pred_chilled_volume_m3 DECIMAL(10, 2) NOT NULL,
    UNIQUE(depot, brand, iso_year, iso_week)
);

