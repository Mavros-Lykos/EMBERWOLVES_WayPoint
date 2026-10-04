<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crisis Mitigate - Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        :root {
            --primary: #ef4444; /* Crisis theme */
            --surf: #0f172a;
            --surf-c: #1e293b;
            --on-surf: #f8fafc;
            --outline: #334155;
            --success: #22c55e;
            --warn: #eab308;
            --crit: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surf);
            color: var(--on-surf);
            margin: 0;
            padding: 0;
            line-height: 1.5;
            display: flex;
            min-height: 100vh;
        }

        /* Nav Rail */
        .nav-rail { width: 80px; background: var(--surf-c); display: flex; flex-direction: column; align-items: center; padding: 12px 0; gap: 4px; border-right: 1px solid var(--outline); flex-shrink: 0; }
        .brand-mark { width: 48px; height: 48px; background: var(--primary); color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; margin-bottom: 20px; }
        .nav-item { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 4px 0; width: 100%; cursor: pointer; color: #94a3b8; text-decoration: none; border: none; background: none; font-size: 11px; }
        .nav-item:hover { color: var(--on-surf); }
        .nav-item.active .icon-wrap { background: rgba(239,68,68,0.2); color: var(--primary); border-radius: 999px; padding: 4px 20px; }
        .icon-wrap { display: flex; align-items: center; justify-content: center; padding: 4px 20px; }
        
        .main-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; overflow-y: auto; }

        .top-bar {
            background: #450a0a;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #7f1d1d;
        }

        .container {
            max-width: 1000px;
            margin: 48px auto;
            padding: 0 24px;
            flex: 1;
        }

        .alert-banner {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--crit);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            gap: 24px;
            align-items: flex-start;
            margin-bottom: 32px;
            box-shadow: 0 0 32px rgba(239, 68, 68, 0.1);
        }

        .alert-icon {
            background: var(--crit);
            color: #fff;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 0 16px var(--crit);
        }

        .metric-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .metric-card {
            background: var(--surf-c);
            border: 1px solid var(--outline);
            border-radius: 12px;
            padding: 24px;
        }
        
        .metric-card.danger {
            border-color: rgba(239, 68, 68, 0.5);
            background: linear-gradient(180deg, var(--surf-c) 0%, rgba(69, 10, 10, 0.2) 100%);
        }

        .prog-bg {
            background: #334155;
            height: 12px;
            border-radius: 6px;
            margin-top: 16px;
            overflow: hidden;
        }
        
        .prog-fill {
            height: 100%;
            border-radius: 6px;
        }
        
        .btn-action {
            background: transparent;
            border: 1px solid var(--outline);
            color: var(--on-surf);
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-right: 12px;
        }
        .btn-action:hover { background: rgba(255,255,255,0.05); }
        .btn-action.primary {
            background: var(--crit);
            border-color: var(--crit);
            color: #fff;
        }
        .btn-action.primary:hover {
            background: #dc2626;
            box-shadow: 0 0 16px rgba(239,68,68,0.4);
        }

        .nav-links {
            display: flex;
            gap: 24px;
        }
        .nav-links a {
            color: #fca5a5;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        .nav-links a.active { color: #fff; font-weight: 600; border-bottom: 2px solid #f87171; padding-bottom: 4px;}
        .nav-links a:hover { color: #fff; }

    </style>
</head>
<body>

    <!-- Nav Rail -->
    <nav class="nav-rail" aria-label="Dispatch navigation">
        <div class="brand-mark">W</div>
        <a href="{{ route('dispatch.overview') }}" class="nav-item">
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
        <a href="{{ route('dispatch.crisis') }}" class="nav-item active" aria-current="page">
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
            <div style="font-size:18px; font-weight:700; letter-spacing:1px; color:#f87171">SYSTEM OVERLOAD DETECTED</div>
            <div class="nav-links">
                <a href="{{ route('dispatch.crisis') }}" class="active">{{ __('Crisis Mitigate') }}</a>
            </div>
        </header>

        <div class="container">
            
            @php
                $chilledPct = $chilledCap > 0 ? ($chilledDemand / $chilledCap) * 100 : 0;
            @endphp

            @if($chilledPct > 100)
            <div class="alert-banner">
                <div class="alert-icon">
                    <span class="material-symbols-outlined" style="font-size:28px">warning</span>
                </div>
                <div>
                    <h2 style="margin:0 0 8px 0; color:#f87171; font-size:20px;">D1 Degradation: Reefer Capacity Overflow</h2>
                    <p style="margin:0; color:#cbd5e1; font-size:15px; max-width:800px;">
                        The total volume of pending Chilled/Fresh orders currently exceeds the absolute theoretical maximum volume of our refrigerated fleet (assuming 2 trips per vehicle per day). Standard auto-allocation will fail. You must apply crisis mitigation policies before locking the plan.
                    </p>
                </div>
            </div>
            @else
            <div class="alert-banner" style="background: rgba(34,197,94,0.1); border-color: var(--success); box-shadow: 0 0 32px rgba(34,197,94,0.1);">
                <div class="alert-icon" style="background: var(--success); box-shadow: 0 0 16px var(--success);">
                    <span class="material-symbols-outlined" style="font-size:28px">check_circle</span>
                </div>
                <div>
                    <h2 style="margin:0 0 8px 0; color:#4ade80; font-size:20px;">System Nominal</h2>
                    <p style="margin:0; color:#cbd5e1; font-size:15px; max-width:800px;">
                        Current chilled and ambient volume demand is well within the active fleet's theoretical maximum capacity. Standard auto-allocation can proceed normally without requiring mitigation policies.
                    </p>
                </div>
            </div>
            @endif

            <div class="metric-grid">
            <div class="metric-card danger">
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="font-weight:600; color:#f87171">Chilled & Perishables (Reefer Fleet)</span>
                    <span style="font-weight:700; color:#f87171">{{ number_format($chilledPct, 1) }}% LOAD</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:14px; color:#cbd5e1;">
                    <span>Demand: {{ number_format($chilledDemand, 1) }} m³</span>
                    <span>Fleet Cap: {{ number_format($chilledCap, 1) }} m³</span>
                </div>
                <div class="prog-bg">
                    <div class="prog-fill" style="width:{{ min($chilledPct, 100) }}%; background:var(--crit)"></div>
                </div>
                @if($chilledPct > 100)
                <div style="margin-top:16px; font-size:13px; color:#fca5a5;">
                    ⚠️ Deficit of {{ number_format($chilledDemand - $chilledCap, 1) }} m³ (approx {{ ceil(($chilledDemand - $chilledCap)/14) }} truckloads).
                </div>
                @endif
            </div>

            <!-- Ambient Capacity -->
            @php
                $ambientPct = $ambientCap > 0 ? ($ambientDemand / $ambientCap) * 100 : 0;
            @endphp
            <div class="metric-card">
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="font-weight:600;">Ambient Goods (Dry Fleet)</span>
                    <span style="font-weight:700;">{{ number_format($ambientPct, 1) }}% LOAD</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:14px; color:#94a3b8;">
                    <span>Demand: {{ number_format($ambientDemand, 1) }} m³</span>
                    <span>Fleet Cap: {{ number_format($ambientCap, 1) }} m³</span>
                </div>
                <div class="prog-bg">
                    <div class="prog-fill" style="width:{{ min($ambientPct, 100) }}%; background:var(--primary)"></div>
                </div>
            </div>

        </div>

        <div style="margin-top:40px; background:var(--surf-c); border:1px solid var(--outline); border-radius:12px; padding:32px;">
            <h3 style="margin:0 0 16px 0; color:var(--on-surf)">Crisis Mitigation Protocols</h3>
            
            <div style="display:flex; gap:16px; flex-wrap:wrap;">
                <button class="btn-action primary" onclick="alert('Executing ML Fair-Share Deferral. Analysing orders...')">
                    <span class="material-symbols-outlined" style="vertical-align:middle; font-size:18px; margin-right:6px;">gavel</span>
                    Execute ML Fair-Share Deferral
                </button>
                <button class="btn-action" onclick="alert('Converting 3 dry trucks into ice-box mode...')">
                    Convert 3 Dry Trucks to Ice-Box Mode
                </button>
                <button class="btn-action" onclick="alert('Requesting 3PL Fleet... Quotes pending.')">
                    Request 3rd-Party 3PL Fleet
                </button>
            </div>
            
            <p style="color:#94a3b8; font-size:13px; margin-top:24px;">
                * Fair-Share Deferral automatically defers non-critical Style/Tech items and distributes chilled deferrals evenly across outlets that were NOT deferred yesterday.
            </p>
        </div>

        </div>
    </div>

</body>
</html>
