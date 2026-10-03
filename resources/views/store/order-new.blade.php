<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('New Order Placement') }} — Waypoint Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        :root {
            --bg-page: #f8fafc;
            --surface: #ffffff;
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
            --success: #10b981;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
        header { background: var(--surface); border-bottom: 1px solid var(--border); padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; }
        .brand { display: flex; align-items: center; gap: 12px; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: var(--text-muted); text-decoration: none; font-weight: 600; font-size: 0.9rem; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); transition: all 0.2s; }
        .back-link:hover { background: #f1f5f9; color: var(--text-main); }
        .container { max-width: 1000px; margin: 32px auto; padding: 0 20px; width: 100%; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .card-header { margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 16px; display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 1.5rem; font-weight: 800; color: var(--text-main); }
        .cutoff-badge { background: #fee2e2; color: #b91c1c; font-weight: 700; font-size: 0.85rem; padding: 6px 14px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-label { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .form-control { height: 48px; border: 1.5px solid var(--border); border-radius: 10px; padding: 0 16px; font-size: 1rem; color: var(--text-main); transition: border-color 0.2s; background: var(--surface); }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(2,132,199,0.15); }
        .radio-pills { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .radio-pill { border: 2px solid var(--border); border-radius: 12px; padding: 14px; cursor: pointer; text-align: center; font-weight: 700; font-size: 0.9rem; transition: all 0.2s; display: flex; flex-direction: column; align-items: center; gap: 6px; }
        .radio-pill input { display: none; }
        .radio-pill:has(input:checked) { border-color: var(--primary); background: #f0f9ff; color: var(--primary); }
        .summary-box { background: #f8fafc; border: 1.5px dashed var(--border); border-radius: 12px; padding: 20px; margin-bottom: 24px; }
        .summary-title { font-size: 0.9rem; font-weight: 700; color: var(--text-muted); margin-bottom: 12px; text-transform: uppercase; }
        .summary-row { display: flex; justify-content: space-between; font-size: 0.95rem; margin-bottom: 6px; }
        .submit-btn { width: 100%; height: 56px; background: var(--primary); color: #ffffff; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; }
        .submit-btn:hover { background: var(--primary-dark); }
        .alert { padding: 16px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <a href="{{ route('store.dashboard') }}" class="back-link">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Dashboard
            </a>
            <span style="font-weight: 700; font-size: 1.1rem; margin-left: 12px;">{{ $outlet->brand ?? 'Store' }} — {{ $outlet->outlet_id ?? $user->outlet_id }}</span>
        </div>
        <div class="cutoff-badge">
            <span class="material-symbols-outlined" style="font-size: 18px;">schedule</span>
            16:00 Daily Cutoff (Next-Day Run)
        </div>
    </header>

    <div class="container">
        @if(session('error'))
            <div class="alert alert-error">
                <span class="material-symbols-outlined">error</span>
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div>
                    <h1 class="card-title">Place Next-Day Replenishment Order</h1>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">Orders placed prior to 16:00 will be sequenced for 04:00 AM DC departure.</p>
                </div>
                <span style="font-size: 0.8rem; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-weight: 700;">STORE-02</span>
            </div>

            <form action="{{ route('store.order') }}" method="POST" id="orderForm">
                @csrf
                <input type="hidden" name="outlet_id" value="{{ $user->outlet_id }}">

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">1. Temperature Zone Requirement</label>
                    <div class="radio-pills">
                        <label class="radio-pill">
                            <input type="radio" name="temp_requirement" value="ambient" checked onchange="calcMetrics()">
                            <span class="material-symbols-outlined" style="color: #ca8a04;">inventory_2</span>
                            <span>Ambient Dry</span>
                        </label>
                        <label class="radio-pill">
                            <input type="radio" name="temp_requirement" value="chilled" onchange="calcMetrics()">
                            <span class="material-symbols-outlined" style="color: #0284c7;">ac_unit</span>
                            <span>Chilled (0-4°C)</span>
                        </label>
                        <label class="radio-pill">
                            <input type="radio" name="temp_requirement" value="frozen" onchange="calcMetrics()">
                            <span class="material-symbols-outlined" style="color: #4f46e5;">severe_cold</span>
                            <span>Frozen Perishable</span>
                        </label>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="order_units">2. Order Units (Standard Crates)</label>
                        <input type="number" id="order_units" name="order_units" class="form-control" min="1" max="500" value="40" required oninput="calcMetrics()">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="delivery_date">3. Required Delivery Date</label>
                        <input type="date" id="delivery_date" class="form-control" value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" readonly>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600;">
                        <input type="checkbox" name="urgency" value="1" style="width: 18px; height: 18px; accent-color: var(--primary);">
                        <span>Flag as Critical Urgency (Store Stockout Emergency)</span>
                    </label>
                </div>

                <div class="summary-box">
                    <div class="summary-title">Calculated Logistics Payload</div>
                    <div class="summary-row">
                        <span>Total Est. Weight:</span>
                        <strong id="weightVal">96.0 kg</strong>
                    </div>
                    <div class="summary-row">
                        <span>Total Est. Cubic Volume:</span>
                        <strong id="volVal">1.40 m³</strong>
                    </div>
                    <div class="summary-row">
                        <span>Vehicle Class Required:</span>
                        <strong id="vehClass">Ambient Truck / Van</strong>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <span class="material-symbols-outlined">send</span>
                    Confirm & Submit Order to Dispatch
                </button>
            </form>
        </div>
    </div>

    <script>
        function calcMetrics() {
            const units = parseInt(document.getElementById('order_units').value) || 0;
            const weight = (units * 2.4).toFixed(1);
            const volume = (units * 0.035).toFixed(2);
            document.getElementById('weightVal').innerText = weight + ' kg';
            document.getElementById('volVal').innerText = volume + ' m³';
            
            const temp = document.querySelector('input[name="temp_requirement"]:checked')?.value || 'ambient';
            if (temp === 'chilled' || temp === 'frozen') {
                document.getElementById('vehClass').innerText = 'Refrigerated Fleet (Reefer Truck / Reefer Van)';
                document.getElementById('vehClass').style.color = '#0284c7';
            } else {
                document.getElementById('vehClass').innerText = 'Standard Dry Box Truck / Van';
                document.getElementById('vehClass').style.color = '#0f172a';
            }
        }
        calcMetrics();
    </script>
</body>
</html>
