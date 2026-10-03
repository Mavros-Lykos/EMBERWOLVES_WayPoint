<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Load Bay Inspection — Waypoint Depot</title>
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
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
        header { height: 72px; padding: 0 24px; background: var(--surface); border-bottom: 2px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .back-btn { height: 52px; padding: 0 16px; border-radius: 12px; background: #1e293b; color: var(--text-main); text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.95rem; border: 1px solid var(--border); }
        .container { max-width: 840px; margin: 24px auto; padding: 0 20px; width: 100%; display: flex; flex-direction: column; gap: 20px; }
        .hero-banner { background: var(--surface); border: 2px solid var(--border); border-radius: 16px; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .check-item { min-height: 72px; background: var(--surface); border: 2px solid var(--border); border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s; user-select: none; }
        .check-item.checked { border-color: var(--success); background: rgba(34, 197, 94, 0.1); }
        .check-circle { width: 44px; height: 44px; border-radius: 12px; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; background: #1e293b; color: transparent; }
        .check-item.checked .check-circle { background: var(--success); border-color: var(--success); color: #000; }
        .action-row { display: flex; gap: 16px; margin-top: 12px; }
        .btn-proceed { flex: 1; height: 68px; background: var(--primary); color: #000; font-size: 1.15rem; font-weight: 800; border: none; border-radius: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('loader.queue') }}" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
            Bay Staging Queue
        </a>
        <div style="font-weight: 800; font-size: 1.2rem; display: flex; align-items: center; gap: 8px;">
            <span class="material-symbols-outlined" style="color: var(--primary);">fact_check</span>
            Pre-Load Dock Inspection: {{ $trip->vehicle_id }}
        </div>
        <span style="background: rgba(56,189,248,0.2); color: var(--primary); padding: 6px 14px; border-radius: 10px; font-weight: 800; font-size: 0.85rem;">LOAD-02</span>
    </header>

    <div class="container">
        <div class="hero-banner">
            <div>
                <div style="font-size: 1.25rem; font-weight: 800; color: #fff;">Trip #{{ $trip->trip_id }} (Bay 04)</div>
                <div style="font-size: 0.9rem; color: var(--text-muted); margin-top: 4px;">Target Departure: 04:00 AM | Vehicle: {{ $trip->vehicle_id }}</div>
            </div>
            <div style="text-align: right;">
                <span style="background: rgba(245,158,11,0.2); color: var(--amber); padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">Reefer Required</span>
            </div>
        </div>

        <div style="font-size: 1rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">
            Mandatory Physical Inspection Gate (Tap to Validate — 64px Targets):
        </div>

        <div class="check-item checked" onclick="this.classList.toggle('checked')">
            <div style="display: flex; align-items: center; gap: 16px;">
                <span class="material-symbols-outlined" style="font-size: 28px; color: var(--primary);">sanitizer</span>
                <div>
                    <div style="font-size: 1.05rem; font-weight: 700;">1. Cargo Box Clean & Odor-Free</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Floor swept, sanitised, and free of food debris or chemical contamination.</div>
                </div>
            </div>
            <div class="check-circle"><span class="material-symbols-outlined" style="font-size: 28px;">check</span></div>
        </div>

        <div class="check-item checked" onclick="this.classList.toggle('checked')">
            <div style="display: flex; align-items: center; gap: 16px;">
                <span class="material-symbols-outlined" style="font-size: 28px; color: var(--primary);">ac_unit</span>
                <div>
                    <div style="font-size: 1.05rem; font-weight: 700;">2. Reefer Pre-Cooled to Setpoint (&le; 4.0°C)</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Current temperature reading: <strong>2.9°C</strong>. Pre-chilling cycle complete.</div>
                </div>
            </div>
            <div class="check-circle"><span class="material-symbols-outlined" style="font-size: 28px;">check</span></div>
        </div>

        <div class="check-item checked" onclick="this.classList.toggle('checked')">
            <div style="display: flex; align-items: center; gap: 16px;">
                <span class="material-symbols-outlined" style="font-size: 28px; color: var(--primary);">shield</span>
                <div>
                    <div style="font-size: 1.05rem; font-weight: 700;">3. Load Bars & Ratchet Straps Onboard</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Minimum 4 functional cargo bars present to prevent transit topple.</div>
                </div>
            </div>
            <div class="check-circle"><span class="material-symbols-outlined" style="font-size: 28px;">check</span></div>
        </div>

        <div class="action-row">
            <a href="{{ route('loader.load.view', $trip->trip_id) }}" class="btn-proceed">
                <span class="material-symbols-outlined">double_arrow</span>
                PASS INSPECTION & BEGIN LIFO LOADING (64px)
            </a>
        </div>
    </div>
</body>
</html>
