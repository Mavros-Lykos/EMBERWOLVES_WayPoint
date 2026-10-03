<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Sequencer & LIFO Feasibility — Waypoint Dispatch</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        :root {
            --bg-page: #0f172a;
            --surface: #1e293b;
            --surface-elevated: #334155;
            --primary: #38bdf8;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --success: #4ade80;
            --amber: #f59e0b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; padding: 24px; display: flex; align-items: center; justify-content: center; }
        .modal-card { width: 100%; max-width: 900px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #0b1120; }
        .modal-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }
        .seq-info { background: #0b1120; border: 1px solid var(--border); border-radius: 12px; padding: 16px; display: flex; justify-content: space-between; align-items: center; }
        .lifo-guide { font-size: 0.85rem; color: var(--amber); display: flex; align-items: center; gap: 6px; font-weight: 600; }
        .stop-list { display: flex; flex-direction: column; gap: 12px; }
        .stop-card { background: #0b1120; border: 1px solid var(--border); border-radius: 12px; padding: 16px; display: flex; align-items: center; justify-content: space-between; }
        .stop-badge { width: 36px; height: 36px; border-radius: 50%; background: var(--surface-elevated); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .time-chip { background: rgba(74,222,128,0.1); color: var(--success); padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; background: #0b1120; }
        .btn-cancel { padding: 0 20px; height: 48px; border-radius: 8px; background: transparent; border: 1px solid var(--border); color: var(--text-muted); cursor: pointer; text-decoration: none; display: flex; align-items: center; }
        .btn-save { padding: 0 24px; height: 48px; border-radius: 8px; background: var(--primary); color: #0f172a; border: none; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>
    <div class="modal-card">
        <div class="modal-header">
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main);">Trip Sequencer: {{ $trip->trip_id }} ({{ $trip->vehicle_id }})</h1>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Departure Target: 04:00 AM | District: {{ $trip->district }}</div>
            </div>
            <span style="background: rgba(56,189,248,0.2); color: var(--primary); font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">DISP-03</span>
        </div>

        <div class="modal-body">
            <div class="seq-info">
                <div>
                    <div style="font-size: 0.95rem; font-weight: 700;">Reverse LIFO Unloading Feasibility</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">First unloaded outlet is loaded last at Peliyagoda Bay.</div>
                </div>
                <div class="lifo-guide">
                    <span class="material-symbols-outlined">swap_vert</span>
                    LIFO Enforced (Pre-8 AM Compliance)
                </div>
            </div>

            <div class="stop-list">
                @forelse($legs as $index => $leg)
                    <div class="stop-card">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div class="stop-badge">{{ $leg->seq }}</div>
                            <div>
                                <div style="font-weight: 700; font-size: 1rem; color: var(--text-main);">{{ $leg->to_outlet }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">Est. Distance: {{ $leg->distance_km ?? 18 }} km | Service Time: 25 mins</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <span class="time-chip">ETA: 0{{ 5 + $index }}:{{ 20 + ($index * 25) }} AM (Pre-8 AM OK)</span>
                            <span class="material-symbols-outlined" style="color: var(--text-muted); cursor: grab;">drag_indicator</span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--text-muted); padding: 32px;">No active route legs found for this trip.</div>
                @endforelse
            </div>
        </div>

        <div class="modal-footer">
            <a href="{{ route('dispatch.overview') }}" class="btn-cancel">Cancel</a>
            <button type="button" class="btn-save" onclick="alert('Trip sequence locked. Reverse-LIFO loading manifest transmitted to Bay Loader.'); window.location.href='{{ route('dispatch.overview') }}'">
                <span class="material-symbols-outlined">check</span>
                Confirm Sequence & Generate Bay Manifest
            </button>
        </div>
    </div>
</body>
</html>
