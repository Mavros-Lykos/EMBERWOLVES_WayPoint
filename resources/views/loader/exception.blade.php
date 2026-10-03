<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flag Loading Exception — Waypoint Depot</title>
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
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; padding: 20px; display: flex; align-items: center; justify-content: center; }
        .modal-card { width: 100%; max-width: 680px; background: var(--surface); border: 2px solid var(--border); border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.7); }
        .modal-header { padding: 20px 24px; border-bottom: 2px solid var(--border); background: #020617; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-label { font-size: 0.85rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .form-control { height: 56px; background: #020617; border: 2px solid var(--border); border-radius: 12px; color: #fff; padding: 0 16px; font-size: 1.1rem; font-weight: 600; }
        .btn-submit { height: 68px; background: var(--danger); color: white; border: none; border-radius: 16px; font-size: 1.15rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; }
        .btn-cancel { height: 56px; border: 2px solid var(--border); border-radius: 12px; background: transparent; color: var(--text-muted); font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; text-decoration: none; }
    </style>
</head>
<body>
    <div class="modal-card">
        <div class="modal-header">
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 800; color: #fca5a5; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="color: var(--danger);">warning</span>
                    Record Dock Shortage / Damage: {{ $trip->trip_id }}
                </h1>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Bay 04 Loading Dock | Vehicle: {{ $trip->vehicle_id }}</div>
            </div>
            <span style="background: rgba(239,68,68,0.2); color: #fca5a5; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem;">LOAD-04</span>
        </div>

        <form action="{{ route('loader.exception') }}" method="POST">
            @csrf
            <input type="hidden" name="trip_id" value="{{ $trip->trip_id }}">

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="order_ref">Select Affected Order</label>
                    <select id="order_ref" name="order_ref" class="form-control" required>
                        @foreach($orders as $o)
                            <option value="{{ $o->order_ref }}">{{ $o->order_ref }} — {{ $o->outlet_id }} ({{ $o->order_units }} Crates)</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="quantity">Actual Crates Loaded Onboard</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" value="36" min="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="reason">Discrepancy Reason</label>
                    <select id="reason" name="reason" class="form-control" required>
                        <option value="Damaged by Forklift at Bay">Damaged by Forklift at Loading Bay</option>
                        <option value="Central Cold Room Stock Depleted">Central Cold Room Stock Depleted</option>
                        <option value="Container Leakage / Packaging Crushed">Packaging Leaking / Crushed Crate</option>
                        <option value="Wrong Item Staged at Staging Line">Wrong SKU Staged on Pallet</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="material-symbols-outlined">send</span>
                    CONFIRM SHORTFALL & RECALCULATE (64px)
                </button>
                <a href="{{ route('loader.load.view', $trip->trip_id) }}" class="btn-cancel">Cancel and Return to Checklist</a>
            </div>
        </form>
    </div>
</body>
</html>
