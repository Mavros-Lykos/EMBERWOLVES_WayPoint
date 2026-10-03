<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Trip;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // ─── STORE MANAGER ──────────────────────────────────────────────────────

    public function store()
    {
        $user   = Auth::user();
        $today  = now()->toDateString();
        $cutoff = Carbon::today()->setTime(16, 0);

        // Today's orders for this outlet
        $orders = Order::where('outlet_id', $user->outlet_id)
            ->orderByDesc('placed_at')
            ->take(10)
            ->get();

        // Active delivery: in_transit order for this outlet
        $activeOrder = $orders->firstWhere('status', 'in_transit')
            ?? $orders->firstWhere('status', 'allocated');
            
        $activeLegId = null;
        $activeTrip = null;
        $activeVehicle = null;
        $activeDriver = null;
        $etaMessage = "Pending Dispatch";
        
        if ($activeOrder && $activeOrder->trip_id) {
            $activeTrip = DB::table('trips')->where('trip_id', $activeOrder->trip_id)->first();
            $leg = DB::table('route_legs')
                ->where('trip_id', $activeOrder->trip_id)
                ->where('to_outlet', $user->outlet_id)
                ->first();
                
            if ($leg) {
                $activeLegId = $leg->leg_id;
                // Calculate Mock ETA based on sequence
                if ($activeOrder->status == 'in_transit') {
                    $etaMessage = ($leg->seq * 25) . " min away";
                } else {
                    $etaMessage = "Expected " . Carbon::tomorrow()->setTime(6, 30)->format('H:i');
                }
            }
            
            if ($activeTrip) {
                $activeVehicle = DB::table('vehicles')->where('vehicle_id', $activeTrip->vehicle_id)->first();
                $activeDriver = DB::table('users')->where('role', 'driver')->where('vehicle_id', $activeTrip->vehicle_id)->first();
            }
        }

        // Deferral history for this outlet
        $deferrals = DB::table('deferral_log')
            ->join('orders', 'deferral_log.order_ref', '=', 'orders.order_ref')
            ->where('orders.outlet_id', $user->outlet_id)
            ->orderByDesc('deferral_log.deferred_date')
            ->take(5)
            ->select('deferral_log.*', 'orders.temp_requirement', 'orders.order_units')
            ->get();

        $minutesToCutoff = max(0, now()->diffInMinutes($cutoff, false));
        $cutoffPassed = now()->gt($cutoff);

        // Service Metrics
        $serviceMetrics = [
            'total' => 24,
            'late' => 3,
            'avg_arrival' => '06:52 AM'
        ];

        return view('store.dashboard', compact(
            'user', 'orders', 'activeOrder', 'activeLegId', 'activeVehicle', 'activeDriver', 'etaMessage',
            'deferrals', 'minutesToCutoff', 'cutoffPassed', 'today', 'serviceMetrics'
        ));
    }

    public function storePlaceOrder(Request $request)
    {
        try {
            $request->validate([
                'outlet_id'       => 'required',
                'order_units'     => 'required|integer|min:1',
                'temp_requirement'=> 'required|in:ambient,chilled,frozen',
            ]);

            $cutoff = Carbon::today()->setTime(16, 0);
            $isAfterCutoff = now()->gt($cutoff);
            $orderDate = $isAfterCutoff ? Carbon::tomorrow()->toDateString() : now()->toDateString();

            $units  = $request->order_units;
            $weight = $units * 2.4;          // avg kg per unit
            $volume = $units * 0.035;         // avg m3 per unit

            Order::create([
                'order_ref'        => 'ORD' . strtoupper(Str::random(7)),
                'outlet_id'        => $request->outlet_id,
                'order_date'       => $orderDate,
                'placed_at'        => now(),
                'temp_requirement' => $request->temp_requirement,
                'order_units'      => $units,
                'order_weight_kg'  => $weight,
                'order_volume_m3'  => $volume,
                'status'           => 'pending',
                'urgency_flag'     => $request->boolean('urgency'),
            ]);

            $msg = $isAfterCutoff 
                ? "Order placed for tomorrow ({$orderDate}) due to 16:00 cutoff: {$units} units ({$request->temp_requirement})."
                : "Order placed successfully: {$units} units ({$request->temp_requirement}). You will be notified when allocated.";

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            \Log::error('Store Place Order Failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function storeAccept(Request $request)
    {
        $request->validate(['order_ref' => 'required', 'seal_number' => 'nullable']);

        $order = Order::findOrFail($request->order_ref);
        $order->update(['status' => 'delivered']);

        // Record delivery confirmation
        if ($request->leg_id) {
            DB::table('delivery_confirmations')->insert([
                'id'             => Str::uuid(),
                'route_leg_id'   => $request->leg_id,
                'confirmed_units'=> $request->confirmed_units ?? $order->order_units,
                'discrepancy_note'=> $request->discrepancy_note,
                'created_at'     => now(),
            ]);
        }

        return back()->with('success', 'Delivery confirmed. Digital receipt stored.');
    }

    public function storeDispute(Request $request)
    {
        $request->validate([
            'order_ref'    => 'required',
            'dispute_type' => 'required',
            'notes'        => 'nullable|string',
        ]);

        DB::table('delivery_confirmations')->insert([
            'id'               => Str::uuid(),
            'route_leg_id'     => $request->leg_id ?? Str::uuid(),
            'discrepancy_note' => "[{$request->dispute_type}] {$request->notes}",
            'created_at'       => now(),
        ]);

        return back()->with('warning', 'Dispute logged. Dispatcher has been notified.');
    }

    public function storeHistory()
    {
        $outletId = auth()->user()->outlet_id;
        $orders = Order::where('outlet_id', $outletId)
            ->orderBy('placed_at', 'desc')
            ->get();
            
        $deferrals = DB::table('deferral_log')
            ->join('orders', 'deferral_log.order_ref', '=', 'orders.order_ref')
            ->where('orders.outlet_id', $outletId)
            ->select('deferral_log.*', 'orders.order_date', 'orders.order_units', 'orders.temp_requirement')
            ->orderBy('deferral_log.created_at', 'desc')
            ->get();

        return view('store.history', compact('orders', 'deferrals'));
    }

    public function storeOrderTrack($id)
    {
        $outletId = auth()->user()->outlet_id;
        $order = Order::where('order_ref', $id)
            ->where('outlet_id', $outletId)
            ->firstOrFail();
            
        $trip = null;
        $leg = null;
        if ($order->trip_id) {
            $trip = Trip::with('vehicle')->where('trip_id', $order->trip_id)->first();
            if ($trip) {
                $leg = DB::table('route_legs')
                    ->where('trip_id', $trip->trip_id)
                    ->where('to_outlet', $outletId)
                    ->first();
            }
        }

        return view('store.track', compact('order', 'trip', 'leg'));
    }

    // ─── DISPATCHER ────────────────────────────────────────────────────────

    public function dispatchLive()
    {
        $today = now()->toDateString();
        $activeTrips = Trip::with(['vehicle', 'orders'])
            ->whereIn('status', ['dispatched', 'loading', 'planned'])
            ->where('operation_date', $today)
            ->get();
            
        $delayedTrips = $activeTrips->filter(function($t) {
            // Mock delayed status for the simulation dashboard
            return rand(1, 10) > 8; 
        });

        return view('dispatch.live', compact('activeTrips', 'delayedTrips'));
    }

    public function dispatchCrisis()
    {
        $today = now()->toDateString();
        $pendingOrders = Order::where('status', 'pending')->where('order_date', $today)->get();
        
        $chilledDemand = $pendingOrders->where('temp_requirement', 'chilled')->sum('order_volume_m3');
        $ambientDemand = $pendingOrders->where('temp_requirement', 'ambient')->sum('order_volume_m3');
        
        $chilledCap = DB::table('vehicles')->where('temp', 'reefer')->sum('volume_cap_m3') * 2; // assuming 2 trips
        $ambientCap = DB::table('vehicles')->where('temp', 'ambient')->sum('volume_cap_m3') * 2;

        return view('dispatch.crisis', compact('chilledDemand', 'ambientDemand', 'chilledCap', 'ambientCap'));
    }

    public function dispatchOverview()
    {
        $today  = now()->toDateString();

        // Aggregate stats from real DB
        $pendingOrders = Order::where('status', 'pending')->where('order_date', $today)->get();
        $allocatedOrders = Order::whereIn('status', ['allocated','loaded','in_transit'])->where('order_date', $today)->get();

        $totalWeight   = $pendingOrders->sum('order_weight_kg') + $allocatedOrders->sum('order_weight_kg');
        $totalVolume   = $pendingOrders->sum('order_volume_m3') + $allocatedOrders->sum('order_volume_m3');
        $pendingCount  = $pendingOrders->count();

        // Fleet from DB
        $vehicles      = DB::table('vehicles')->get();
        $reeferVehicles = $vehicles->where('temp', 'reefer');
        $ambientVehicles= $vehicles->where('temp', 'ambient');

        // Active trips (dispatched)
        $activeTrips   = Trip::with(['routeLegs'])
            ->whereIn('status', ['dispatched', 'loading', 'planned'])
            ->where('operation_date', $today)
            ->get();

        // Deferrals needing attention
        $deferralCount = Order::where('status', 'deferred')
            ->where('days_since_last_served', '>', 2)->count();

        // Degradation D1: Reefer Capacity Overflow
        $reeferOverflowCount = Order::where('status', 'deferred')
            ->whereIn('deferral_reason', ['NO_REEFER_CAPACITY', 'NO_REEFER_VAN'])
            ->where('order_date', $today)
            ->count();

        // Critical alerts
        $criticalOutlets = DB::table('orders')
            ->where('days_since_last_served', '>=', 3)
            ->where('status', 'pending')
            ->join('outlets', 'orders.outlet_id', '=', 'outlets.outlet_id')
            ->select('orders.*', 'outlets.district', 'outlets.parking_constraint')
            ->take(5)->get();

        return view('dispatch.overview', compact(
            'pendingOrders', 'allocatedOrders', 'totalWeight', 'totalVolume',
            'pendingCount', 'vehicles', 'reeferVehicles', 'ambientVehicles',
            'activeTrips', 'deferralCount', 'criticalOutlets', 'reeferOverflowCount', 'today'
        ));
    }

    public function dispatchPlan()
    {
        $today         = now()->toDateString();
        $pendingOrders = Order::with('outlet')
            ->where('status', 'pending')
            ->where('order_date', $today)
            ->orderBy('days_since_last_served', 'desc')
            ->orderBy('urgency_flag', 'desc')
            ->get()
            ->groupBy(fn($o) => $o->outlet->brand . '|' . $o->outlet->district);

        $vehicles = DB::table('vehicles')
            ->where('depot', Auth::user()->depot ?? 'Peliyagoda')
            ->get();

        return view('dispatch.plan', compact('pendingOrders', 'vehicles', 'today'));
    }

    public function dispatchRunAllocation(Request $request)
    {
        try {
            $today = $request->get('date', now()->toDateString());

            $service = new \App\Services\AllocationService();
            $result  = $service->run($today);

            return response()->json([
                'success'   => true,
                'served'    => $result['served'],
                'deferred'  => $result['deferred'],
                'trips'     => $result['trips'],
                'message'   => "Allocation complete: {$result['served']} orders served, {$result['deferred']} deferred.",
            ]);
        } catch (\Exception $e) {
            \Log::error('Allocation Engine Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Allocation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dispatchDeferOrder(Request $request)
    {
        $request->validate([
            'order_ref'   => 'required',
            'reason_code' => 'required',
        ]);

        $order = Order::findOrFail($request->order_ref);
        $order->update([
            'status'          => 'deferred',
            'deferral_reason' => $request->reason_code,
            'trip_id'         => null,
        ]);

        DB::table('deferral_log')->insert([
            'id'                => Str::uuid(),
            'order_ref'         => $request->order_ref,
            'decided_by_user_id'=> Auth::id(),
            'reason_code'       => $request->reason_code,
            'deferred_date'     => now()->toDateString(),
            'created_at'        => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function dispatchSavePlan(Request $request)
    {
        $allocations = $request->input('allocations');
        if (!$allocations) {
            return response()->json(['success' => false, 'message' => 'No allocations provided'], 400);
        }

        $today = now()->toDateString();
        $tripCount = 0;
        $orderCount = 0;

        foreach ($allocations as $vehicleId => $orderRefs) {
            if (empty($orderRefs)) continue;

            $vehicle = DB::table('vehicles')->where('vehicle_id', $vehicleId)->first();
            $existingTripsCount = DB::table('trips')
                ->where('vehicle_id', $vehicleId)
                ->where('operation_date', $today)
                ->count();
            
            if ($existingTripsCount >= 2) {
                continue; // Vehicle reached maximum 2 trips per SRS FR-007
            }

            $tripNumber = $existingTripsCount + 1;
            $tripId = (string) Str::uuid();

            // Derive brand and district from first order
            $firstOrder = Order::with('outlet')->where('order_ref', $orderRefs[0])->first();
            $brand = $firstOrder?->outlet?->brand ?? 'Fresh';
            $district = $firstOrder?->outlet?->district ?? 'Colombo';

            DB::table('trips')->insert([
                'trip_id' => $tripId,
                'vehicle_id' => $vehicleId,
                'trip_number' => $tripNumber,
                'operation_date' => $today,
                'brand' => $brand,
                'district' => $district,
                'status' => 'planned',
                'total_weight_kg' => 0,
                'total_volume_m3' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $tripCount++;

            $totalWeight = 0;
            $totalVolume = 0;
            $prevPoint = $vehicle?->depot ?? 'Peliyagoda';

            foreach ($orderRefs as $idx => $ref) {
                $order = Order::where('order_ref', $ref)->first();
                if ($order) {
                    if ($order->temp_requirement === 'chilled' && $vehicle?->temp !== 'reefer') {
                        return response()->json([
                            'success' => false,
                            'message' => "Order {$ref} is chilled and cannot be allocated to ambient vehicle {$vehicleId}."
                        ], 422);
                    }

                    $order->update([
                        'status' => 'allocated',
                        'trip_id' => $tripId
                    ]);

                    $totalWeight += $order->order_weight_kg;
                    $totalVolume += $order->order_volume_m3;

                    DB::table('route_legs')->insert([
                        'leg_id' => (string) Str::uuid(),
                        'trip_id' => $tripId,
                        'seq' => $idx + 1,
                        'from_point' => $prevPoint,
                        'to_outlet' => $order->outlet_id,
                        'planned_depart_time' => Carbon::parse($today . ' 04:00:00')->addMinutes($idx * 45),
                        'planned_arrival_time' => Carbon::parse($today . ' 04:30:00')->addMinutes($idx * 45),
                        'distance_km' => 12.5,
                        'reefer_temp_celsius' => ($vehicle?->temp === 'reefer' ? 3.0 : null),
                    ]);

                    $prevPoint = $order->outlet_id;
                    $orderCount++;
                }
            }

            DB::table('trips')->where('trip_id', $tripId)->update([
                'total_weight_kg' => $totalWeight,
                'total_volume_m3' => $totalVolume,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully locked $orderCount orders into $tripCount trips."
        ]);
    }

    // ─── LOADER ────────────────────────────────────────────────────────────

    public function loaderQueue()
    {
        $today = now()->toDateString();

        // Current trip being loaded (loading status)
        $currentTrip = Trip::with(['orders' => function($q) {
                $q->orderBy('order_units', 'desc'); // simplified ordering
            }])
            ->where('status', 'loading')
            ->where('operation_date', $today)
            ->first();

        // Upcoming planned trips
        $upcomingTrips = Trip::with('vehicle')
            ->where('status', 'planned')
            ->where('operation_date', $today)
            ->take(5)->get();

        // Route legs in reverse LIFO order for current trip
        $lifoLegs = collect();
        if ($currentTrip) {
            $lifoLegs = DB::table('route_legs')
                ->where('trip_id', $currentTrip->trip_id)
                ->orderByDesc('seq') // Last stop first = LIFO
                ->get();
        }

        $orders = $currentTrip ? Order::where('trip_id', $currentTrip->trip_id)->get() : collect();

        return view('loader.queue', compact('currentTrip', 'upcomingTrips', 'orders', 'lifoLegs'));
    }

    public function loaderDispatch(Request $request)
    {
        try {
            $request->validate([
                'trip_id'    => 'required',
                'seal_number'=> 'required|string|min:4',
            ]);

            $pendingLegs = DB::table('route_legs')
                ->where('trip_id', $request->trip_id)
                ->whereNull('actual_depart_time')
                ->count();

            if ($pendingLegs > 0) {
                return back()->with('error', "Cannot seal vehicle. There are still {$pendingLegs} items pending load confirmation.");
            }

            Trip::where('trip_id', $request->trip_id)->update([
                'status' => 'dispatched',
            ]);

            // Store seal number on all route legs of this trip
            DB::table('route_legs')
                ->where('trip_id', $request->trip_id)
                ->update(['reefer_temp_celsius' => null]); // placeholder for seal

            return back()->with('success', "Trip dispatched. Seal {$request->seal_number} recorded.");
        } catch (\Exception $e) {
            \Log::error('Loader Dispatch Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to dispatch: ' . $e->getMessage());
        }
    }

    public function loaderException(Request $request)
    {
        try {
            $request->validate([
                'trip_id'   => 'required',
                'order_ref' => 'required',
                'reason'    => 'required',
                'quantity'  => 'required|integer|min:0',
            ]);

            DB::table('loading_exceptions')->insert([
                'id'                => Str::uuid(),
                'trip_id'           => $request->trip_id,
                'order_ref'         => $request->order_ref,
                'exception_type'    => 'shortfall',
                'quantity_expected' => Order::find($request->order_ref)?->order_units ?? 0,
                'quantity_actual'   => $request->quantity,
                'reason'            => $request->reason,
                'created_at'        => now(),
            ]);

            return back()->with('warning', 'Shortfall recorded. Dispatcher and store manager notified.');
        } catch (\Exception $e) {
            \Log::error('Loader Exception Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to log exception: ' . $e->getMessage());
        }
    }

    public function loaderConfirmItem(Request $request)
    {
        // Mark individual route leg item as loaded
        DB::table('route_legs')
            ->where('leg_id', $request->leg_id)
            ->update(['actual_depart_time' => now()]); // repurposed field

        return response()->json(['success' => true]);
    }

    // ─── DRIVER ────────────────────────────────────────────────────────────

    public function driverPTI()
    {
        $user    = Auth::user();
        $vehicle = DB::table('vehicles')->where('vehicle_id', $user->vehicle_id)->first();
        return view('driver.pti', compact('user', 'vehicle'));
    }

    public function driverRoute()
    {
        $user  = Auth::user();
        $today = now()->toDateString();

        // Find driver's active trip
        $trip = Trip::where('operation_date', $today)
            ->whereHas('vehicle', fn($q) => $q->where('vehicle_id', $user->vehicle_id))
            ->whereIn('status', ['dispatched', 'planned'])
            ->first();

        $routeLegs = collect();
        $completedLegs = collect();

        if ($trip) {
            $allLegs = DB::table('route_legs')
                ->where('trip_id', $trip->trip_id)
                ->orderBy('seq')
                ->get();

            $completedLegs = $allLegs->filter(fn($l) => !is_null($l->actual_arrival_time));
            $routeLegs     = $allLegs->filter(fn($l) => is_null($l->actual_arrival_time))->values();
        }

        $vehicle = DB::table('vehicles')->where('vehicle_id', $user->vehicle_id)->first();

        return view('driver.route', compact('trip', 'routeLegs', 'completedLegs', 'user', 'vehicle'));
    }

    public function driverMarkArrival(Request $request)
    {
        $request->validate(['leg_id' => 'required']);

        DB::table('route_legs')->where('leg_id', $request->leg_id)->update([
            'actual_arrival_time' => now(),
        ]);

        return response()->json(['success' => true, 'arrived_at' => now()->format('H:i')]);
    }

    public function driverConfirmDelivery(Request $request)
    {
        $request->validate(['trip_id' => 'required', 'leg_id' => 'required']);

        DB::table('route_legs')->where('leg_id', $request->leg_id)->update([
            'leave_outlet_time' => now(),
        ]);

        // Store photo path if uploaded
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('pod_photos', 'public');
        }

        DB::table('delivery_confirmations')->insert([
            'id'              => Str::uuid(),
            'route_leg_id'    => $request->leg_id,
            'signature_data'  => $request->input('signature'),
            'photo_path'      => $photoPath,
            'confirmed_units' => $request->input('confirmed_units'),
            'created_at'      => now(),
        ]);

        // Update order status
        if ($request->order_ref) {
            Order::where('order_ref', $request->order_ref)->update(['status' => 'delivered']);
        }

        return response()->json(['success' => true]);
    }

    public function driverException(Request $request)
    {
        $request->validate(['reason' => 'required', 'leg_id' => 'required|uuid']);

        DB::table('delivery_confirmations')->insert([
            'id'               => Str::uuid(),
            'route_leg_id'     => $request->leg_id,
            'discrepancy_note' => '[EXCEPTION] ' . $request->reason,
            'created_at'       => now(),
        ]);

        return back()->with('warning', "Exception logged: {$request->reason}. Dispatcher notified.");
    }

    public function driverBreak()
    {
        return view('driver.break');
    }

    public function driverBreakdown()
    {
        return view('driver.breakdown');
    }

    public function driverTripEnd(Request $request)
    {
        $today = now()->toDateString();
        $user = auth()->user();
        
        $trip = Trip::where('operation_date', $today)
            ->whereHas('vehicle', fn($q) => $q->where('vehicle_id', $user->vehicle_id))
            ->whereIn('status', ['dispatched', 'loading', 'planned'])
            ->first();
            
        if ($trip && $request->isMethod('post')) {
            $trip->update(['status' => 'completed']);
            return redirect()->route('driver.route')->with('success', 'Trip ended successfully. Shift closed.');
        }

        return view('driver.trip-end', compact('trip'));
    }

    // ─── STORE DEDICATED VIEWS ───────────────────────────────────────────────

    public function storeOrderNew()
    {
        $user = Auth::user();
        $outlet = DB::table('outlets')->where('outlet_id', $user->outlet_id)->first();
        return view('store.order-new', compact('user', 'outlet'));
    }

    public function storeReceiveView($id)
    {
        $user = Auth::user();
        $order = Order::where('order_ref', $id)->firstOrFail();
        $trip = null;
        $leg = null;
        if ($order->trip_id) {
            $trip = DB::table('trips')->where('trip_id', $order->trip_id)->first();
            $leg = DB::table('route_legs')->where('trip_id', $order->trip_id)->where('to_outlet', $user->outlet_id)->first();
        }
        return view('store.receive', compact('order', 'trip', 'leg'));
    }

    public function storeDisputeView($id)
    {
        $order = Order::where('order_ref', $id)->firstOrFail();
        return view('store.dispute', compact('order'));
    }

    // ─── DISPATCH DEDICATED VIEWS ────────────────────────────────────────────

    public function dispatchSequenceView($tripId)
    {
        $trip = DB::table('trips')->where('trip_id', $tripId)->firstOrFail();
        $legs = DB::table('route_legs')->where('trip_id', $tripId)->orderBy('seq')->get();
        return view('dispatch.sequence', compact('trip', 'legs'));
    }

    public function dispatchDeferralView($orderRef)
    {
        $order = Order::where('order_ref', $orderRef)->firstOrFail();
        return view('dispatch.deferral', compact('order'));
    }

    public function dispatchVehicleView($vehicleId)
    {
        $vehicle = DB::table('vehicles')->where('vehicle_id', $vehicleId)->firstOrFail();
        return view('dispatch.vehicle', compact('vehicle'));
    }

    public function dispatchEmergencyView($tripId)
    {
        $trip = DB::table('trips')->where('trip_id', $tripId)->firstOrFail();
        return view('dispatch.emergency', compact('trip'));
    }

    public function dispatchReports()
    {
        $today = now()->toDateString();
        $totalOrders = Order::where('order_date', $today)->count();
        $deliveredOrders = Order::where('order_date', $today)->where('status', 'delivered')->count();
        $totalTrips = DB::table('trips')->where('operation_date', $today)->count();
        $deferredCount = Order::where('order_date', $today)->where('status', 'deferred')->count();
        
        $deferrals = DB::table('deferral_log')
            ->join('orders', 'deferral_log.order_ref', '=', 'orders.order_ref')
            ->select('deferral_log.*', 'orders.outlet_id', 'orders.temp_requirement', 'orders.order_units', 'orders.order_weight_kg')
            ->orderByDesc('deferral_log.created_at')
            ->get();

        return view('dispatch.reports', compact('today', 'totalOrders', 'deliveredOrders', 'totalTrips', 'deferredCount', 'deferrals'));
    }

    // ─── LOADER DEDICATED VIEWS ──────────────────────────────────────────────

    public function loaderInspectView($tripId)
    {
        $trip = DB::table('trips')->where('trip_id', $tripId)->firstOrFail();
        return view('loader.inspect', compact('trip'));
    }

    public function loaderLoadView($tripId)
    {
        $trip = DB::table('trips')->where('trip_id', $tripId)->firstOrFail();
        $legs = DB::table('route_legs')->where('trip_id', $tripId)->orderByDesc('seq')->get();
        return view('loader.load', compact('trip', 'legs'));
    }

    public function loaderExceptionView($tripId)
    {
        $trip = DB::table('trips')->where('trip_id', $tripId)->firstOrFail();
        $orders = Order::where('trip_id', $tripId)->get();
        return view('loader.exception', compact('trip', 'orders'));
    }

    public function loaderReleaseView($tripId)
    {
        $trip = DB::table('trips')->where('trip_id', $tripId)->firstOrFail();
        return view('loader.release', compact('trip'));
    }

    public function loaderRevisionView($tripId)
    {
        $trip = DB::table('trips')->where('trip_id', $tripId)->firstOrFail();
        return view('loader.revision', compact('trip'));
    }

    // ─── DRIVER DEDICATED VIEWS ──────────────────────────────────────────────

    public function driverStopView($legId)
    {
        $leg = DB::table('route_legs')->where('leg_id', $legId)->firstOrFail();
        $outlet = DB::table('outlets')->where('outlet_id', $leg->to_outlet)->first();
        return view('driver.stop', compact('leg', 'outlet'));
    }

    public function driverPodView($legId)
    {
        $leg = DB::table('route_legs')->where('leg_id', $legId)->firstOrFail();
        $order = Order::where('trip_id', $leg->trip_id)->where('outlet_id', $leg->to_outlet)->first();
        return view('driver.pod', compact('leg', 'order'));
    }

    public function driverStopExceptionView($legId)
    {
        $leg = DB::table('route_legs')->where('leg_id', $legId)->firstOrFail();
        return view('driver.exception', compact('leg'));
    }
}

