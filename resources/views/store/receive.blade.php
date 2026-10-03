<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Dock Receiving & Seal Verification') }} — Waypoint Store</title>
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
            --success: #16a34a;
            --amber: #d97706;
            --danger: #dc2626;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
        header { background: var(--surface); border-bottom: 1px solid var(--border); padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: var(--text-muted); text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); }
        .container { max-width: 800px; margin: 32px auto; padding: 0 20px; width: 100%; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 16px; }
        .step-section { background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .step-title { font-size: 1rem; font-weight: 800; color: var(--text-main); display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
        .seal-input-group { display: flex; align-items: center; gap: 12px; }
        .seal-input { width: 180px; height: 52px; font-size: 1.25rem; font-weight: 700; text-align: center; border: 2px solid var(--border); border-radius: 8px; font-family: monospace; letter-spacing: 2px; }
        .seal-input:focus { border-color: var(--primary); outline: none; }
        .action-row { display: flex; gap: 16px; margin-top: 24px; }
        .btn-confirm { flex: 1; height: 64px; background: var(--success); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-dispute { height: 64px; padding: 0 24px; background: #fff1f2; color: var(--danger); border: 2px solid #fecdd3; border-radius: 12px; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('store.dashboard') }}" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Dashboard
        </a>
        <div style="font-weight: 700;">Dock Custody Transfer Gate</div>
        <span style="background: #e0f2fe; color: #0284c7; padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">STORE-05</span>
    </header>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div>
                    <h1 style="font-size: 1.4rem; font-weight: 800;">Receive Shipment: {{ $order->order_ref }}</h1>
                    <div style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">Vehicle: {{ $trip->vehicle_id ?? 'VEH-003' }} | Driver Delivery Arrival</div>
                </div>
                <span style="background: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.85rem;">Dock Gate Active</span>
            </div>

            <form action="{{ route('store.accept') }}" method="POST">
                @csrf
                <input type="hidden" name="order_ref" value="{{ $order->order_ref }}">
                <input type="hidden" name="leg_id" value="{{ $leg->leg_id ?? '' }}">

                <!-- STEP 1: SEAL CHECK -->
                <div class="step-section">
                    <div class="step-title">
                        <span class="material-symbols-outlined" style="color: var(--primary);">verified_user</span>
                        Step 1: Tamper-Evident Bolt Seal Verification
                    </div>
                    <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 12px;">
                        Inspect the mechanical bolt seal on the rear container door before allowing the driver to cut it.
                    </p>
                    <div class="seal-input-group">
                        <span style="font-weight: 700; font-size: 1.1rem; color: var(--text-muted);">SL -</span>
                        <input type="text" name="seal_number" class="seal-input" placeholder="9942" required maxlength="8">
                        <span style="font-size: 0.85rem; color: var(--text-muted);">Depot Recorded Seal: <strong>SL-9942</strong></span>
                    </div>
                </div>

                <!-- STEP 2: TEMPERATURE PROBE -->
                <div class="step-section">
                    <div class="step-title">
                        <span class="material-symbols-outlined" style="color: #0284c7;">device_thermostat</span>
                        Step 2: Core Cargo Temperature Probe Reading
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <input type="number" step="0.1" name="probe_temp" class="seal-input" value="3.2" style="width: 120px;" required>
                        <span style="font-size: 1.1rem; font-weight: 700;">°C</span>
                        <span style="color: var(--success); font-weight: 600; font-size: 0.875rem;">
                            <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">check_circle</span>
                            Cold-Chain In Compliance (0.0°C to 4.0°C)
                        </span>
                    </div>
                </div>

                <!-- STEP 3: PHYSICAL COUNT AUDIT -->
                <div class="step-section">
                    <div class="step-title">
                        <span class="material-symbols-outlined" style="color: #ca8a04;">inventory</span>
                        Step 3: Manifest Crate Count
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 700;">{{ $order->order_units }} Crates Expected ({{ ucfirst($order->temp_requirement) }})</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">Total Order Weight: {{ $order->order_weight_kg }} kg</div>
                        </div>
                        <input type="number" name="confirmed_units" value="{{ $order->order_units }}" class="seal-input" style="width: 100px;">
                    </div>
                </div>

                <div class="action-row">
                    <a href="{{ route('store.dispute.view', $order->order_ref) }}" class="btn-dispute">
                        <span class="material-symbols-outlined">report_problem</span>
                        Report Dispute / Shortage
                    </a>
                    <button type="submit" class="btn-confirm">
                        <span class="material-symbols-outlined">how_to_reg</span>
                        Verify Seal & Sign Receipt (64px)
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
