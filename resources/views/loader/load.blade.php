<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIFO Loading Checklist — Waypoint Depot</title>
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
            --danger: #ef4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
        header { height: 72px; padding: 0 24px; background: var(--surface); border-bottom: 2px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .back-btn { height: 52px; padding: 0 16px; border-radius: 12px; background: #1e293b; color: var(--text-main); text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.95rem; border: 1px solid var(--border); }
        .container { max-width: 900px; margin: 24px auto; padding: 0 20px; width: 100%; display: flex; flex-direction: column; gap: 20px; }
        .truck-diagram { background: var(--surface); border: 2px solid var(--border); border-radius: 16px; padding: 20px; display: flex; flex-direction: column; gap: 12px; }
        .compartment-flow { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 700; }
        .comp-box { flex: 1; padding: 12px; border-radius: 8px; text-align: center; border: 1.5px solid var(--border); }
        .comp-cab { background: #1e293b; color: var(--text-muted); }
        .comp-front { background: rgba(56,189,248,0.15); border-color: var(--primary); color: var(--primary); }
        .comp-mid { background: rgba(245,158,11,0.15); border-color: var(--amber); color: var(--amber); }
        .comp-rear { background: rgba(34,197,94,0.15); border-color: var(--success); color: var(--success); }
        .lifo-card { background: var(--surface); border: 2px solid var(--border); border-radius: 16px; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .lifo-badge { width: 44px; height: 44px; border-radius: 12px; background: #1e293b; color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; }
        .btn-load { height: 56px; padding: 0 24px; border-radius: 12px; background: var(--success); color: #000; font-weight: 800; font-size: 1rem; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; }
        .action-footer { display: flex; gap: 16px; margin-top: 12px; }
        .btn-exception { height: 68px; padding: 0 24px; border-radius: 16px; background: rgba(239,68,68,0.15); border: 2px solid var(--danger); color: #fca5a5; font-weight: 800; font-size: 1rem; cursor: pointer; display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-seal { flex: 1; height: 68px; border-radius: 16px; background: var(--primary); border: none; color: #000; font-weight: 800; font-size: 1.15rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('loader.queue') }}" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
            Bay Queue
        </a>
        <div style="font-weight: 800; font-size: 1.2rem;">
            LIFO Bay Loading: {{ $trip->vehicle_id }}
        </div>
        <span style="background: rgba(56,189,248,0.2); color: var(--primary); padding: 6px 14px; border-radius: 10px; font-weight: 800; font-size: 0.85rem;">LOAD-03</span>
    </header>

    <div class="container">
        <!-- 2D TRUCK CROSS-SECTION COMPARTMENT GUIDE -->
        <div class="truck-diagram">
            <div style="font-size: 0.85rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">
                2D Vehicle Compartment LIFO Loading Direction:
            </div>
            <div class="compartment-flow">
                <div class="comp-box comp-cab">FRONT CAB [DRIVER]</div>
                <span class="material-symbols-outlined" style="color: var(--text-muted);">arrow_forward</span>
                <div class="comp-box comp-front">LOAD FIRST: DEEP NOSE (Final Stop)</div>
                <span class="material-symbols-outlined" style="color: var(--text-muted);">arrow_forward</span>
                <div class="comp-box comp-mid">MID CAB (Stop 2)</div>
                <span class="material-symbols-outlined" style="color: var(--text-muted);">arrow_forward</span>
                <div class="comp-box comp-rear">LOAD LAST: REAR DOOR (First Stop)</div>
            </div>
        </div>

        <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">
            Staging Order (Reverse Unload Sequence — Last In, First Out):
        </div>

        @forelse($legs as $leg)
            <div class="lifo-card">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div class="lifo-badge">#{{ $leg->seq }}</div>
                    <div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: #fff;">{{ $leg->to_outlet }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">
                            Unload Sequence: Stop {{ $leg->seq }} | Distance: {{ $leg->distance_km ?? 18 }} km
                        </div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="background: rgba(34,197,94,0.15); color: var(--success); padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">
                        Verified Staged
                    </span>
                    <button class="btn-load" onclick="this.innerText='✓ LOADED'; this.style.background='#334155'; this.style.color='#94a3b8';">
                        <span class="material-symbols-outlined">inventory_2</span>
                        CONFIRM LOAD (64px)
                    </button>
                </div>
            </div>
        @empty
            <div style="text-align: center; color: var(--text-muted); padding: 32px;">No active stops queued for this trip.</div>
        @endforelse

        <div class="action-footer">
            <a href="{{ route('loader.exception.view', $trip->trip_id) }}" class="btn-exception">
                <span class="material-symbols-outlined">report_problem</span>
                Flag Shortage / Damage
            </a>
            <a href="{{ route('loader.release.view', $trip->trip_id) }}" class="btn-seal">
                <span class="material-symbols-outlined">lock</span>
                ALL ITEMS LOADED — PROCEED TO SEAL (64px)
            </a>
        </div>
    </div>
</body>
</html>
