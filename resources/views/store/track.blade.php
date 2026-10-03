<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order {{ $order->order_ref }} - Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --surf: #f8fafc;
            --surf-c: #ffffff;
            --on-surf: #0f172a;
            --outline: #e2e8f0;
            --success: #10b981;
            --warn: #f59e0b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surf);
            color: var(--on-surf);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .top-bar {
            background: var(--surf-c);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--outline);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--on-surf);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .page-title:hover {
            color: var(--primary);
        }

        .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }
        
        @media(max-width: 900px) {
            .container { grid-template-columns: 1fr; }
        }

        .card {
            background: var(--surf-c);
            border-radius: 16px;
            border: 1px solid var(--outline);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--outline);
            background: #f1f5f9;
            font-weight: 600;
            font-size: 16px;
        }

        .card-body {
            padding: 24px;
            flex: 1;
        }

        .map-placeholder {
            width: 100%;
            height: 400px;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #64748b;
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--outline);
        }
        .data-row:last-child { border-bottom: none; }
        
        .data-label { color: #64748b; font-size: 14px; }
        .data-val { font-weight: 500; font-size: 14px; text-align: right; }

        .timeline {
            position: relative;
            padding-left: 32px;
            margin-top: 24px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--outline);
        }
        .tl-item {
            position: relative;
            margin-bottom: 24px;
        }
        .tl-item:last-child { margin-bottom: 0; }
        .tl-dot {
            position: absolute;
            left: -32px;
            top: 2px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--surf-c);
            border: 2px solid var(--outline);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .tl-dot.active {
            border-color: var(--primary);
            background: var(--primary);
            color: #fff;
        }
        .tl-dot.done {
            border-color: var(--success);
            background: var(--success);
            color: #fff;
        }
        .tl-content { font-size: 14px; }
        .tl-title { font-weight: 600; margin-bottom: 4px; }
        .tl-desc { color: #64748b; font-size: 13px; }

    </style>
</head>
<body>

    <header class="top-bar">
        <a href="{{ route('store.dashboard') }}" class="page-title">
            <span class="material-symbols-outlined">arrow_back</span>
            {{ __('Order') }} #{{ $order->order_ref }}
        </a>
        <div style="display:flex; align-items:center; gap:16px;">
            <div style="font-size:14px; font-weight:500;">{{ auth()->user()->outlet_id }}</div>
        </div>
    </header>

    <div class="container">
        
        <!-- LEFT COL: MAP & ROUTE PROGRESS -->
        <div class="card">
            <div class="card-header">{{ __('Live Route Progress') }}</div>
            <div class="card-body">
                @if($trip && $trip->status != 'planned')
                <div class="map-placeholder">
                    <span class="material-symbols-outlined" style="font-size:48px; margin-bottom:16px">map</span>
                    <div>{{ __('Live Telemetry Map (Simulated)') }}</div>
                    <div style="font-size:13px; margin-top:8px;">{{ __('Vehicle is in transit on assigned route corridor.') }}</div>
                </div>
                
                <div class="timeline">
                    <div class="tl-item">
                        <div class="tl-dot done"><span class="material-symbols-outlined" style="font-size:14px">check</span></div>
                        <div class="tl-content">
                            <div class="tl-title">{{ __('Dispatched from Hub') }}</div>
                            <div class="tl-desc">{{ __('Vehicle left depot') }}</div>
                        </div>
                    </div>
                    <div class="tl-item">
                        <div class="tl-dot {{ $leg && $leg->actual_arrival_time ? 'done' : 'active' }}">
                            <span class="material-symbols-outlined" style="font-size:14px">{{ $leg && $leg->actual_arrival_time ? 'check' : 'local_shipping' }}</span>
                        </div>
                        <div class="tl-content">
                            <div class="tl-title">{{ __('In Transit to') }} {{ auth()->user()->outlet_id }}</div>
                            <div class="tl-desc">
                                @if($leg && $leg->planned_arrival_time)
                                    {{ __('Estimated Arrival:') }} {{ \Carbon\Carbon::parse($leg->planned_arrival_time)->format('h:i A') }}
                                @else
                                    {{ __('Calculating ETA...') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tl-item">
                        <div class="tl-dot"><span class="material-symbols-outlined" style="font-size:14px">store</span></div>
                        <div class="tl-content">
                            <div class="tl-title">{{ __('Store Delivery') }}</div>
                            <div class="tl-desc">{{ __('Pending dock receipt') }}</div>
                        </div>
                    </div>
                </div>
                @else
                <div style="text-align:center; padding:60px 0;">
                    <span class="material-symbols-outlined" style="font-size:64px; color:#cbd5e1; margin-bottom:16px">pending_actions</span>
                    <h3 style="margin:0 0 8px 0; color:#475569;">{{ __('Pending Dispatch') }}</h3>
                    <p style="color:#64748b; margin:0; font-size:14px;">{{ __('This order is either waiting for allocation or the vehicle has not left the depot yet.') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- RIGHT COL: ORDER DETAILS -->
        <div class="card">
            <div class="card-header">{{ __('Order Specification') }}</div>
            <div class="card-body" style="padding:0;">
                <div style="padding:0 24px;">
                    <div class="data-row">
                        <div class="data-label">{{ __('Status') }}</div>
                        <div class="data-val" style="color:var(--primary)">{{ strtoupper($order->status) }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">{{ __('Target Date') }}</div>
                        <div class="data-val">{{ $order->order_date }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">{{ __('Category') }}</div>
                        <div class="data-val">{{ ucfirst($order->temp_requirement) }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">{{ __('Total Units') }}</div>
                        <div class="data-val">{{ $order->order_units }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">{{ __('Weight') }}</div>
                        <div class="data-val">{{ number_format($order->order_weight_kg) }} kg</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">{{ __('Volume') }}</div>
                        <div class="data-val">{{ number_format($order->order_volume_m3, 2) }} m³</div>
                    </div>
                </div>
                
                @if($trip)
                <div class="card-header" style="border-top:1px solid var(--outline); margin-top:12px;">{{ __('Assigned Vehicle') }}</div>
                <div style="padding:0 24px;">
                    <div class="data-row">
                        <div class="data-label">{{ __('Trip Reference') }}</div>
                        <div class="data-val">{{ $trip->trip_id }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">{{ __('Vehicle ID') }}</div>
                        <div class="data-val">{{ $trip->vehicle_id }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">{{ __('Vehicle Type') }}</div>
                        <div class="data-val">{{ $trip->vehicle ? $trip->vehicle->type : 'Unknown' }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

</body>
</html>
