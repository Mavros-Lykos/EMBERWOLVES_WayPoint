<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deferral Governance Audit — Waypoint Dispatch</title>
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
        .modal-card { width: 100%; max-width: 680px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #0b1120; }
        .modal-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }
        .policy-card { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 12px; padding: 16px; display: flex; gap: 12px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-label { font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em; }
        .form-select, .form-input { height: 48px; background: #0b1120; border: 1px solid var(--border); border-radius: 8px; color: var(--text-main); padding: 0 16px; font-size: 0.95rem; }
        .form-textarea { background: #0b1120; border: 1px solid var(--border); border-radius: 8px; color: var(--text-main); padding: 12px 16px; font-size: 0.95rem; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; background: #0b1120; }
        .btn-cancel { padding: 0 20px; height: 48px; border-radius: 8px; background: transparent; border: 1px solid var(--border); color: var(--text-muted); cursor: pointer; text-decoration: none; display: flex; align-items: center; }
        .btn-confirm { padding: 0 24px; height: 48px; border-radius: 8px; background: var(--danger); color: white; border: none; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>
    <div class="modal-card">
        <div class="modal-header">
            <div>
                <h1 style="font-size: 1.2rem; font-weight: 700; color: #fca5a5;">Order Deferral Governance: {{ $order->order_ref }}</h1>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Outlet: {{ $order->outlet_id }} | Category: {{ ucfirst($order->temp_requirement) }}</div>
            </div>
            <span style="background: rgba(239,68,68,0.2); color: #f87171; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">DISP-04</span>
        </div>

        <form action="{{ route('dispatch.defer') }}" method="POST">
            @csrf
            <input type="hidden" name="order_ref" value="{{ $order->order_ref }}">

            <div class="modal-body">
                <div class="policy-card">
                    <span class="material-symbols-outlined" style="color: var(--danger); font-size: 28px;">gavel</span>
                    <div>
                        <div style="font-weight: 700; color: #fca5a5; font-size: 0.95rem;">Service Equity Rule Enforcement</div>
                        <div style="font-size: 0.85rem; color: #e2e8f0; margin-top: 4px;">
                            Consecutive Days Deferred: <strong>{{ $order->days_since_last_served ?? 0 }} Days</strong>.<br>
                            Waypoint policy strictly prohibits rolling the same outlet 2 days consecutively without Senior Logistics pass.
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="reason_code">Mandatory Reason Taxonomy Code</label>
                    <select id="reason_code" name="reason_code" class="form-select" required>
                        <option value="CAPACITY_EXCEEDED">Exceeded Physical Vehicle Cubic/Weight Limits</option>
                        <option value="NO_REEFER_CAPACITY">Refrigerated Fleet 100% Saturated</option>
                        <option value="NO_REEFER_VAN">Van-Only Constraint & No Reefer Van Available</option>
                        <option value="DEPOT_STOCK_SHORTFALL">Depot Central WMS Stock Depleted</option>
                        <option value="FUEL_QUOTA_EXHAUSTED">Weekly Vehicle Fuel Km Quota Saturated</option>
                        <option value="MALL_WINDOW_EXPIRED">Mall Delivery Access Window Closed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="justification">Operational Justification / Resolution Action</label>
                    <textarea id="justification" name="justification" rows="3" class="form-textarea" placeholder="Explain capacity trade-off and confirm P1 priority slot for tomorrow's run..." required></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <a href="{{ route('dispatch.overview') }}" class="btn-cancel">Cancel (Keep on Manifest)</a>
                <button type="submit" class="btn-confirm">
                    <span class="material-symbols-outlined">schedule</span>
                    Roll Order to Tomorrow (P1 Priority)
                </button>
            </div>
        </form>
    </div>
</body>
</html>
