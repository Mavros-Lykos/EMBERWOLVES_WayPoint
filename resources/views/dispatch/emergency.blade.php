<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Rescue Handoff — Waypoint Dispatch</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        :root {
            --bg-page: #0f172a;
            --surface: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --danger: #ef4444;
            --amber: #f59e0b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; padding: 24px; display: flex; align-items: center; justify-content: center; }
        .modal-card { width: 100%; max-width: 840px; background: var(--surface); border: 2px solid var(--danger); border-radius: 16px; overflow: hidden; box-shadow: 0 0 50px rgba(239,68,68,0.3); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #0b1120; }
        .modal-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }
        .alert-hero { background: rgba(239, 68, 68, 0.15); border: 1px solid var(--danger); border-radius: 12px; padding: 18px; display: flex; align-items: center; justify-content: space-between; }
        .countdown-time { font-size: 2rem; font-weight: 800; color: var(--danger); font-family: monospace; }
        .rescue-option { background: #0b1120; border: 1.5px solid var(--border); border-radius: 12px; padding: 16px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: all 0.2s; }
        .rescue-option.active { border-color: var(--amber); background: rgba(245,158,11,0.08); }
        .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); background: #0b1120; display: flex; justify-content: flex-end; gap: 12px; }
        .btn-cancel { padding: 0 20px; height: 48px; border-radius: 8px; background: transparent; border: 1px solid var(--border); color: var(--text-muted); cursor: pointer; text-decoration: none; display: flex; align-items: center; }
        .btn-dispatch-rescue { padding: 0 24px; height: 48px; border-radius: 8px; background: var(--amber); color: #000; font-weight: 800; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>
    <div class="modal-card">
        <div class="modal-header">
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 800; color: #fca5a5; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="color: var(--danger);">emergency</span>
                    Critical Breakdown Handoff: Trip #{{ $trip->trip_id }} ({{ $trip->vehicle_id }})
                </h1>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Stranded Location: Alawwa Trunk Road (A1) | Driver: Saman K.</div>
            </div>
            <span style="background: rgba(239,68,68,0.2); color: #fca5a5; font-size: 0.75rem; font-weight: 800; padding: 4px 10px; border-radius: 6px;">DISP-07 / D3</span>
        </div>

        <div class="modal-body">
            <div class="alert-hero">
                <div>
                    <div style="font-weight: 700; color: #fca5a5;">Cold-Chain Spoilage Window Running</div>
                    <div style="font-size: 0.85rem; color: #cbd5e1; margin-top: 2px;">Reefer compressor offline. Chilled dairy will breach 4.0°C safety ceiling.</div>
                </div>
                <div class="countdown-time">01:24:10</div>
            </div>

            <div style="font-size: 0.95rem; font-weight: 700;">Select Nearest Rescue Vehicle to Adopt Remaining Stops:</div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <label class="rescue-option active">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="radio" name="rescue_vehicle" checked style="accent-color: var(--amber); width: 18px; height: 18px;">
                            <span style="font-weight: 700; font-size: 1rem;">VEH-011 (Isuzu 4.5T Reefer Truck)</span>
                            <span style="background: rgba(74,222,128,0.15); color: #4ade80; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">EMPTY / STANDBY</span>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-left: 28px; margin-top: 4px;">
                            Current Location: Peliyagoda DC Bay 02 (22 km from incident | ETA: 28 mins)
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="color: #4ade80; font-weight: 700; font-size: 0.9rem;">100% Temp Match</span>
                    </div>
                </label>

                <label class="rescue-option">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="radio" name="rescue_vehicle" style="accent-color: var(--amber); width: 18px; height: 18px;">
                            <span style="font-weight: 700; font-size: 1rem;">VEH-008 (Tata 1.2T Reefer Van)</span>
                            <span style="background: rgba(245,158,11,0.15); color: var(--amber); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">RETURNING EMPTY</span>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-left: 28px; margin-top: 4px;">
                            Current Location: Giriulla (14 km from incident | ETA: 18 mins)
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="color: var(--amber); font-weight: 700; font-size: 0.9rem;">Capacity Constrained (Van)</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="modal-footer">
            <a href="{{ route('dispatch.live') }}" class="btn-cancel">Close</a>
            <button type="button" class="btn-dispatch-rescue" onclick="alert('Rescue vehicle VEH-011 dispatched! Digital QR handoff manifest transmitted to driver Saman.'); window.location.href='{{ route('dispatch.live') }}'">
                <span class="material-symbols-outlined">send</span>
                Dispatch Rescue Vehicle & Re-route Stops
            </button>
        </div>
    </div>
</body>
</html>
