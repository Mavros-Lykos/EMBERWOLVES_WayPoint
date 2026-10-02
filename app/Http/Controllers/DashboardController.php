<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Order;
use App\Models\RouteLeg;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function store()
    {
        $user = Auth::user();
        // Get orders for this store's outlet (Assuming user has an outlet_id for demo, but we use depot for now)
        // Let's just fetch some dummy orders for the UI
        $expectedOrders = Order::where('status', 'allocated')->take(3)->get();
        $disputes = Order::where('status', 'deferred')->count();
        
        $incoming = Order::where('status', 'in_transit')->with('trip')->first();

        return view('store.dashboard', compact('expectedOrders', 'disputes', 'incoming'));
    }

    public function dispatchOverview()
    {
        $user = Auth::user();
        $activeTrips = Trip::where('status', 'dispatched')->get();
        
        return view('dispatch.overview', compact('activeTrips'));
    }

    public function loaderQueue()
    {
        $user = Auth::user();
        $currentTrip = Trip::where('status', 'planned')->first();
        $upcomingTrips = Trip::where('status', 'planned')->skip(1)->take(5)->get();
        
        // If current trip exists, fetch its orders
        $orders = [];
        if ($currentTrip) {
            $orders = Order::where('trip_id', $currentTrip->trip_id)->get();
        }

        return view('loader.queue', compact('currentTrip', 'upcomingTrips', 'orders'));
    }

    public function driverRoute()
    {
        $user = Auth::user();
        
        // Find the trip for the driver's vehicle
        $trip = Trip::where('vehicle_id', $user->vehicle_id)
                    ->whereIn('status', ['planned', 'dispatched'])
                    ->first();
                    
        $routeLegs = [];
        if ($trip) {
            $routeLegs = RouteLeg::where('trip_id', $trip->trip_id)->orderBy('seq', 'asc')->get();
        }

        return view('driver.route', compact('trip', 'routeLegs'));
    }
}
