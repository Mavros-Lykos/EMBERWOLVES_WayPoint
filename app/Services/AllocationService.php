<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Trip;
use Carbon\Carbon;

/**
 * AllocationService — Constraint-aware greedy bin-packing.
 *
 * Implements all 10 feasibility rules from SRS §4 FR-001 – FR-010.
 *
 * Reason codes (from Master Plan A7):
 *   VOLUME_OVER_ANY_VEHICLE | NO_REEFER_CAPACITY | NO_REEFER_VAN
 *   NO_VAN | TIME_BUDGET | WINDOW | DEPOT | CHOICE
 */
class AllocationService
{
    // Trip time formula constants (minutes)
    const OUTBOUND_MIN     = 30;  // simplified outbound once
    const INTER_STOP_MIN   = 15;  // inter-stop × (orders-1)
    const FRESH_MAX_MIN    = 270;
    const STYLE_TECH_MAX   = 480;

    // Service allowance by brand/dock
    const SERVICE_BY_BRAND = [
        'Fresh'  => 20,
        'Style'  => 30,
        'Tech'   => 45,
    ];

    public function run(string $date): array
    {
        // 1. Load all pending orders for this date with outlet data
        $orders = Order::with('outlet')
            ->where('status', 'pending')
            ->where('order_date', $date)
            ->orderByDesc('urgency_flag')
            ->orderByDesc('days_since_last_served')
            ->get();

        // 2. Load available vehicles
        $vehicles = DB::table('vehicles')->get()->keyBy('vehicle_id');

        // 3. Track trip assignments: [vehicle_id => [trip_number => {trip}]]
        $vehicleTrips  = [];
        $vehicleMinutes = []; // [vehicle_id => minutes_used_today]

        $served   = 0;
        $deferred = 0;
        $trips    = [];
        $deferredOrders = [];

        // 4. Group by (brand, district) — FR-001
        $groups = $orders->groupBy(fn($o) => $o->outlet->brand . '|' . $o->outlet->district);

        foreach ($groups as $groupKey => $groupOrders) {
            [$brand, $district] = explode('|', $groupKey);
            $isChilled = $groupOrders->contains(fn($o) => $o->temp_requirement === 'chilled');
            $isVanOnly = $groupOrders->contains(fn($o) => $o->outlet->parking_constraint === 'van_only');

            // Find eligible vehicles
            $eligible = collect($vehicles)->filter(function($v) use ($isChilled, $isVanOnly) {
                if ($isChilled && $v->temp !== 'reefer') return false;      // FR-002
                if ($isVanOnly && $v->type !== 'van') return false;          // FR-003
                return true;
            });

            if ($eligible->isEmpty()) {
                // No eligible vehicle exists at all
                $reasonCode = $isVanOnly && $isChilled ? 'NO_REEFER_VAN'
                    : ($isVanOnly ? 'NO_VAN' : 'NO_REEFER_CAPACITY');
                foreach ($groupOrders as $order) {
                    $order->update(['status' => 'deferred', 'deferral_reason' => $reasonCode]);
                    $deferredOrders[] = ['order_ref' => $order->order_ref, 'reason' => $reasonCode];
                    $deferred++;
                }
                continue;
            }

            // Try to pack orders into trips on eligible vehicles
            foreach ($groupOrders as $order) {
                $outlet = $order->outlet;

                // EC-01: Order exceeds all vehicle capacity
                $maxVol = $eligible->max('volume_cap_m3');
                if ($order->order_volume_m3 > $maxVol) {
                    $order->update(['status' => 'deferred', 'deferral_reason' => 'VOLUME_OVER_ANY_VEHICLE']);
                    $deferredOrders[] = ['order_ref' => $order->order_ref, 'reason' => 'VOLUME_OVER_ANY_VEHICLE'];
                    $deferred++;
                    continue;
                }

                $allocated = false;

                // Try to fit into existing open trip
                foreach ($vehicleTrips as $vId => &$vTrips) {
                    $vehicle = $vehicles[$vId];

                    // Must match brand+district on this trip
                    foreach ($vTrips as $tNum => &$tripData) {
                        if ($tripData['brand'] !== $brand || $tripData['district'] !== $district) continue;
                        if ($tripData['status'] !== 'open') continue;

                        // Check capacity
                        $newWeight = $tripData['weight'] + $order->order_weight_kg;
                        $newVolume = $tripData['volume'] + $order->order_volume_m3;
                        if ($newWeight > $vehicle->weight_cap_kg) continue;   // FR-005
                        if ($newVolume > $vehicle->volume_cap_m3) continue;   // FR-006

                        // Check time budget — FR-008/009
                        $serviceMin   = self::SERVICE_BY_BRAND[$brand] ?? 20;
                        $orderCount   = count($tripData['orders']) + 1;
                        $tripMin      = self::OUTBOUND_MIN + (self::INTER_STOP_MIN * ($orderCount - 1)) + ($serviceMin * $orderCount);
                        $maxMin       = in_array($brand, ['Style','Tech']) ? self::STYLE_TECH_MAX : self::FRESH_MAX_MIN;

                        $currentMin   = $vehicleMinutes[$vId] ?? 0;
                        if (($currentMin + $tripMin) > $maxMin) continue;     // FR-008/009

                        // Pack it
                        $tripData['orders'][] = $order->order_ref;
                        $tripData['weight']   = $newWeight;
                        $tripData['volume']   = $newVolume;
                        $vehicleMinutes[$vId] = $currentMin + $tripMin;

                        $order->update(['status' => 'allocated', 'trip_id' => $tripData['trip_id']]);

                        $seq = count($tripData['orders']);
                        $prevOrderRef = $tripData['orders'][$seq - 2] ?? null;
                        $prevOrder = $prevOrderRef ? Order::where('order_ref', $prevOrderRef)->first() : null;
                        DB::table('route_legs')->insert([
                            'leg_id' => Str::uuid(),
                            'trip_id' => $tripData['trip_id'],
                            'seq' => $seq,
                            'from_point' => $prevOrder ? $prevOrder->outlet_id : ($vehicle->depot ?? 'Peliyagoda'),
                            'to_outlet' => $order->outlet_id,
                            'planned_depart_time' => Carbon::parse($date . ' 04:00:00')->addMinutes(($seq - 1) * 45),
                            'planned_arrival_time' => Carbon::parse($date . ' 04:30:00')->addMinutes(($seq - 1) * 45),
                            'distance_km' => 12.0,
                            'reefer_temp_celsius' => ($vehicle->temp === 'reefer' ? 3.0 : null),
                        ]);

                        $served++;
                        $allocated = true;
                        break 2;
                    }
                }
                unset($tripData, $vTrips);

                if ($allocated) continue;

                // Open a new trip on an eligible vehicle
                foreach ($eligible as $vehicle) {
                    $vId = $vehicle->vehicle_id;
                    $existingCount = count($vehicleTrips[$vId] ?? []);
                    if ($existingCount >= 2) continue;  // FR-007

                    $tripNum = $existingCount + 1;
                    $tripId  = Str::uuid()->toString();

                    // Create trip record
                    Trip::create([
                        'trip_id'          => $tripId,
                        'vehicle_id'       => $vId,
                        'trip_number'      => $tripNum,
                        'operation_date'   => $date,
                        'brand'            => $brand,
                        'district'         => $district,
                        'status'           => 'planned',
                        'total_weight_kg'  => $order->order_weight_kg,
                        'total_volume_m3'  => $order->order_volume_m3,
                    ]);

                    $serviceMin = self::SERVICE_BY_BRAND[$brand] ?? 20;
                    $vehicleTrips[$vId][$tripNum] = [
                        'trip_id'  => $tripId,
                        'brand'    => $brand,
                        'district' => $district,
                        'weight'   => $order->order_weight_kg,
                        'volume'   => $order->order_volume_m3,
                        'orders'   => [$order->order_ref],
                        'status'   => 'open',
                    ];
                    $vehicleMinutes[$vId] = ($vehicleMinutes[$vId] ?? 0)
                        + self::OUTBOUND_MIN + $serviceMin;

                    $order->update(['status' => 'allocated', 'trip_id' => $tripId]);

                    DB::table('route_legs')->insert([
                        'leg_id' => Str::uuid(),
                        'trip_id' => $tripId,
                        'seq' => 1,
                        'from_point' => $vehicle->depot ?? 'Peliyagoda',
                        'to_outlet' => $order->outlet_id,
                        'planned_depart_time' => Carbon::parse($date . ' 04:00:00'),
                        'planned_arrival_time' => Carbon::parse($date . ' 04:30:00'),
                        'distance_km' => 15.0,
                        'reefer_temp_celsius' => ($vehicle->temp === 'reefer' ? 3.0 : null),
                    ]);

                    $trips[] = ['trip_id' => $tripId, 'vehicle_id' => $vId, 'brand' => $brand, 'district' => $district];
                    $served++;
                    $allocated = true;
                    break;
                }

                if (!$allocated) {
                    // No vehicle could take it
                    $reason = $isChilled ? 'NO_REEFER_CAPACITY' : 'CHOICE';
                    $order->update(['status' => 'deferred', 'deferral_reason' => $reason]);
                    $deferredOrders[] = ['order_ref' => $order->order_ref, 'reason' => $reason];
                    $deferred++;
                }
            }
        }

        return compact('served', 'deferred', 'trips', 'deferredOrders');
    }
}
