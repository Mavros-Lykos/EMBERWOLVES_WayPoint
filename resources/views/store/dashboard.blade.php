<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Dashboard — Waypoint Fresh</title>
    <meta name="theme-color" content="#1565C0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Roboto', sans-serif; display: flex; min-height: 100vh; margin: 0; background: #F3F3F6; color: #1B1B1F; }
        /* Nav Rail */
        .nav-rail { width: 80px; background: #EDEDF0; display: flex; flex-direction: column; align-items: center; padding: 12px 0; gap: 4px; border-right: 1px solid rgba(116,119,127,0.2); flex-shrink: 0; }
        .brand-mark { width: 48px; height: 48px; background: #1565C0; color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-weight: 700; font-size: 20px; }
        .nav-item { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 4px 0; width: 100%; cursor: pointer; color: #74777F; text-decoration: none; border: none; background: none; font-size: 11px; }
        .nav-item:hover { color: #1B1B1F; }
        .nav-item.active .icon-wrap { background: #C2D3F0; color: #001849; border-radius: 999px; padding: 4px 20px; }
        .icon-wrap { display: flex; align-items: center; justify-content: center; padding: 4px 20px; }
        /* Top Bar */
        .main-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .top-bar { height: 64px; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(116,119,127,0.2); background: #FAFAFA; }
        .page-title { font-size: 22px; font-weight: 500; }
        .meta { display: flex; align-items: center; gap: 16px; }
        .cutoff-chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; background: #C2D3F0; color: #001849; border-radius: 999px; font-size: 14px; font-weight: 500; }
        .cutoff-chip.urgent { background: #FFDAD6; color: #93000A; }
        /* Page Body */
        .page-body { flex: 1; overflow-y: auto; padding: 24px; display: grid; grid-template-columns: 1fr 340px; gap: 24px; }
        .section-label { font-size: 11px; font-weight: 600; color: #74777F; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        /* Delivery Card */
        .delivery-card { background: #FAFAFA; border: 1px solid rgba(116,119,127,0.2); border-radius: 12px; padding: 20px; }
        .eta-row { display: flex; align-items: baseline; gap: 8px; margin-bottom: 16px; }
        .eta-big { font-size: 45px; font-weight: 400; color: #1565C0; line-height: 1; }
        .eta-label { font-size: 14px; color: #74777F; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .detail-key { font-size: 10px; font-weight: 600; color: #74777F; text-transform: uppercase; letter-spacing: 0.06em; }
        .detail-val { font-size: 14px; color: #1B1B1F; margin-top: 2px; }
        .delivery-actions { display: flex; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(116,119,127,0.2); }
        /* Orders Table */
        .orders-section { margin-top: 24px; }
        .orders-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .orders-table th { text-align: left; font-size: 11px; font-weight: 600; color: #74777F; text-transform: uppercase; letter-spacing: 0.06em; padding: 12px 16px; border-bottom: 1px solid rgba(116,119,127,0.2); }
        .orders-table td { padding: 12px 16px; border-bottom: 1px solid rgba(116,119,127,0.2); }
        .orders-table tr:hover td { background: #F3F3F6; }
        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 500; }
        .badge-success { background: #C8E6C9; color: #1B5E20; }
        .badge-warning { background: #FFF3E0; color: #E65100; }
        .badge-error { background: #FFDAD6; color: #93000A; }
        .badge-info { background: #C2D3F0; color: #001849; }
        /* Sidebar */
        .sidebar { display: flex; flex-direction: column; gap: 20px; }
        .card { background: #FAFAFA; border: 1px solid rgba(116,119,127,0.2); border-radius: 12px; padding: 20px; }
        .card-title { font-size: 16px; font-weight: 500; margin-bottom: 12px; }
        .stat-chips { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
        .stat-chip { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; background: #EDEDF0; border-radius: 999px; font-size: 12px; color: #74777F; }
        .metric-big { font-size: 45px; font-weight: 400; color: #1B1B1F; line-height: 1; }
        .metric-sub { font-size: 12px; color: #74777F; margin-top: 4px; }
        /* Buttons */
        .btn-filled { display: inline-flex; align-items: center; gap: 8px; background: #1565C0; color: #fff; border: none; border-radius: 999px; padding: 10px 24px; font-size: 14px; font-weight: 500; cursor: pointer; transition: box-shadow 0.2s; }
        .btn-filled:hover { box-shadow: 0 2px 8px rgba(21,101,192,0.4); }
        .btn-outlined { display: inline-flex; align-items: center; gap: 8px; background: transparent; color: #1565C0; border: 1px solid rgba(21,101,192,0.4); border-radius: 999px; padding: 10px 24px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .btn-outlined:hover { background: rgba(21,101,192,0.08); }
        /* Alert Banner */
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: #C8E6C9; color: #1B5E20; }
        .alert-warning { background: #FFF3E0; color: #E65100; }
        .alert-error { background: #FFDAD6; color: #93000A; }
        /* Deferral Banner */
        .deferral-banner { background: #FFF3E0; border: 1px solid #E65100; border-radius: 12px; padding: 16px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 12px; }
        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: flex-end; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal-sheet { background: #FAFAFA; border-radius: 20px 20px 0 0; padding: 24px; width: 100%; max-width: 560px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-title { font-size: 20px; font-weight: 500; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #74777F; }
        label.field-label { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #74777F; margin-bottom: 6px; }
        .field-input { width: 100%; border: 1px solid rgba(116,119,127,0.4); border-radius: 8px; padding: 12px; font-size: 14px; outline: none; background: #F3F3F6; box-sizing: border-box; }
        .field-input:focus { border-color: #1565C0; box-shadow: 0 0 0 2px rgba(21,101,192,0.15); }
        .bottom-nav { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: #EDEDF0; border-top: 1px solid rgba(116,119,127,0.2); z-index: 40; justify-content: space-around; padding: 8px 0; padding-bottom: max(8px, env(safe-area-inset-bottom)); }
        .bottom-nav .nav-item { width: auto; padding: 4px 16px; }
        .bottom-nav .icon-wrap { background: transparent; padding: 4px 12px; }
        .bottom-nav .nav-item.active .icon-wrap { background: #C2D3F0; }
        @media (max-width: 1024px) { .nav-rail { display: none; } .bottom-nav { display: flex; } .main-content { padding-bottom: 70px; } .page-body { grid-template-columns: 1fr; } }
        @media (max-width: 640px) { .top-bar { padding: 0 16px; } .page-body { padding: 16px; } }
    </style>
</head>
<body x-data="storeApp()" @keydown.escape.window="closeAll()">

    <!-- Navigation Rail -->
    <nav class="nav-rail" aria-label="Main navigation">
        <div class="brand-mark">W</div>
        <a href="{{ route('store.dashboard') }}" class="nav-item active" aria-current="page">
            <span class="icon-wrap"><span class="material-symbols-outlined">dashboard</span></span>
            <span>Home</span>
        </a>
        <button class="nav-item" @click="showOrderModal = true" aria-label="Place order">
            <span class="icon-wrap"><span class="material-symbols-outlined">add_shopping_cart</span></span>
            <span>Order</span>
        </button>
        <a href="{{ route('store.history') }}" class="nav-item" aria-label="History">
            <span class="icon-wrap"><span class="material-symbols-outlined">history</span></span>
            <span>History</span>
        </a>
        <form action="{{ route('logout') }}" method="POST" style="width:100%">
            @csrf
            <button type="submit" class="nav-item" aria-label="Sign out">
                <span class="icon-wrap"><span class="material-symbols-outlined">logout</span></span>
                <span>Exit</span>
            </button>
        </form>
    </nav>

    <!-- Bottom Navigation (Mobile/Tablet) -->
    <nav class="bottom-nav" aria-label="Mobile navigation">
        <a href="{{ route('store.dashboard') }}" class="nav-item active">
            <span class="icon-wrap"><span class="material-symbols-outlined">dashboard</span></span>
            <span>Home</span>
        </a>
        <button class="nav-item" @click="showOrderModal = true">
            <span class="icon-wrap"><span class="material-symbols-outlined">add_shopping_cart</span></span>
            <span>Order</span>
        </button>
        <a href="{{ route('store.history') }}" class="nav-item">
            <span class="icon-wrap"><span class="material-symbols-outlined">history</span></span>
            <span>History</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-item">
                <span class="icon-wrap"><span class="material-symbols-outlined">logout</span></span>
                <span>Exit</span>
            </button>
        </form>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <header class="top-bar">
            <span class="page-title">{{ $user->outlet_id }} · {{ __('Waypoint Fresh') }}</span>
            <div class="meta">
                <div class="cutoff-chip {{ $cutoffPassed ? 'urgent' : '' }}">
                    <span class="material-symbols-outlined" style="font-size:18px">schedule</span>
                    @if($cutoffPassed)
                        {{ __('Cutoff: CLOSED') }}
                    @else
                        {{ __('Cutoff:') }} {{ floor($minutesToCutoff/60) }}h {{ $minutesToCutoff % 60 }}m
                    @endif
                </div>
                <!-- Language Switcher -->
                <div style="display:flex; gap: 8px;">
                    <a href="{{ route('locale.set', 'en') }}" style="font-size: 12px; text-decoration: none; color: {{ session('locale') == 'en' || !session('locale') ? '#1565C0; font-weight: bold;' : '#74777F;' }}">EN</a>
                    <a href="{{ route('locale.set', 'si') }}" style="font-size: 12px; text-decoration: none; color: {{ session('locale') == 'si' ? '#1565C0; font-weight: bold;' : '#74777F;' }}">සිං</a>
                    <a href="{{ route('locale.set', 'ta') }}" style="font-size: 12px; text-decoration: none; color: {{ session('locale') == 'ta' ? '#1565C0; font-weight: bold;' : '#74777F;' }}">தமிழ்</a>
                </div>
                <span style="font-size:14px; color:#74777F">{{ $user->name }}</span>
            </div>
        </header>

        <div class="page-body">
            <!-- Left Column -->
            <div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning">
                        <span class="material-symbols-outlined" style="font-size:18px">warning</span>
                        {{ session('warning') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">
                        <span class="material-symbols-outlined" style="font-size:18px">error</span>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Deferral Notice -->
                @if($deferrals->count() > 0)
                <div class="deferral-banner">
                    <span class="material-symbols-outlined" style="color:#E65100">warning</span>
                    <div>
                        <strong>{{ __('Deferral notice:') }}</strong> {{ __('Your last') }} {{ $deferrals->count() }} {{ __('order(s) were deferred.') }}
                        {{ __('Most recent:') }} <strong>{{ $deferrals->first()->reason_code }}</strong> {{ __('on') }} {{ $deferrals->first()->deferred_date }}.
                    </div>
                </div>
                @endif

                <!-- Active Delivery Card -->
                <div class="section-label">{{ __("Today's delivery") }}</div>
                @if($activeOrder)
                <div class="delivery-card">
                    <div class="eta-row">
                        <span class="material-symbols-outlined" style="font-size:32px; color:var(--primary)">near_me</span>
                        <span class="eta-label" style="font-size:20px; font-weight:600; color:var(--on-surf);">{{ $etaMessage }}</span>
                    </div>
                    @if($activeOrder->status == 'in_transit')
                    <div style="background:rgba(21,101,192,0.1); padding:12px; border-radius:8px; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                        <span class="material-symbols-outlined" style="color:var(--primary)">group</span>
                        <span style="font-size:14px; color:var(--primary); font-weight:500;">Staff scheduling guidance: Have receiving staff ready at dock by {{ \Carbon\Carbon::parse('+25 minutes')->format('H:i') }}</span>
                    </div>
                    @endif
                    <div class="detail-grid">
                        <div>
                            <div class="detail-key">Order</div>
                            <div class="detail-val">{{ $activeOrder->order_ref }}</div>
                        </div>
                        <div>
                            <div class="detail-key">Type</div>
                            <div class="detail-val">{{ ucfirst($activeOrder->temp_requirement) }}</div>
                        </div>
                        <div>
                            <div class="detail-key">Units</div>
                            <div class="detail-val">{{ $activeOrder->order_units }}</div>
                        </div>
                        <div>
                            <div class="detail-key">Status</div>
                            <div class="detail-val">
                                <span class="badge badge-success">{{ ucfirst($activeOrder->status) }}</span>
                            </div>
                        </div>
                        @if($activeDriver && $activeVehicle)
                        <div style="grid-column: span 2;">
                            <div class="detail-key">Driver / Vehicle</div>
                            <div class="detail-val" style="display:flex; align-items:center; gap:8px;">
                                <span class="material-symbols-outlined" style="font-size:18px">person</span> {{ $activeDriver->name }} 
                                <span class="material-symbols-outlined" style="font-size:18px; margin-left:8px">local_shipping</span> {{ $activeVehicle->vehicle_id }}
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="delivery-actions">
                        <button class="btn-outlined" @click="showDisputeModal = true">
                            <span class="material-symbols-outlined" style="font-size:18px">report_problem</span>
                            Report Issue
                        </button>
                        <button class="btn-filled" @click="showAcceptModal = true">
                            <span class="material-symbols-outlined" style="font-size:18px">task_alt</span>
                            Confirm Receipt
                        </button>
                    </div>
                </div>
                @else
                <div class="delivery-card" style="text-align:center; padding: 40px 20px;">
                    <span class="material-symbols-outlined" style="font-size:48px; color:#74777F">local_shipping</span>
                    <p style="font-size:16px; font-weight:500; margin:12px 0 4px">No active delivery</p>
                    <p style="font-size:14px; color:#74777F; margin:0">Place an order before 16:00 cutoff</p>
                    <button class="btn-filled" style="margin-top:20px" @click="showOrderModal = true">
                        <span class="material-symbols-outlined" style="font-size:18px">add</span>
                        Place Order
                    </button>
                </div>
                @endif

                <!-- Service Metrics (SRS Requirement) -->
                <div class="section-label" style="margin-top:32px;">{{ __('Service quality (30 days)') }}</div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:24px;">
                    <div style="background:var(--surf); border:1px solid var(--outline); border-radius:12px; padding:16px; display:flex; flex-direction:column; gap:4px;">
                        <span style="color:#74777F; font-size:13px">{{ __('Avg. Arrival Time') }}</span>
                        <span style="font-size:24px; font-weight:600; color:var(--on-surf);">{{ $serviceMetrics['avg_arrival'] }}</span>
                    </div>
                    <div style="background:var(--surf); border:1px solid var(--outline); border-radius:12px; padding:16px; display:flex; flex-direction:column; gap:4px;">
                        <span style="color:#74777F; font-size:13px">{{ __('On-time Rate') }}</span>
                        <span style="font-size:24px; font-weight:600; color:var(--success);">
                            {{ number_format((($serviceMetrics['total'] - $serviceMetrics['late']) / max($serviceMetrics['total'], 1)) * 100, 0) }}%
                        </span>
                        <span style="font-size:12px; color:#74777F">{{ $serviceMetrics['late'] }} {{ __('late out of') }} {{ $serviceMetrics['total'] }}</span>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="orders-section" id="history">
                    <div class="section-label">All orders</div>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order Ref</th>
                                <th>Category</th>
                                <th>Units</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->order_ref }}</td>
                                <td>{{ ucfirst($order->temp_requirement) }}</td>
                                <td>{{ $order->order_units }}</td>
                                <td>
                                    @php
                                        $cls = match($order->status) {
                                            'allocated','in_transit' => 'badge-success',
                                            'deferred' => 'badge-error',
                                            'delivered' => 'badge-info',
                                            default => 'badge-warning'
                                        };
                                    @endphp
                                    <span class="badge {{ $cls }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td style="color:#74777F">{{ $order->order_date }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align:center; color:#74777F; padding:32px">
                                    No orders yet. Place your first order above.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="card">
                    <div class="card-title">Quick reorder</div>
                    <div class="stat-chips">
                        @if($orders->count() > 0)
                            @php $last = $orders->first(); @endphp
                            <span class="stat-chip">{{ $last->order_units }} units</span>
                            <span class="stat-chip">{{ number_format($last->order_weight_kg, 0) }} kg</span>
                            <span class="stat-chip">{{ number_format($last->order_volume_m3, 2) }} m³</span>
                        @else
                            <span class="stat-chip">No prior order</span>
                        @endif
                    </div>
                    <button class="btn-filled" style="width:100%" 
                        @click="orderUnits = {{ $orders->count() > 0 ? $last->order_units : "''" }}; orderTemp = '{{ $orders->count() > 0 ? $last->temp_requirement : 'ambient' }}'; showOrderModal = true"
                        @if($cutoffPassed) disabled style="width:100%; opacity:0.5; cursor:not-allowed" @endif>
                        @if($cutoffPassed) Cutoff Passed @else Reorder for tomorrow @endif
                    </button>
                </div>

                <div class="card">
                    <div class="card-title">Fill rate</div>
                    <div class="metric-big">
                        @php
                            $total = $orders->count();
                            $delivered = $orders->where('status','delivered')->count();
                            $fillRate = $total > 0 ? round(($delivered / $total) * 100) : 0;
                        @endphp
                        {{ $fillRate }}%
                    </div>
                    <div class="metric-sub">
                        {{ $delivered }} delivered · {{ $deferrals->count() }} deferred
                    </div>
                </div>

                @if($deferrals->count() > 0)
                <div class="card" style="border-color: #E65100">
                    <div class="card-title" style="color:#E65100">Deferral History</div>
                    @foreach($deferrals as $d)
                    <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid rgba(116,119,127,0.15); font-size:13px;">
                        <span>{{ $d->reason_code }}</span>
                        <span style="color:#74777F">{{ $d->deferred_date }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </aside>
        </div>
    </div>

    <!-- ── Place Order Modal ─────────────────────────────────────────── -->
    <div class="modal-overlay" :class="{ 'open': showOrderModal }" @click.self="showOrderModal = false">
        <div class="modal-sheet">
            <div class="modal-header">
                <span class="modal-title">Place New Order</span>
                <button class="modal-close" @click="showOrderModal = false" aria-label="Close">✕</button>
            </div>
            @if($cutoffPassed)
            <div class="alert alert-warning">
                <span class="material-symbols-outlined" style="font-size:18px">lock_clock</span>
                16:00 cutoff has passed. This order will be placed for the next operating day.
            </div>
            @endif
            <form action="{{ route('store.order') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="outlet_id" value="{{ $user->outlet_id }}">
                <div style="margin-bottom:16px">
                    <label class="field-label" for="order_units">Units Needed</label>
                    <input type="number" id="order_units" name="order_units" required min="1" class="field-input" placeholder="e.g. 250" x-model="orderUnits">
                </div>
                <div style="margin-bottom:16px">
                    <label class="field-label" for="temp_requirement">Temperature Requirement</label>
                    <select id="temp_requirement" name="temp_requirement" class="field-input" x-model="orderTemp">
                        <option value="ambient">🌡 Ambient (dry goods)</option>
                        <option value="chilled">❄️ Chilled (2-4°C dairy, fresh)</option>
                        <option value="frozen">🧊 Frozen (<-18°C)</option>
                    </select>
                </div>
                <div style="margin-bottom:24px; display:flex; align-items:center; gap:12px;">
                    <input type="checkbox" name="urgency" id="urgency" value="1" style="width:20px; height:20px; cursor:pointer">
                    <label for="urgency" style="font-size:14px; cursor:pointer">
                        🚨 <strong>Urgency flag</strong> — I am critically low on stock
                    </label>
                </div>
                <button type="submit" class="btn-filled" style="width:100%; justify-content:center; padding:14px 24px; font-size:16px">
                    Submit Order
                </button>
            </form>
        </div>
    </div>

    <!-- ── Confirm Receipt Modal ─────────────────────────────────────── -->
    <div class="modal-overlay" :class="{ 'open': showAcceptModal }" @click.self="showAcceptModal = false">
        <div class="modal-sheet">
            <div class="modal-header">
                <span class="modal-title">Confirm Receipt</span>
                <button class="modal-close" @click="showAcceptModal = false">✕</button>
            </div>
            <form action="{{ route('store.accept') }}" method="POST">
                @csrf
                <input type="hidden" name="order_ref" value="{{ $activeOrder?->order_ref }}">
                <input type="hidden" name="leg_id" value="{{ $activeLegId }}">
                <div style="margin-bottom:16px">
                    <label class="field-label">Seal Number (from vehicle)</label>
                    <input type="text" name="seal_number" class="field-input" placeholder="e.g. SL-9942">
                </div>
                <div style="margin-bottom:16px" x-data="{ units: {{ $activeOrder?->order_units ?? 0 }}, expected: {{ $activeOrder?->order_units ?? 0 }} }">
                    <label class="field-label">Units Received</label>
                    <input type="number" name="confirmed_units" class="field-input" x-model="units" min="0">
                    
                    <div x-show="units != expected" style="margin-top:8px; display:none; background:rgba(230,81,0,0.1); color:#E65100; padding:8px 12px; border-radius:6px; font-size:12px; display:flex; gap:6px; align-items:center;">
                        <span class="material-symbols-outlined" style="font-size:16px">error</span>
                        Quantity mismatch! Expected <span x-text="expected"></span> units. Please add a note below.
                    </div>
                </div>
                <div style="margin-bottom:24px">
                    <label class="field-label">Notes (optional)</label>
                    <textarea name="discrepancy_note" class="field-input" rows="2" placeholder="Any issues with the delivery?"></textarea>
                </div>
                <button type="submit" class="btn-filled" style="width:100%; justify-content:center; padding:14px 24px; background:#2E7D32">
                    <span class="material-symbols-outlined" style="font-size:18px">task_alt</span>
                    Confirm & Sign
                </button>
            </form>
        </div>
    </div>

    <!-- ── Dispute Modal ─────────────────────────────────────────────── -->
    <div class="modal-overlay" :class="{ 'open': showDisputeModal }" @click.self="showDisputeModal = false">
        <div class="modal-sheet">
            <div class="modal-header">
                <span class="modal-title" style="color:#C62828">⚠ Report Issue</span>
                <button class="modal-close" @click="showDisputeModal = false">✕</button>
            </div>
            <form action="{{ route('store.dispute') }}" method="POST">
                @csrf
                <input type="hidden" name="order_ref" value="{{ $activeOrder?->order_ref }}">
                <input type="hidden" name="leg_id" value="{{ $activeLegId }}">
                <div style="margin-bottom:16px">
                    <label class="field-label">Issue Type</label>
                    <select name="dispute_type" class="field-input">
                        <option value="quantity_short">Quantity short</option>
                        <option value="temperature_breach">Temperature breach (cold chain violated)</option>
                        <option value="damaged_items">Damaged items</option>
                        <option value="wrong_items">Wrong items delivered</option>
                    </select>
                </div>
                <div style="margin-bottom:24px">
                    <label class="field-label">Notes</label>
                    <textarea name="notes" class="field-input" rows="3" placeholder="Describe the issue..."></textarea>
                </div>
                <button type="submit" class="btn-filled" style="width:100%; justify-content:center; padding:14px 24px; background:#C62828">
                    Log Dispute
                </button>
            </form>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('storeApp', () => ({
                showOrderModal:   false,
                showAcceptModal:  false,
                showDisputeModal: false,
                orderUnits: '',
                orderTemp: 'ambient',
                init() {
                    this.$watch('showOrderModal', val => { if(val) document.body.style.overflow = 'hidden'; else document.body.style.overflow = ''; });
                    this.$watch('showAcceptModal', val => { if(val) document.body.style.overflow = 'hidden'; else document.body.style.overflow = ''; });
                    this.$watch('showDisputeModal', val => { if(val) document.body.style.overflow = 'hidden'; else document.body.style.overflow = ''; });
                },
                closeAll() {
                    this.showOrderModal = false;
                    this.showAcceptModal = false;
                    this.showDisputeModal = false;
                }
            }));
        });
    </script>
</body>
</html>
