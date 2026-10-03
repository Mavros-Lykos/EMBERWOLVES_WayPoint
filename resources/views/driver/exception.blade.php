<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Report Dock Blocker — Waypoint Driver</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        :root {
            --bg-page: #020617;
            --surface: #0f172a;
            --border: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --danger: #ef4444;
            --amber: #f59e0b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; max-width: 440px; margin: 0 auto; display: flex; flex-direction: column; }
        header { height: 68px; padding: 0 16px; background: var(--surface); border-bottom: 2px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .back-btn { height: 48px; padding: 0 14px; border-radius: 10px; background: #1e293b; color: var(--text-main); text-decoration: none; display: flex; align-items: center; gap: 6px; font-weight: 700; font-size: 0.9rem; border: 1px solid var(--border); }
        .container { flex: 1; padding: 16px; display: flex; flex-direction: column; gap: 16px; overflow-y: auto; }
        .card { background: var(--surface); border: 2px solid var(--border); border-radius: 16px; padding: 16px; display: flex; flex-direction: column; gap: 12px; }
        .card-label { font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 800; letter-spacing: 0.05em; }
        .radio-box { background: #020617; border: 1.5px solid var(--border); border-radius: 12px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; font-size: 0.9rem; }
        .radio-box input { accent-color: var(--amber); width: 20px; height: 20px; }
        .bottom-action { padding: 16px; border-top: 2px solid var(--border); background: var(--surface); }
        .btn-abort { height: 68px; border-radius: 16px; background: var(--danger); color: white; font-size: 1.15rem; font-weight: 800; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('driver.stop.view', $leg->leg_id) }}" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
            Back
        </a>
        <div style="font-weight: 800; font-size: 1.05rem; color: var(--amber);">
            Dock Blocker: {{ $leg->to_outlet }}
        </div>
        <span style="background: rgba(245,158,11,0.2); color: var(--amber); padding: 4px 8px; border-radius: 6px; font-weight: 800; font-size: 0.75rem;">DRV-05</span>
    </header>

    <div class="container">
        <form action="{{ route('driver.exception') }}" method="POST">
            @csrf
            <input type="hidden" name="leg_id" value="{{ $leg->leg_id }}">

            <div class="card">
                <div class="card-label">Obstacle Category Reason</div>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label class="radio-box">
                        <input type="radio" name="reason" value="Alley Blocked by 3rd-Party Vehicle" checked>
                        <span>Alley Blocked by 3rd-Party Vehicle</span>
                    </label>
                    <label class="radio-box">
                        <input type="radio" name="reason" value="Store Receiving Staff Absent / No Answer">
                        <span>Store Receiving Staff Absent / No Answer</span>
                    </label>
                    <label class="radio-box">
                        <input type="radio" name="reason" value="Canopy Gate Locked & Guard Absent">
                        <span>Canopy Gate Locked & Guard Absent</span>
                    </label>
                    <label class="radio-box">
                        <input type="radio" name="reason" value="Store Power Outage / Cold Room Full">
                        <span>Store Power Outage / Cold Room Full</span>
                    </label>
                </div>
            </div>

            <div class="card" style="margin-top: 16px;">
                <div class="card-label">Photo Proof Attachment</div>
                <div style="border: 2px dashed var(--border); border-radius: 12px; padding: 20px; text-align: center; color: var(--text-muted); cursor: pointer;" onclick="alert('Photo evidence attached to exception log.')">
                    <span class="material-symbols-outlined" style="font-size: 36px; color: var(--amber);">photo_camera</span>
                    <div style="font-weight: 700; color: #fff; margin-top: 6px;">Tap to Capture Photo Proof</div>
                    <div style="font-size: 0.75rem;">Protects driver from detention penalties</div>
                </div>
            </div>

            <div class="bottom-action" style="margin-top: 24px;">
                <button type="submit" class="btn-abort">
                    <span class="material-symbols-outlined">cancel</span>
                    ABORT STOP & NOTIFY DISPATCH (64px)
                </button>
            </div>
        </form>
    </div>
</body>
</html>
