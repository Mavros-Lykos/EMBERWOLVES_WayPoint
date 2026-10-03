<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Log Delivery Dispute') }} — Waypoint Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        :root {
            --bg-page: #f8fafc;
            --surface: #ffffff;
            --primary: #0284c7;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #dc2626;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
        header { background: var(--surface); border-bottom: 1px solid var(--border); padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: var(--text-muted); text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); }
        .container { max-width: 800px; margin: 32px auto; padding: 0 20px; width: 100%; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        .form-label { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }
        .form-control { border: 1.5px solid var(--border); border-radius: 10px; padding: 12px 16px; font-size: 1rem; }
        .radio-options { display: flex; flex-direction: column; gap: 10px; }
        .radio-box { border: 1.5px solid var(--border); border-radius: 10px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; }
        .radio-box input { accent-color: var(--danger); width: 18px; height: 18px; }
        .photo-uploader { border: 2px dashed #cbd5e1; border-radius: 12px; padding: 24px; text-align: center; color: var(--text-muted); cursor: pointer; background: #f8fafc; }
        .btn-submit { width: 100%; height: 60px; background: var(--danger); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('store.dashboard') }}" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Dashboard
        </a>
        <div style="font-weight: 700;">Delivery Discrepancy Gate</div>
        <span style="background: #fee2e2; color: #dc2626; padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">STORE-06</span>
    </header>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div>
                    <h1 style="font-size: 1.4rem; font-weight: 800; color: var(--danger);">Record Delivery Exception: {{ $order->order_ref }}</h1>
                    <div style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">Generates immediate credit note and alerts central dispatch.</div>
                </div>
            </div>

            <form action="{{ route('store.dispute') }}" method="POST">
                @csrf
                <input type="hidden" name="order_ref" value="{{ $order->order_ref }}">

                <div class="form-group">
                    <label class="form-label">Discrepancy Category</label>
                    <div class="radio-options">
                        <label class="radio-box">
                            <input type="radio" name="dispute_type" value="Shortage at Dock" checked>
                            <span>Short-shipped at Dock (Fewer crates received than on manifest)</span>
                        </label>
                        <label class="radio-box">
                            <input type="radio" name="dispute_type" value="Damaged in Transit">
                            <span>Damaged / Crushed Cartons during vehicle transit</span>
                        </label>
                        <label class="radio-box">
                            <input type="radio" name="dispute_type" value="Temperature Abuse">
                            <span>Temperature Abuse / Perishable Spoilage (&gt; 4.0°C)</span>
                        </label>
                        <label class="radio-box">
                            <input type="radio" name="dispute_type" value="Tampered Seal">
                            <span>Tampered / Mismatched Door Bolt Seal</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Quantity Affected (Crates)</label>
                    <input type="number" name="affected_units" class="form-control" value="2" min="1" max="{{ $order->order_units }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Specific Details / Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Describe the physical condition of the crates or temperature readings..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Photo Evidence Attachment</label>
                    <div class="photo-uploader" onclick="alert('Camera activated: Photo captured and attached to credit note.')">
                        <span class="material-symbols-outlined" style="font-size: 36px; color: var(--danger);">photo_camera</span>
                        <div style="font-weight: 600; color: var(--text-main); margin-top: 6px;">Tap to Take Photo of Damaged Items</div>
                        <div style="font-size: 0.8rem;">Required for ERP credit note clearance</div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="material-symbols-outlined">gavel</span>
                    Submit Exception & Issue Credit Note
                </button>
            </form>
        </div>
    </div>
</body>
</html>
