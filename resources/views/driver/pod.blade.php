<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Proof of Delivery (PoD) — Waypoint Driver</title>
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
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; max-width: 440px; margin: 0 auto; display: flex; flex-direction: column; }
        header { height: 68px; padding: 0 16px; background: var(--surface); border-bottom: 2px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .back-btn { height: 48px; padding: 0 14px; border-radius: 10px; background: #1e293b; color: var(--text-main); text-decoration: none; display: flex; align-items: center; gap: 6px; font-weight: 700; font-size: 0.9rem; border: 1px solid var(--border); }
        .container { flex: 1; padding: 16px; display: flex; flex-direction: column; gap: 16px; overflow-y: auto; }
        .pod-card { background: var(--surface); border: 2px solid var(--border); border-radius: 16px; padding: 16px; display: flex; flex-direction: column; gap: 12px; }
        .card-label { font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 800; letter-spacing: 0.05em; }
        .sig-pad { height: 140px; background: #020617; border: 2px dashed var(--border); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.9rem; }
        .stamp-box { background: #020617; border: 1px solid var(--border); border-radius: 10px; padding: 10px 14px; display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); }
        .bottom-action { padding: 16px; border-top: 2px solid var(--border); background: var(--surface); }
        .btn-confirm-pod { height: 68px; border-radius: 16px; background: var(--success); color: #000; font-size: 1.15rem; font-weight: 800; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('driver.stop.view', $leg->leg_id) }}" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
            Stop Details
        </a>
        <div style="font-weight: 800; font-size: 1.05rem;">
            Digital PoD: {{ $leg->to_outlet }}
        </div>
        <span style="background: rgba(34,197,94,0.2); color: var(--success); padding: 4px 8px; border-radius: 6px; font-weight: 800; font-size: 0.75rem;">DRV-04</span>
    </header>

    <div class="container">
        <form id="podForm" action="{{ route('driver.confirm') }}" method="POST">
            @csrf
            <input type="hidden" name="trip_id" value="{{ $leg->trip_id }}">
            <input type="hidden" name="leg_id" value="{{ $leg->leg_id }}">
            <input type="hidden" name="confirmed_units" value="{{ $order->order_units ?? 40 }}">
            <input type="hidden" name="signature" value="SIGNATURE_CAPTURE_PRIYA_K">

            <div class="pod-card">
                <div class="card-label">Handover Custody Verification</div>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 0.95rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" checked style="width: 20px; height: 20px; accent-color: var(--success);">
                    Rear Bolt Seal Cut in Presence of Store Manager
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 0.95rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" checked style="width: 20px; height: 20px; accent-color: var(--success);">
                    All {{ $order->order_units ?? 40 }} Crates Transferred to Store Loading Dock
                </label>
            </div>

            <div class="pod-card" style="margin-top: 16px;">
                <div class="card-label">Store Manager Digital Signature</div>
                <div class="sig-pad">
                    <span style="font-style: italic; font-weight: 700; color: #fff; font-size: 1.2rem;">Priya Kulatunga (SM-0412)</span>
                </div>
                <div style="font-size: 0.8rem; color: var(--text-muted); text-align: center;">Signed on Touch Screen</div>
            </div>

            <div class="stamp-box" style="margin-top: 16px;">
                <span>GPS Auto Stamp: 6.8341° N, 79.8652° E</span>
                <span style="color: var(--success); font-weight: 700;">Verified Offline [✓]</span>
            </div>

            <div class="bottom-action" style="margin-top: 24px;">
                <button type="submit" class="btn-confirm-pod">
                    <span class="material-symbols-outlined">save</span>
                    CONFIRM PoD & CLOSE STOP (64px)
                </button>
            </div>
        </form>
    </div>
</body>
</html>
