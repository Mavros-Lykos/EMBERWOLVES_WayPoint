<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Stop Arrival & Navigation — Waypoint Driver</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        :root {
            --bg-page: #020617;
            --surface: #0f172a;
            --border: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #38bdf8;
            --success: #22c55e;
            --amber: #f59e0b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; max-width: 440px; margin: 0 auto; display: flex; flex-direction: column; }
        header { height: 68px; padding: 0 16px; background: var(--surface); border-bottom: 2px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .back-btn { height: 48px; padding: 0 14px; border-radius: 10px; background: #1e293b; color: var(--text-main); text-decoration: none; display: flex; align-items: center; gap: 6px; font-weight: 700; font-size: 0.9rem; border: 1px solid var(--border); }
        .container { flex: 1; padding: 16px; display: flex; flex-direction: column; gap: 16px; overflow-y: auto; }
        .dock-card { background: var(--surface); border: 2px solid var(--border); border-radius: 16px; padding: 16px; display: flex; flex-direction: column; gap: 10px; }
        .card-label { font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 800; letter-spacing: 0.05em; }
        .nav-btn-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .nav-tool-btn { height: 52px; border-radius: 12px; background: #1e293b; border: 1.5px solid var(--border); color: #fff; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; }
        .bottom-action { padding: 16px; border-top: 2px solid var(--border); background: var(--surface); display: flex; flex-direction: column; gap: 10px; }
        .btn-arrived { height: 68px; border-radius: 16px; background: var(--success); color: #000; font-size: 1.15rem; font-weight: 800; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; }
        .btn-blocker { height: 52px; border-radius: 12px; background: rgba(245,158,11,0.15); border: 1.5px solid var(--amber); color: var(--amber); font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('driver.route') }}" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
            Route Map
        </a>
        <div style="font-weight: 800; font-size: 1.05rem;">
            Stop Arrival: {{ $leg->to_outlet }}
        </div>
        <span style="background: rgba(56,189,248,0.2); color: var(--primary); padding: 4px 8px; border-radius: 6px; font-weight: 800; font-size: 0.75rem;">DRV-03</span>
    </header>

    <div class="container">
        <div class="dock-card">
            <div class="card-label">Target Stop Information</div>
            <div style="font-size: 1.25rem; font-weight: 800; color: #fff;">{{ $outlet->brand ?? 'Waypoint Fresh' }} — {{ $leg->to_outlet }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">
                District: {{ $outlet->district ?? 'Western' }} | Dock Clearance: {{ $outlet->parking_constraint == 'van_only' ? 'Van Only (3.0m)' : 'Standard Heavy Truck OK' }}
            </div>
        </div>

        <div class="dock-card">
            <div class="card-label">Dock Entry & Service Notes</div>
            <ul style="font-size: 0.9rem; color: #cbd5e1; padding-left: 20px; line-height: 1.5;">
                <li>Entrance: Rear delivery service alley off Main Trunk Road</li>
                <li>Dock Type: {{ ucfirst($outlet->dock_type ?? 'rear_dock') }}</li>
                <li>Duty Guard Phone: 077-4921044</li>
            </ul>
        </div>

        <div class="nav-btn-grid">
            <a href="https://maps.google.com" target="_blank" class="nav-tool-btn">
                <span class="material-symbols-outlined" style="color: var(--primary);">navigation</span>
                Open in Maps
            </a>
            <a href="tel:0774921044" class="nav-tool-btn">
                <span class="material-symbols-outlined" style="color: var(--success);">call</span>
                Call Store Dock
            </a>
        </div>
    </div>

    <div class="bottom-action">
        <form action="{{ route('driver.arrival') }}" method="POST">
            @csrf
            <input type="hidden" name="leg_id" value="{{ $leg->leg_id }}">
            <button type="submit" class="btn-arrived">
                <span class="material-symbols-outlined">where_to_vote</span>
                MARK ARRIVED AT DOCK (64px)
            </button>
        </form>
        <a href="{{ route('driver.stop.exception', $leg->leg_id) }}" class="btn-blocker">
            <span class="material-symbols-outlined">warning</span>
            Report Dock Blocker / Closed Gate
        </a>
    </div>
</body>
</html>
