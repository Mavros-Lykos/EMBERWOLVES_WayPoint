<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mid-Load Revision Lockout — Waypoint Depot</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        :root {
            --bg-page: #020617;
            --surface: #0f172a;
            --border: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --amber: #f59e0b;
            --danger: #ef4444;
            --success: #22c55e;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; padding: 24px; display: flex; align-items: center; justify-content: center; }
        .modal-card { width: 100%; max-width: 680px; background: var(--surface); border: 3px solid var(--amber); border-radius: 20px; overflow: hidden; box-shadow: 0 0 50px rgba(245,158,11,0.3); }
        .modal-header { padding: 20px 24px; border-bottom: 2px solid var(--border); background: #020617; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }
        .alert-strip { background: rgba(239,68,68,0.15); border: 1.5px solid var(--danger); border-radius: 12px; padding: 16px; color: #fca5a5; font-size: 0.95rem; line-height: 1.5; }
        .delta-table { background: #020617; border: 2px solid var(--border); border-radius: 12px; overflow: hidden; }
        .delta-row { padding: 14px 16px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem; }
        .delta-row:last-child { border-bottom: none; }
        .instruction-box { background: rgba(245,158,11,0.1); border-left: 4px solid var(--amber); border-radius: 4px; padding: 16px; font-size: 0.95rem; color: #fef08a; }
        .modal-footer { padding: 20px 24px; border-top: 2px solid var(--border); background: #020617; display: flex; flex-direction: column; gap: 8px; }
        .btn-ack { height: 68px; border-radius: 16px; background: var(--amber); color: #000; font-size: 1.15rem; font-weight: 800; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="modal-card">
        <div class="modal-header">
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 800; color: var(--amber); display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 28px;">gpp_maybe</span>
                    STOP LOADING IMMEDIATELY! — PLAN REVISED
                </h1>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Trip #{{ $trip->trip_id }} | Vehicle: {{ $trip->vehicle_id }}</div>
            </div>
            <span style="background: rgba(245,158,11,0.2); color: var(--amber); padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem;">LOAD-06 / D2</span>
        </div>

        <div class="modal-body">
            <div class="alert-strip">
                <strong>Dispatcher Revision Detected:</strong> Dispatcher Kamal has re-allocated vehicle <strong>{{ $trip->vehicle_id }}</strong> while bay loading was underway to resolve a high-priority fresh shortage.
            </div>

            <div class="delta-table">
                <div class="delta-row">
                    <div>
                        <div style="font-weight: 700; color: #fff;">Pallet #P-101 (OUT021 Panadura)</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">2 Crates Highland Ice Cream</div>
                    </div>
                    <span style="color: var(--danger); font-weight: 700; display: flex; align-items: center; gap: 4px;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">remove_circle</span>
                        REMOVED
                    </span>
                </div>
                <div class="delta-row">
                    <div>
                        <div style="font-weight: 700; color: #fff;">Pallet #P-118 (OUT009 Moratuwa North)</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">2 Crates Chilled Butter (Priority S1)</div>
                    </div>
                    <span style="color: var(--success); font-weight: 700; display: flex; align-items: center; gap: 4px;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span>
                        ADDED
                    </span>
                </div>
            </div>

            <div class="instruction-box">
                <strong>Mandatory Restaging Order:</strong> Pallet #P-118 must be placed into the <strong>MID-CAB COMPARTMENT</strong> to preserve the reverse-LIFO delivery order!
            </div>
        </div>

        <div class="modal-footer">
            <a href="{{ route('loader.load.view', $trip->trip_id) }}" class="btn-ack">
                <span class="material-symbols-outlined">touch_app</span>
                PRESS & HOLD TO ACKNOWLEDGE REVISION (64px)
            </a>
            <div style="text-align: center; font-size: 0.75rem; color: var(--text-muted);">
                Lockout prevents gate clearance until acknowledged by bay supervisor.
            </div>
        </div>
    </div>
</body>
</html>
