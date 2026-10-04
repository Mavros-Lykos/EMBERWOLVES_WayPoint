<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch Control — Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --surf: #0f172a; --surf-c: #1e293b; --surf-cl: #0f172a;
            --on-surf: #e2e8f0; --outline: rgba(148,163,184,0.2);
            --primary: #3b82f6; --on-primary: #fff;
            --success: #22c55e; --warn: #f59e0b; --crit: #ef4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Roboto', sans-serif; display: flex; min-height: 100vh; background: var(--surf); color: var(--on-surf); }
        /* Nav Rail */
        .nav-rail { width: 80px; background: var(--surf-c); display: flex; flex-direction: column; align-items: center; padding: 12px 0; gap: 4px; border-right: 1px solid var(--outline); flex-shrink: 0; }
        .brand-mark { width: 48px; height: 48px; background: var(--primary); color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; margin-bottom: 20px; }
        .nav-item { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 4px 0; width: 100%; cursor: pointer; color: #94a3b8; text-decoration: none; border: none; background: none; font-size: 11px; }
        .nav-item:hover { color: var(--on-surf); }
        .nav-item.active .icon-wrap { background: rgba(59,130,246,0.2); color: var(--primary); border-radius: 999px; padding: 4px 20px; }
        .icon-wrap { display: flex; align-items: center; justify-content: center; padding: 4px 20px; }
        /* Layout */
        .main-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .top-bar { height: 64px; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--outline); background: var(--surf-c); }
        .page-title { font-size: 20px; font-weight: 500; }
        .meta { display: flex; align-items: center; gap: 16px; }
        .cutoff-chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; background: rgba(239,68,68,0.15); color: #fca5a5; border-radius: 999px; font-size: 13px; font-weight: 500; border: 1px solid rgba(239,68,68,0.3); }
        .page-body { flex: 1; overflow-y: auto; padding: 24px; }
        /* Aggregate Strip */
        .agg-strip { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
        .agg-item { flex: 1; min-width: 160px; background: var(--surf-c); border: 1px solid var(--outline); border-radius: 12px; padding: 16px; }
        .agg-label { font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 4px; }
        .agg-value { font-size: 36px; font-weight: 400; color: var(--on-surf); line-height: 1.1; }
        .agg-sub { font-size: 12px; color: #94a3b8; margin-top: 2px; }
        /* Grid */
        .ops-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .ops-card { background: var(--surf-c); border: 1px solid var(--outline); border-radius: 12px; padding: 20px; }
        .card-header { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
        .card-header .material-symbols-outlined { color: var(--primary); font-size: 20px; }
        .card-title { font-size: 15px; font-weight: 500; }
        /* Capacity Bars */
        .cap-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--outline); }
        .cap-row:last-child { border-bottom: none; }
        .cap-label { font-size: 14px; color: var(--on-surf); }
        .cap-bar-wrap { display: flex; align-items: center; gap: 8px; }
        .cap-bar { width: 100px; height: 6px; background: rgba(148,163,184,0.2); border-radius: 999px; overflow: hidden; }
        .cap-fill { height: 100%; border-radius: 999px; }
        .cap-fill.ok { background: var(--success); }
        .cap-fill.warn { background: var(--warn); }
        .cap-fill.crit { background: var(--crit); }
        .cap-pct { font-size: 12px; color: #94a3b8; min-width: 36px; text-align: right; font-family: 'Roboto Mono', monospace; }
        /* Fleet list */
        .fleet-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--outline); }
        .fleet-row:last-child { border-bottom: none; }
        .fleet-type { font-size: 14px; }
        .fleet-count { font-size: 14px; color: #94a3b8; font-family: 'Roboto Mono', monospace; }
        /* Action Bar */
        .action-bar { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
        .btn-filled { display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: #fff; border: none; border-radius: 999px; padding: 10px 24px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
        .btn-filled:hover { box-shadow: 0 6px 16px rgba(59,130,246,0.4); transform: translateY(-1px); }
        .btn-filled:active { box-shadow: 0 2px 4px rgba(59,130,246,0.3); transform: translateY(0); }
        .btn-outlined { display: inline-flex; align-items: center; gap: 8px; background: transparent; color: var(--primary); border: 1px solid rgba(59,130,246,0.4); border-radius: 999px; padding: 10px 24px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-outlined:hover { background: rgba(59,130,246,0.1); box-shadow: 0 4px 8px rgba(0,0,0,0.2); transform: translateY(-1px); }
        .btn-outlined:active { transform: translateY(0); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        /* Alerts section */
        .alerts-section { margin-bottom: 24px; }
        .alert-card { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 12px; padding: 16px; display: flex; gap: 12px; align-items: flex-start; }
        .alert-card + .alert-card { margin-top: 8px; }
        /* Active Trips Table */
        .trips-table { width: 100%; border-collapse: collapse; }
        .trips-table th { font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; padding: 10px 16px; border-bottom: 1px solid var(--outline); text-align: left; }
        .trips-table td { padding: 12px 16px; border-bottom: 1px solid var(--outline); font-size: 13px; }
        .trips-table tr:hover td { background: var(--surf-c); }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 10px; border-radius: 999px; font-size: 11px; font-weight: 500; }
        .badge-success { background: rgba(34,197,94,0.15); color: #86efac; }
        .badge-warning { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .badge-info { background: rgba(59,130,246,0.15); color: #93c5fd; }
        /* Allocation result */
        .alloc-result { display: none; background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); border-radius: 12px; padding: 16px; margin-bottom: 16px; }
        .alloc-result.show { display: block; }
        @media (max-width: 1024px) { .ops-grid { grid-template-columns: 1fr 1fr; } .nav-rail { display: none; } }
        @media (max-width: 640px) { .ops-grid { grid-template-columns: 1fr; } .agg-strip { flex-direction: column; } }
    </style>
</head>
<body x-data="dispatchApp()">

    <!-- Nav Rail -->
    <nav class="nav-rail" aria-label="Dispatch navigation">
        <div class="brand-mark">W</div>
        <a href="{{ route('dispatch.overview') }}" class="nav-item active" aria-current="page">
            <span class="icon-wrap"><span class="material-symbols-outlined">dashboard</span></span>
            <span>Overview</span>
        </a>
        <a href="{{ route('dispatch.plan') }}" class="nav-item">
            <span class="icon-wrap"><span class="material-symbols-outlined">view_kanban</span></span>
            <span>Plan</span>
        </a>
        <a href="{{ route('dispatch.live') }}" class="nav-item">
            <span class="icon-wrap"><span class="material-symbols-outlined">satellite_alt</span></span>
            <span>Live Map</span>
        </a>
        <a href="{{ route('dispatch.crisis') }}" class="nav-item">
            <span class="icon-wrap"><span class="material-symbols-outlined">warning</span></span>
            <span>Crisis</span>
        </a>
        <a href="{{ route('dispatch.reports') }}" class="nav-item">
            <span class="icon-wrap"><span class="material-symbols-outlined">analytics</span></span>
            <span>Reports</span>
        </a>
        <form action="{{ route('logout') }}" method="POST" style="width:100%">
            @csrf
            <button type="submit" class="nav-item">
                <span class="icon-wrap"><span class="material-symbols-outlined">logout</span></span>
                <span>Exit</span>
            </button>
        </form>
    </nav>

    <div class="main-content">
        <header class="top-bar">
            <span class="page-title">{{ __('Fleet Overview') }} — Peliyagoda DC &amp; Kandy Hub</span>
            <div class="meta">
                <div class="cutoff-chip">
                    <span class="material-symbols-outlined" style="font-size:18px">lock_clock</span>
                    16:00 {{ __('Cutoff') }}
                </div>
                <div style="display:flex; align-items:center; gap: 8px;">
                    @include('partials.notifications')
                    @include('partials.settings')
                </div>
                <span style="font-size:13px; color:#94a3b8">{{ auth()->user()->name ?? 'Kamal' }}</span>
            </div>
        </header>

        <div class="page-body">

            <!-- Allocation Result Banner -->
            <div class="alloc-result" :class="{ show: allocResult }" x-show="allocResult">
                <span class="material-symbols-outlined" style="color:#22c55e; float:left; margin-right:8px">check_circle</span>
                <span x-text="allocResult"></span>
            </div>

            <!-- Degradation D1: Reefer Capacity Overflow -->
            @if($reeferOverflowCount > 0)
            <div class="alerts-section" style="margin-bottom: 24px;">
                <div class="alert-card" style="background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3);">
                    <span class="material-symbols-outlined" style="color:#f59e0b; flex-shrink:0">ac_unit</span>
                    <div>
                        <strong style="color: #fcd34d;">Degradation Alert: Reefer Overflow</strong><br>
                        {{ $reeferOverflowCount }} chilled orders were deferred due to max reefer vehicle capacity.
                        <a href="{{ route('dispatch.crisis') }}" class="btn-outlined" style="margin-top: 8px; border-color: rgba(245,158,11,0.4); color: #f59e0b; padding: 4px 12px; font-size: 12px; text-decoration: none;">Review 3P Reefer Options</a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Critical Alerts -->
            @if($criticalOutlets->count() > 0)
            <div class="alerts-section">
                @foreach($criticalOutlets->take(3) as $outlet)
                <div class="alert-card">
                    <span class="material-symbols-outlined" style="color:#ef4444; flex-shrink:0">warning</span>
                    <div>
                        <strong>{{ $outlet->outlet_id }}</strong> — {{ $outlet->district }} ·
                        Not served for <strong>{{ $outlet->days_since_last_served }} days</strong> ·
                        {{ ucfirst($outlet->temp_requirement) }}
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Aggregate Strip -->
            <div class="agg-strip">
                <div class="agg-item">
                    <div class="agg-label">{{ __('Pending Orders') }}</div>
                    <div class="agg-value">{{ $pendingCount }}</div>
                    <div class="agg-sub">{{ __('Awaiting allocation') }}</div>
                </div>
                <div class="agg-item">
                    <div class="agg-label">{{ __('Total Weight') }}</div>
                    <div class="agg-value">{{ number_format($totalWeight/1000, 1) }}T</div>
                    <div class="agg-sub">{{ __('Pending + allocated') }}</div>
                </div>
                <div class="agg-item">
                    <div class="agg-label">{{ __('Volume') }}</div>
                    <div class="agg-value">{{ number_format($totalVolume, 0) }} m³</div>
                    <div class="agg-sub">{{ __('Across all orders') }}</div>
                </div>
                <div class="agg-item">
                    <div class="agg-label">{{ __('Vehicles') }}</div>
                    <div class="agg-value">{{ $vehicles->count() }}</div>
                    <div class="agg-sub">{{ $reeferVehicles->count() }} {{ __('reefer') }} · {{ $ambientVehicles->count() }} {{ __('ambient') }}</div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <button class="btn-filled" @click="runAllocation" :disabled="allocating">
                    <span class="material-symbols-outlined" style="font-size:18px">auto_awesome</span>
                    <span x-text="allocating ? '{{ __('Running…') }}' : '{{ __('Run Allocation Engine') }}'"></span>
                </button>
                <a href="{{ route('dispatch.plan') }}" class="btn-outlined" style="text-decoration: none;">
                    <span class="material-symbols-outlined" style="font-size:18px">view_kanban</span>
                    {{ __('Open Planning Canvas') }}
                </a>
                <a href="{{ route('dispatch.reports') }}" class="btn-outlined" style="border-color: rgba(245,158,11,0.4); color:#f59e0b; text-decoration: none;">
                    <span class="material-symbols-outlined" style="font-size:18px">history</span>
                    {{ __('Deferrals') }} ({{ $deferralCount }})
                </a>
                <a href="{{ route('dispatch.crisis') }}" class="btn-outlined" style="border-color: rgba(239,68,68,0.4); color:#ef4444; text-decoration: none;">
                    <span class="material-symbols-outlined" style="font-size:18px">local_shipping</span>
                    {{ __('Review 3P Reefer Options') }}
                </a>
            </div>

            <!-- Depot + Fleet Grid -->
            <div class="ops-grid">
                <!-- Peliyagoda -->
                <div class="ops-card">
                    <div class="card-header">
                        <span class="material-symbols-outlined">warehouse</span>
                        <span class="card-title">Peliyagoda DC</span>
                    </div>
                    @php
                        $peliyVehicles = $vehicles->where('depot', 'Peliyagoda');
                        $peliyReefer   = $peliyVehicles->where('temp', 'reefer');
                    @endphp
                    <div class="cap-row">
                        <span class="cap-label">Ambient trucks</span>
                        <div class="cap-bar-wrap">
                            <div class="cap-bar"><div class="cap-fill ok" style="width:84%"></div></div>
                            <span class="cap-pct">{{ $peliyVehicles->where('temp','ambient')->count() }}</span>
                        </div>
                    </div>
                    <div class="cap-row">
                        <span class="cap-label">Reefer capacity</span>
                        <div class="cap-bar-wrap">
                            <div class="cap-bar"><div class="cap-fill crit" style="width:94%"></div></div>
                            <span class="cap-pct">{{ $peliyReefer->count() }}</span>
                        </div>
                    </div>
                    <div class="cap-row">
                        <span class="cap-label">Pending orders</span>
                        <div class="cap-bar-wrap">
                            <span class="cap-pct" style="font-size:14px; color:var(--on-surf)">{{ $pendingOrders->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kandy -->
                <div class="ops-card">
                    <div class="card-header">
                        <span class="material-symbols-outlined">warehouse</span>
                        <span class="card-title">Kandy Hub</span>
                    </div>
                    @php $kandyVehicles = $vehicles->where('depot', 'Kandy'); @endphp
                    <div class="cap-row">
                        <span class="cap-label">Ambient trucks</span>
                        <div class="cap-bar-wrap">
                            <div class="cap-bar"><div class="cap-fill ok" style="width:72%"></div></div>
                            <span class="cap-pct">{{ $kandyVehicles->where('temp','ambient')->count() }}</span>
                        </div>
                    </div>
                    <div class="cap-row">
                        <span class="cap-label">Reefer capacity</span>
                        <div class="cap-bar-wrap">
                            <div class="cap-bar"><div class="cap-fill ok" style="width:68%"></div></div>
                            <span class="cap-pct">{{ $kandyVehicles->where('temp','reefer')->count() }}</span>
                        </div>
                    </div>
                    <div class="cap-row">
                        <span class="cap-label">Vehicles total</span>
                        <div class="cap-bar-wrap">
                            <span class="cap-pct" style="font-size:14px; color:var(--on-surf)">{{ $kandyVehicles->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Fleet Status -->
                <div class="ops-card">
                    <div class="card-header">
                        <span class="material-symbols-outlined">directions_bus</span>
                        <span class="card-title">Fleet Breakdown</span>
                    </div>
                    <div class="fleet-row">
                        <span class="fleet-type">Reefer trucks</span>
                        <span class="fleet-count">{{ $reeferVehicles->where('type','truck')->count() }} ready</span>
                    </div>
                    <div class="fleet-row">
                        <span class="fleet-type">Dry trucks</span>
                        <span class="fleet-count">{{ $ambientVehicles->where('type','truck')->count() }} ready</span>
                    </div>
                    <div class="fleet-row">
                        <span class="fleet-type">Reefer vans</span>
                        <span class="fleet-count">{{ $reeferVehicles->where('type','van')->count() }} ready</span>
                    </div>
                    <div class="fleet-row">
                        <span class="fleet-type">Dry vans</span>
                        <span class="fleet-count">{{ $ambientVehicles->where('type','van')->count() }} ready</span>
                    </div>
                </div>
            </div>

            <!-- Active Trips -->
            @if($activeTrips->count() > 0)
            <div class="ops-card" style="margin-top:0">
                <div class="card-header">
                    <span class="material-symbols-outlined">local_shipping</span>
                    <span class="card-title">Active Trips Today ({{ $activeTrips->count() }})</span>
                </div>
                <table class="trips-table">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Brand</th>
                            <th>District</th>
                            <th>Trip #</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeTrips as $t)
                        <tr>
                            <td style="font-family:'Roboto Mono',monospace">{{ $t->vehicle_id }}</td>
                            <td>{{ $t->brand }}</td>
                            <td>{{ $t->district }}</td>
                            <td>{{ $t->trip_number }}</td>
                            <td>
                                <span class="badge {{ $t->status === 'dispatched' ? 'badge-success' : 'badge-info' }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dispatchApp', () => ({
                allocating: false,
                allocResult: null,

                runAllocation() {
                    this.allocating = true;
                    this.allocResult = null;
                    fetch('{{ route('dispatch.allocate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ date: '{{ now()->toDateString() }}' })
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.allocating = false;
                        this.allocResult = data.message;
                        // Reload page to reflect new allocations
                        setTimeout(() => location.reload(), 2000);
                    })
                    .catch(() => {
                        this.allocating = false;
                        this.allocResult = 'Allocation failed. Check server logs.';
                    });
                }
            }));
        });
    </script>
</body>
</html>
