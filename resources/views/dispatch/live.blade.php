<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Fleet Control Tower - Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        :root {
            --primary: #3b82f6;
            --surf: #0f172a;
            --surf-c: #1e293b;
            --on-surf: #f8fafc;
            --outline: #334155;
            --success: #22c55e;
            --warn: #eab308;
            --crit: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surf);
            color: var(--on-surf);
            margin: 0;
            padding: 0;
        }

        .top-bar {
            background: var(--surf-c);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--outline);
        }

        .container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            height: calc(100vh - 65px);
        }

        /* Map Area */
        .map-area {
            background: #0f172a;
            position: relative;
            border-right: 1px solid var(--outline);
            background-image: radial-gradient(#334155 1px, transparent 1px);
            background-size: 24px 24px;
            overflow: hidden;
        }
        
        .map-overlay {
            position: absolute;
            top: 24px;
            left: 24px;
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid var(--outline);
            padding: 16px;
            border-radius: 12px;
            backdrop-filter: blur(8px);
        }

        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-dot.green { background: var(--success); box-shadow: 0 0 8px var(--success); }
        .status-dot.amber { background: var(--warn); box-shadow: 0 0 8px var(--warn); }
        .status-dot.red { background: var(--crit); box-shadow: 0 0 8px var(--crit); }

        /* Vehicle Trackers on map (simulated) */
        .v-tracker {
            position: absolute;
            width: 32px;
            height: 32px;
            background: var(--surf-c);
            border: 2px solid var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--success);
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            transition: all 1s;
        }
        .v-tracker.delayed { border-color: var(--warn); color: var(--warn); }
        .v-tracker.critical { border-color: var(--crit); color: var(--crit); }
        
        .v-tracker-label {
            position: absolute;
            top: -24px;
            background: var(--surf-c);
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 4px;
            white-space: nowrap;
            border: 1px solid var(--outline);
            font-weight: 600;
        }

        /* Right Panel */
        .side-panel {
            background: var(--surf-c);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--outline);
            font-weight: 600;
            display: flex;
            justify-content: space-between;
        }

        .v-card {
            padding: 16px 24px;
            border-bottom: 1px solid var(--outline);
            cursor: pointer;
            transition: background 0.2s;
        }
        .v-card:hover { background: rgba(255,255,255,0.05); }

        .v-card.delayed {
            border-left: 4px solid var(--warn);
        }

        .btn {
            background: var(--outline);
            color: var(--on-surf);
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 12px;
        }
        .btn:hover { background: #475569; }

        .nav-links {
            display: flex;
            gap: 24px;
        }
        .nav-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        .nav-links a.active { color: #fff; font-weight: 600; border-bottom: 2px solid var(--primary); padding-bottom: 4px;}
        .nav-links a:hover { color: #fff; }

    </style>
</head>
<body>

    <header class="top-bar">
        <div style="font-size:18px; font-weight:700; letter-spacing:1px">WAYPOINT CONTROL TOWER</div>
        <div class="nav-links">
            <a href="{{ route('dispatch.overview') }}">{{ __('Overview') }}</a>
            <a href="{{ route('dispatch.plan') }}">{{ __('Planning Canvas') }}</a>
            <a href="{{ route('dispatch.live') }}" class="active">{{ __('Live Fleet') }}</a>
            <a href="{{ route('dispatch.crisis') }}">{{ __('Crisis Mitigate') }}</a>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-weight:600">Exit</button>
        </form>
    </header>

    <div class="container">
        <div class="map-area">
            
            <div class="map-overlay">
                <div style="font-weight:600; margin-bottom:12px;">Live Telemetry Overview</div>
                <div style="font-size:13px; margin-bottom:8px;"><span class="status-dot green"></span> {{ $activeTrips->count() - $delayedTrips->count() }} On Time</div>
                <div style="font-size:13px; margin-bottom:8px;"><span class="status-dot amber"></span> {{ $delayedTrips->count() }} Delayed (>15m)</div>
                <div style="font-size:13px;"><span class="status-dot red"></span> 0 Critical (Breakdowns)</div>
            </div>

            <!-- Simulated Map Trackers -->
            @foreach($activeTrips as $index => $trip)
                @php
                    $isDelayed = $delayedTrips->contains('trip_id', $trip->trip_id);
                    $top = rand(10, 80) . '%';
                    $left = rand(10, 80) . '%';
                @endphp
                <div class="v-tracker {{ $isDelayed ? 'delayed' : '' }}" style="top:{{ $top }}; left:{{ $left }};">
                    <span class="material-symbols-outlined" style="font-size:16px">local_shipping</span>
                    <div class="v-tracker-label">{{ $trip->vehicle_id }}</div>
                </div>
            @endforeach
            
            @if($activeTrips->count() === 0)
            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); color:#64748b; text-align:center;">
                <span class="material-symbols-outlined" style="font-size:64px; opacity:0.5">satellite_alt</span>
                <p>No active vehicles in transit</p>
            </div>
            @endif

        </div>

        <div class="side-panel">
            <div class="panel-header">
                <span>Active Vehicle Monitoring Queue</span>
                <span style="color:#94a3b8; font-size:14px;">{{ $activeTrips->count() }} total</span>
            </div>

            @foreach($delayedTrips as $trip)
            <div class="v-card delayed">
                <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                    <strong style="color:var(--warn)">⚠️ {{ $trip->vehicle_id }} ({{ $trip->district }} Run)</strong>
                    <span style="font-size:12px; color:#94a3b8">Trip {{ $trip->trip_number }}</span>
                </div>
                <div style="font-size:13px; color:#cbd5e1; margin-bottom:4px;">Delay: +28 mins (Traffic at Alawwa)</div>
                <div style="font-size:13px; color:#f8fafc; font-weight:500;">Next Stop: OUT051 Fresh at Risk of 08:00 AM</div>
                <div>
                    <button class="btn">Reroute</button>
                    <button class="btn">Call Driver</button>
                    <button class="btn">Alert Store</button>
                </div>
            </div>
            @endforeach

            @foreach($activeTrips->whereNotIn('trip_id', $delayedTrips->pluck('trip_id')) as $trip)
            <div class="v-card">
                <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                    <strong style="color:var(--success)">🟢 {{ $trip->vehicle_id }} ({{ $trip->district }} Run)</strong>
                    <span style="font-size:12px; color:#94a3b8">Trip {{ $trip->trip_number }}</span>
                </div>
                <div style="font-size:13px; color:#cbd5e1; margin-bottom:4px;">On track. ETA next stop: 07:12 AM</div>
            </div>
            @endforeach
            
            @if($activeTrips->count() === 0)
            <div style="padding:32px; text-align:center; color:#64748b; font-size:14px;">
                Queue empty. All vehicles are at depot.
            </div>
            @endif
        </div>
    </div>

</body>
</html>
