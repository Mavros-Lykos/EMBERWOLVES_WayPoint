<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Telemetry & Fuel Inspector — Waypoint Dispatch</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        :root {
            --bg-page: #0f172a;
            --surface: #1e293b;
            --primary: #38bdf8;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --success: #4ade80;
            --amber: #f59e0b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; padding: 24px; display: flex; align-items: center; justify-content: center; }
        .drawer-card { width: 100%; max-width: 580px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
        .drawer-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #0b1120; }
        .drawer-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }
        .stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .stat-box { background: #0b1120; border: 1px solid var(--border); border-radius: 10px; padding: 14px; display: flex; flex-direction: column; gap: 4px; }
        .stat-label { font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; }
        .stat-value { font-size: 1.25rem; font-weight: 700; color: var(--text-main); }
        .quota-bar { height: 12px; background: #334155; border-radius: 6px; overflow: hidden; margin-top: 8px; }
        .quota-fill { height: 100%; background: var(--amber); width: 72%; }
        .telemetry-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        .drawer-footer { padding: 16px 24px; border-top: 1px solid var(--border); background: #0b1120; display: flex; justify-content: flex-end; }
        .btn-close { padding: 0 20px; height: 44px; border-radius: 8px; background: var(--primary); color: #0f172a; font-weight: 700; border: none; cursor: pointer; text-decoration: none; display: flex; align-items: center; }
    </style>
</head>
<body>
    <div class="drawer-card">
        <div class="drawer-header">
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main);">Vehicle Telemetry: {{ $vehicle->vehicle_id }}</h1>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">{{ ucfirst($vehicle->type) }} ({{ ucfirst($vehicle->temp) }}) | Depot: {{ $vehicle->depot }}</div>
            </div>
            <span style="background: rgba(56,189,248,0.2); color: var(--primary); font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">DISP-06</span>
        </div>

        <div class="drawer-body">
            <div class="stat-grid">
                <div class="stat-box">
                    <span class="stat-label">Payload Capacity</span>
                    <span class="stat-value">{{ number_format($vehicle->weight_cap_kg) }} kg</span>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $vehicle->volume_cap_m3 }} m³ volume</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">Fuel Efficiency</span>
                    <span class="stat-value">{{ $vehicle->km_per_l }} km/L</span>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Type: {{ ucfirst($vehicle->fuel_type) }}</span>
                </div>
            </div>

            <div style="background: #0b1120; border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; font-weight: 700;">
                    <span>Weekly Fuel Km Quota Allowance</span>
                    <span style="color: var(--amber);">72% Consumed (864 / 1,200 km)</span>
                </div>
                <div class="quota-bar">
                    <div class="quota-fill"></div>
                </div>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">
                    Weekly Quota Balance: <strong>336 km remaining</strong> for Saturday runs.
                </div>
            </div>

            <div style="background: #0b1120; border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
                <div style="font-size: 0.9rem; font-weight: 700; margin-bottom: 8px;">Live Telemetry Stream</div>
                <div class="telemetry-row">
                    <span style="color: var(--text-muted);">Current Speed</span>
                    <span style="font-weight: 600;">44 km/h (Normal Transit)</span>
                </div>
                <div class="telemetry-row">
                    <span style="color: var(--text-muted);">Reefer Compressor Temp</span>
                    <span style="font-weight: 600; color: var(--success);">2.8°C (Optimal Setpoint)</span>
                </div>
                <div class="telemetry-row">
                    <span style="color: var(--text-muted);">Assigned Driver</span>
                    <span style="font-weight: 600;">Saman K. (DRV-084)</span>
                </div>
                <div class="telemetry-row" style="border-bottom: none;">
                    <span style="color: var(--text-muted);">GPS Coordinates</span>
                    <span style="font-family: monospace;">6.8341° N, 79.8652° E</span>
                </div>
            </div>
        </div>

        <div class="drawer-footer">
            <a href="{{ route('dispatch.live') }}" class="btn-close">Close Drawer</a>
        </div>
    </div>
</body>
</html>
