<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>End of Trip - Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    <style>
        :root {
            --primary: #3b82f6;
            --surf: #0f172a;
            --surf-c: #1e293b;
            --on-surf: #f8fafc;
            --outline: #334155;
            --success: #10b981;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surf);
            color: var(--on-surf);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .top-bar {
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surf-c);
            border-bottom: 1px solid var(--outline);
        }

        .content {
            flex: 1;
            padding: 24px;
            display: flex;
            flex-direction: column;
        }

        .hero {
            text-align: center;
            padding: 32px 0;
        }

        .success-icon {
            width: 80px; height: 80px;
            background: rgba(16, 185, 129, 0.1);
            border: 2px solid var(--success);
            color: var(--success);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px auto;
        }

        .stat-card {
            background: var(--surf-c);
            border: 1px solid var(--outline);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-label { color: #94a3b8; font-size: 14px; }
        .stat-val { font-size: 18px; font-weight: 600; }

        .btn-large {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 20px;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            color: #fff;
            background: var(--success);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
            cursor: pointer;
            margin-top: auto;
            transition: all 0.2s;
        }
        .btn-large:active { background: #059669; transform: scale(0.98); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.5); }
        .btn-large:disabled { background: var(--outline); color: #94a3b8; cursor: not-allowed; transform: none; }

        .checkbox-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            background: rgba(255,255,255,0.05);
            border-radius: 8px;
            margin-bottom: 12px;
            border: 1px solid var(--outline);
        }

        input[type="checkbox"] {
            width: 24px; height: 24px;
            accent-color: var(--success);
        }

    </style>
</head>
<body>

    <div class="top-bar">
        <a href="{{ route('driver.route') }}" style="color:#94a3b8; text-decoration:none;">
            <span class="material-symbols-outlined">close</span>
        </a>
        <div style="font-size:16px; font-weight:600;">{{ __('Trip Completion') }}</div>
        <div style="width:24px;"></div>
    </div>

    <div class="content">
        @if($trip)
        <div class="hero">
            <div class="success-icon">
                <span class="material-symbols-outlined" style="font-size:40px;">task_alt</span>
            </div>
            <h1 style="margin:0 0 8px 0; font-size:24px;">{{ __('Route Finished') }}</h1>
            <p style="margin:0; color:#94a3b8; font-size:15px;">{{ __('All deliveries on Trip') }} {{ $trip->trip_id }} {{ __('have been completed.') }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-label">{{ __('Return Depot') }}</span>
            <span class="stat-val">{{ $trip->vehicle->depot }}</span>
        </div>

        <form action="{{ route('driver.tripEnd.post') }}" method="POST" style="display:flex; flex-direction:column; flex:1;">
            @csrf
            <h3 style="font-size:16px; margin:24px 0 12px 0;">{{ __('Post-Trip Inspection') }}</h3>
            
            <label class="checkbox-row">
                <input type="checkbox" required>
                <div>
                    <div style="font-weight:500">{{ __('Vehicle Parked Safely') }}</div>
                    <div style="font-size:13px; color:#94a3b8">{{ __('Keys returned to depot dispatch.') }}</div>
                </div>
            </label>
            
            <label class="checkbox-row">
                <input type="checkbox" required>
                <div>
                    <div style="font-weight:500">{{ __('Reefer / Cab Empty') }}</div>
                    <div style="font-size:13px; color:#94a3b8">{{ __('All crates, pallets, and trash removed.') }}</div>
                </div>
            </label>

            <button type="submit" class="btn-large" style="margin-top:32px;">
                <span class="material-symbols-outlined">power_settings_new</span>
                {{ __('End Shift & Sign Out') }}
            </button>
        </form>
        
        @else
        <div class="hero" style="margin-top:40px; background:var(--surf-c); border:1px solid var(--outline); border-radius:16px; padding:40px 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.3);">
            <span class="material-symbols-outlined" style="font-size:64px; color:#334155; margin-bottom:16px;">verified</span>
            <h2>{{ __('No Active Trip') }}</h2>
            <p style="color:#94a3b8;">{{ __('You have no active trips running.') }}</p>
            <a href="{{ route('driver.route') }}" style="color:var(--primary); text-decoration:none; display:inline-block; margin-top:24px; font-weight:600;">
                {{ __('Return Home') }}
            </a>
        </div>
        @endif
    </div>

</body>
</html>
