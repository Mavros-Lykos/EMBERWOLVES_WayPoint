<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --surf: #f8fafc;
            --surf-c: #ffffff;
            --on-surf: #0f172a;
            --outline: #e2e8f0;
            --success: #10b981;
            --warn: #f59e0b;
            --crit: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surf);
            color: var(--on-surf);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .top-bar {
            background: var(--surf-c);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--outline);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--on-surf);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .page-title:hover {
            color: var(--primary);
        }

        .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 24px;
        }

        .card {
            background: var(--surf-c);
            border-radius: 16px;
            border: 1px solid var(--outline);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 32px;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--outline);
            background: #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--outline);
        }

        th {
            background: #f8fafc;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover td {
            background: #f8fafc;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-allocated { background: #e0f2fe; color: #0369a1; }
        .badge-delivered { background: #dcfce3; color: #166534; }
        .badge-deferred { background: #fee2e2; color: #b91c1c; }

        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid var(--outline);
            background: var(--surf-c);
            color: var(--on-surf);
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #f1f5f9;
        }

        .text-sub {
            color: #64748b;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <header class="top-bar">
        <a href="{{ route('store.dashboard') }}" class="page-title">
            <span class="material-symbols-outlined">arrow_back</span>
            {{ __('Order History & Deferrals') }}
        </a>
        <div style="display:flex; align-items:center; gap:16px;">
            <div style="font-size:14px; font-weight:500;">{{ auth()->user()->outlet_id }}</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background:none; border:none; color:#64748b; cursor:pointer; font-weight:600">Exit</button>
            </form>
        </div>
    </header>

    <div class="container">
        
        <!-- DEFERRAL AUDIT LOG -->
        <div class="card" style="border-color:#fca5a5; box-shadow:0 8px 16px rgba(239,68,68,0.1);">
            <div class="card-header" style="background:#fef2f2;">
                <div class="card-title" style="color:#b91c1c;">
                    <span class="material-symbols-outlined">gavel</span>
                    {{ __('Critical Deferral Audit Trail') }}
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Order ID') }}</th>
                            <th>{{ __('Target Date') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Resolution / Reason') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deferrals as $def)
                        <tr>
                            <td style="font-weight:600;">{{ $def->order_ref }}</td>
                            <td>{{ $def->order_date }}</td>
                            <td>{{ ucfirst($def->temp_requirement) }} <span class="text-sub">({{ $def->order_units }} units)</span></td>
                            <td><span class="badge badge-deferred">{{ __('DEFERRED') }}</span></td>
                            <td style="color:#b91c1c; font-weight:500;">⚠️ {{ $def->reason_code }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:32px; color:#64748b;">
                                {{ __('No deferrals recorded on your account.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FULL ORDER HISTORY -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span class="material-symbols-outlined">history</span>
                    {{ __('Complete Order History') }}
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Order ID') }}</th>
                            <th>{{ __('Placed On') }}</th>
                            <th>{{ __('Target Date') }}</th>
                            <th>{{ __('Items / Weight') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td style="font-weight:600;">{{ $order->order_ref }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->placed_at)->format('M d, H:i') }}</td>
                            <td>{{ $order->order_date }}</td>
                            <td>
                                <div>{{ $order->order_units }} {{ __('units') }}</div>
                                <div class="text-sub">{{ number_format($order->order_weight_kg) }} kg | {{ $order->temp_requirement }}</div>
                            </td>
                            <td>
                                @php
                                    $bClass = 'badge-pending';
                                    if($order->status == 'allocated') $bClass = 'badge-allocated';
                                    if($order->status == 'delivered') $bClass = 'badge-delivered';
                                    if($order->status == 'deferred') $bClass = 'badge-deferred';
                                @endphp
                                <span class="badge {{ $bClass }}">{{ strtoupper($order->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('store.order.track', $order->order_ref) }}" class="btn">
                                    <span class="material-symbols-outlined" style="font-size:18px">visibility</span>
                                    {{ __('Track') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:32px; color:#64748b;">
                                {{ __('No orders found.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
