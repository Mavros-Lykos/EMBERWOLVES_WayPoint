<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Route Manifest — Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f172a">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
        }
    </script>
    <style>
        :root { --surf:#0f172a; --surf-c:#1e293b; --on-surf:#e2e8f0; --outline:rgba(148,163,184,0.2); --primary:#3b82f6; --success:#22c55e; --warn:#f59e0b; --crit:#ef4444; }
        * { box-sizing:border-box; margin:0; padding:0; }
        html { font-size:16px; }
        body { font-family:'Roboto',sans-serif; display:flex; flex-direction:column; min-height:100vh; max-width:420px; margin:0 auto; background:var(--surf); color:var(--on-surf); }
        
        /* A11y Focus States */
        button:focus-visible, a:focus-visible, input:focus-visible, textarea:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }

        /* Top Bar */
        .top-bar { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1px solid var(--outline); background:var(--surf-c); position:sticky; top:0; z-index:20; box-shadow: 0 4px 16px rgba(0,0,0,0.4); }
        .title { font-size:18px; font-weight:500; }
        .sync-badge { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:999px; font-size:12px; cursor:pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: transform 0.2s; }
        .sync-badge:active { transform: scale(0.95); }
        .online { background:rgba(34,197,94,0.1); color:#86efac; border:1px solid rgba(34,197,94,0.3); }
        .offline { background:rgba(245,158,11,0.1); color:#fcd34d; border:1px solid rgba(245,158,11,0.3); }
        
        /* Main */
        main { flex:1; padding:16px; padding-bottom:100px; display:flex; flex-direction:column; gap:16px; }
        
        /* Next Stop Card */
        .next-stop-card { background:var(--surf-c); border:2px solid var(--primary); border-radius:16px; padding:20px; box-shadow: 0 8px 32px rgba(59,130,246,0.15), 0 4px 12px rgba(0,0,0,0.3); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .stop-tag { display:inline-flex; align-items:center; gap:6px; background:var(--primary); color:#fff; font-size:12px; font-weight:600; padding:3px 12px; border-radius:999px; margin-bottom:12px; text-transform:uppercase; letter-spacing:0.05em; box-shadow: 0 2px 8px rgba(59,130,246,0.4); }
        .stop-counter { font-size:12px; color:#94a3b8; float:right; }
        .outlet-name { font-size:22px; font-weight:600; margin:0 0 4px; }
        .outlet-meta { font-size:13px; color:#94a3b8; margin-bottom:16px; display:flex; gap:8px; flex-wrap:wrap; }
        .outlet-meta span { display:inline-flex; align-items:center; gap:4px; }
        
        /* Window status */
        .window-bar { background:rgba(59,130,246,0.1); border:1px solid rgba(59,130,246,0.2); border-radius:8px; padding:10px 14px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; font-size:13px; }
        .window-label { color:#94a3b8; }
        .window-time { font-weight:600; color:var(--primary); }
        
        /* Action buttons */
        .stop-actions { display:flex; gap:8px; }
        .nav-btn { display:flex; align-items:center; justify-content:center; gap:8px; flex:1; height:56px; background:rgba(59,130,246,0.1); color:var(--primary); border:1px solid rgba(59,130,246,0.3); border-radius:12px; text-decoration:none; font-size:15px; font-weight:500; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.2s; }
        .nav-btn:active { background:rgba(59,130,246,0.2); transform: scale(0.97); }
        .issue-btn { display:flex; align-items:center; justify-content:center; gap:8px; flex:1; height:56px; background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.3); border-radius:12px; font-size:15px; font-weight:500; cursor:pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.2s; }
        .issue-btn:active { transform: scale(0.97); background:rgba(239,68,68,0.2); }
        .arrived-btn { width:100%; height:64px; background:var(--primary); color:#fff; border:none; border-radius:12px; font-size:20px; font-weight:600; cursor:pointer; margin-top:10px; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow: 0 8px 24px rgba(59,130,246,0.3); transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .arrived-btn:active { transform: scale(0.96); box-shadow: 0 4px 12px rgba(59,130,246,0.4); }
        
        /* Stop list */
        .section-label { font-size:10px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:10px; margin-top:8px; }
        .stop-row { background:var(--surf-c); border:1px solid var(--outline); border-radius:10px; padding:14px 16px; opacity:0.6; display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: opacity 0.2s, transform 0.2s; }
        .stop-row:active { transform: scale(0.98); }
        .stop-row.completed { border-color:rgba(34,197,94,0.3); background:rgba(34,197,94,0.05); opacity: 0.8; }
        .stop-name { font-size:15px; font-weight:500; }
        .stop-time { font-size:12px; color:#94a3b8; margin-top:2px; }
        .badge { display:inline-flex; align-items:center; gap:3px; padding:2px 8px; border-radius:999px; font-size:11px; font-weight:500; }
        .badge-success { background:rgba(34,197,94,0.15); color:#86efac; box-shadow: 0 2px 4px rgba(34,197,94,0.2); }
        .badge-primary { background:rgba(59,130,246,0.15); color:#93c5fd; box-shadow: 0 2px 4px rgba(59,130,246,0.2); }
        
        /* No trip state */
        .empty-state { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:16px; padding:40px 20px; text-align:center; }
        
        /* PoD Modal */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:50; flex-direction:column; backdrop-filter: blur(4px); }
        .modal-overlay.open { display:flex; }
        .modal-sheet { background:var(--surf-c); border-radius:20px 20px 0 0; padding:20px; flex:1; overflow-y:auto; margin-top:auto; border-top:1px solid var(--outline); box-shadow: 0 -8px 32px rgba(0,0,0,0.6); animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .modal-title { font-size:18px; font-weight:500; }
        .modal-close { background:none; border:none; color:#94a3b8; font-size:24px; cursor:pointer; padding:4px; border-radius:50%; transition: background 0.2s; }
        .modal-close:active { background: rgba(148,163,184,0.2); }
        label.fl { display:block; font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:6px; }
        .photo-zone { width:100%; height:100px; background:rgba(59,130,246,0.05); border:2px dashed rgba(59,130,246,0.3); border-radius:12px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; cursor:pointer; margin-bottom:16px; transition:background 0.2s, border-color 0.2s; }
        .photo-zone:active { background:rgba(59,130,246,0.15); border-color: rgba(59,130,246,0.5); }
        .sig-area { border:1px solid var(--outline); border-radius:12px; overflow:hidden; margin-bottom:8px; background:#fff; box-shadow: inset 0 2px 8px rgba(0,0,0,0.05); }
        .sig-clear { font-size:12px; color:#94a3b8; cursor:pointer; background:none; border:none; text-align:right; display:block; width:100%; margin-bottom:16px; padding: 4px; }
        .complete-btn { width:100%; height:60px; background:var(--success); color:#fff; border:none; border-radius:12px; font-size:18px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow: 0 8px 24px rgba(34,197,94,0.3); transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .complete-btn:active:not(:disabled) { transform: scale(0.96); box-shadow: 0 4px 12px rgba(34,197,94,0.4); }
        .complete-btn:disabled { opacity:0.5; cursor:not-allowed; box-shadow: none; }
        
        /* Exception Modal */
        .exc-sheet { background:var(--surf-c); border-radius:20px 20px 0 0; padding:20px; margin-top:auto; border-top:1px solid var(--outline); box-shadow: 0 -8px 32px rgba(0,0,0,0.6); animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .exc-opt { display:flex; align-items:center; gap:12px; padding:14px 16px; background:var(--surf); border:1px solid var(--outline); border-radius:10px; margin-bottom:8px; cursor:pointer; transition: all 0.15s; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .exc-opt:active { transform: scale(0.98); }
        .exc-opt.selected { border-color:var(--crit); background:rgba(239,68,68,0.05); box-shadow: 0 2px 8px rgba(239,68,68,0.2); }
        .exc-icon { width:40px; height:40px; border-radius:20px; background:rgba(239,68,68,0.1); display:flex; align-items:center; justify-content:center; color:var(--crit); flex-shrink:0; }
        .exc-label { font-size:15px; font-weight:500; }
        .submit-exc { width:100%; height:56px; background:var(--crit); color:#fff; border:none; border-radius:12px; font-size:16px; font-weight:600; cursor:pointer; margin-top:16px; box-shadow: 0 8px 24px rgba(239,68,68,0.3); transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .submit-exc:active:not(:disabled) { transform: scale(0.96); box-shadow: 0 4px 12px rgba(239,68,68,0.4); }
        .submit-exc:disabled { opacity: 0.5; box-shadow: none; cursor: not-allowed; }
    </style>
</head>
<body x-data="driverApp()">

    <header class="top-bar">
        <span class="title">{{ __('Route Manifest') }}</span>
        <div style="display:flex; align-items:center; gap:8px;">
            <span class="sync-badge" :class="isOnline ? 'online' : 'offline'" @click="syncData()" style="cursor:pointer">
                <span class="material-symbols-outlined" style="font-size:14px" x-text="isOnline ? 'cloud_done' : 'cloud_off'"></span>
                <span x-text="isOnline ? '{{ __('Synced') }}' : (pendingSync + ' {{ __('pending') }}')"></span>
            </span>
            <!-- Language Switcher -->
            <div style="display:flex; gap: 8px; margin-right: 12px;">
                <a href="{{ route('locale.set', 'en') }}" style="font-size: 12px; text-decoration: none; color: {{ session('locale') == 'en' || !session('locale') ? '#1565C0; font-weight: bold;' : '#74777F;' }}">EN</a>
                <a href="{{ route('locale.set', 'si') }}" style="font-size: 12px; text-decoration: none; color: {{ session('locale') == 'si' ? '#1565C0; font-weight: bold;' : '#74777F;' }}">සිං</a>
                <a href="{{ route('locale.set', 'ta') }}" style="font-size: 12px; text-decoration: none; color: {{ session('locale') == 'ta' ? '#1565C0; font-weight: bold;' : '#74777F;' }}">தமிழ்</a>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin:0">
                @csrf
                <button type="submit" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:13px">{{ __('Exit') }}</button>
            </form>
        </div>
    </header>

    <main>
        @if(session('success'))
        <div style="background:rgba(34,197,94,0.1); border-left:4px solid var(--success); padding:12px 16px; font-size:14px; color:#86efac; display:flex; gap:8px; margin-bottom:16px; border-radius:4px;">
            <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @if(session('warning'))
        <div style="background:rgba(245,158,11,0.1); border-left:4px solid var(--warn); padding:12px 16px; font-size:14px; color:#fcd34d; display:flex; gap:8px; margin-bottom:16px; border-radius:4px;">
            <span class="material-symbols-outlined" style="font-size:18px">warning</span>
            {{ session('warning') }}
        </div>
        @endif

        @if($trip && $routeLegs->count() > 0)
        @php $nextLeg = $routeLegs->first(); @endphp

        <!-- Next Stop Card -->
        <div class="next-stop-card">
            <div>
                <span class="stop-tag">
                    <span class="material-symbols-outlined" style="font-size:14px">navigation</span>
                    {{ __('Next Stop') }}
                </span>
                <span class="stop-counter">{{ __('Stop') }} {{ $completedLegs->count() + 1 }} {{ __('of') }} {{ $routeLegs->count() + $completedLegs->count() }}</span>
            </div>

            <h2 class="outlet-name">{{ $nextLeg->to_outlet }}</h2>
            
            @php 
                $order = \App\Models\Order::where('outlet_id', $nextLeg->to_outlet)->where('trip_id', $trip->trip_id)->first(); 
                $dockType = (ord(substr($nextLeg->to_outlet, -1)) % 2 == 0) ? 'Rear Dock' : 'Curbside Front';
            @endphp
            
            <div class="outlet-meta">
                <span style="color:var(--primary); font-weight:600;"><span class="material-symbols-outlined" style="font-size:16px">inventory_2</span>
                    {{ $order ? $order->order_units : '--' }} {{ __('units to drop') }}
                </span>
                <span><span class="material-symbols-outlined" style="font-size:16px">local_shipping</span>
                    {{ __('Access:') }} {{ $dockType }}
                </span>
                <span style="width:100%;"></span> <!-- Line break equivalent -->
                <span><span class="material-symbols-outlined" style="font-size:14px">schedule</span>
                    {{ __('ETA') }} {{ $nextLeg->planned_arrival_time ? \Carbon\Carbon::parse($nextLeg->planned_arrival_time)->format('H:i') : '—' }}
                </span>
                <span><span class="material-symbols-outlined" style="font-size:14px">straighten</span>
                    {{ number_format($nextLeg->distance_km ?? 0, 1) }} km
                </span>
            </div>

            <div class="window-bar">
                <span class="window-label">{{ __('Delivery window') }}</span>
                <span class="window-time">
                    {{ $nextLeg->planned_arrival_time ? \Carbon\Carbon::parse($nextLeg->planned_arrival_time)->subMinutes(15)->format('H:i') : '—' }} –
                    {{ $nextLeg->planned_arrival_time ? \Carbon\Carbon::parse($nextLeg->planned_arrival_time)->addMinutes(30)->format('H:i') : '—' }}
                </span>
            </div>

            <div class="stop-actions">
                <a href="geo:6.9271,79.8612?q={{ urlencode($nextLeg->to_outlet) }}" class="nav-btn">
                    <span class="material-symbols-outlined">navigation</span>
                    {{ __('Navigate') }}
                </a>
                <button class="issue-btn" @click="showException = true">
                    <span class="material-symbols-outlined">warning</span>
                    {{ __('Issue') }}
                </button>
            </div>
            <button class="arrived-btn" @click="markArrived('{{ $nextLeg->leg_id }}')">
                <span x-show="!arriving" class="material-symbols-outlined">check_circle</span>
                <span x-text="arriving ? '{{ __('Recording…') }}' : '{{ __('Arrived') }}'"></span>
            </button>
        </div>

        <!-- Remaining Stops -->
        @if($routeLegs->count() > 1)
        <div class="section-label">{{ __('Remaining Stops') }}</div>
        @foreach($routeLegs->slice(1) as $leg)
        <div class="stop-row">
            <div>
                <div class="stop-name">{{ $leg->to_outlet }}</div>
                <div class="stop-time">{{ __('ETA') }} {{ $leg->planned_arrival_time ? \Carbon\Carbon::parse($leg->planned_arrival_time)->format('H:i') : '—' }}</div>
            </div>
            <span class="badge badge-primary">{{ __('Upcoming') }}</span>
        </div>
        @endforeach
        @endif

        <!-- Completed Stops -->
        @if($completedLegs->count() > 0)
        <div class="section-label">{{ __('Completed') }} ({{ $completedLegs->count() }})</div>
        @foreach($completedLegs as $leg)
        <div class="stop-row completed">
            <div>
                <div class="stop-name">{{ $leg->to_outlet }}</div>
                <div class="stop-time">{{ __('Arrived') }} {{ \Carbon\Carbon::parse($leg->actual_arrival_time)->format('H:i') }}</div>
            </div>
            <span class="badge badge-success">{{ __('Done') }}</span>
        </div>
        @endforeach
        @endif

        @elseif($trip)
        <div class="empty-state">
            <span class="material-symbols-outlined" style="font-size:64px; color:#94a3b8">flag</span>
            <h3 style="font-size:20px; font-weight:600">{{ __('Route Complete') }}</h3>
            <p style="color:#94a3b8; font-size:14px">{{ __('All stops completed. Return to depot.') }}</p>
        </div>
        @else
        <div class="empty-state">
            <span class="material-symbols-outlined" style="font-size:64px; color:#94a3b8">local_shipping</span>
            <h3 style="font-size:20px; font-weight:600">{{ __('No Trip Assigned') }}</h3>
            <p style="color:#94a3b8; font-size:14px">{{ __('Dispatcher has not assigned a trip for today.') }}</p>
        </div>
        @endif

        <!-- Utility Action Bar (Break, Breakdown, Trip End) -->
        <div style="display:flex; gap:8px; margin-top:24px;">
            <a href="{{ route('driver.break') }}" class="nav-btn" style="height:48px; font-size:13px;">
                <span class="material-symbols-outlined" style="font-size:18px">local_cafe</span> {{ __('Break') }}
            </a>
            <a href="{{ route('driver.breakdown') }}" class="nav-btn" style="height:48px; font-size:13px; color:#fca5a5; border-color:rgba(239,68,68,0.3); background:rgba(239,68,68,0.1);">
                <span class="material-symbols-outlined" style="font-size:18px">car_crash</span> {{ __('SOS') }}
            </a>
            <a href="{{ route('driver.tripEnd') }}" class="nav-btn" style="height:48px; font-size:13px; color:#86efac; border-color:rgba(34,197,94,0.3); background:rgba(34,197,94,0.1);">
                <span class="material-symbols-outlined" style="font-size:18px">power_settings_new</span> {{ __('End') }}
            </a>
        </div>

    </main>

    <!-- ── Proof of Delivery Modal ─────────────────────── -->
    <div class="modal-overlay" :class="{ 'open': showPoD }">
        <div class="modal-sheet">
            <div class="modal-header">
                <span class="modal-title">Proof of Delivery</span>
                <button class="modal-close" @click="showPoD = false">✕</button>
            </div>

            <input type="file" accept="image/*" capture="environment" id="camera-input" style="display:none" @change="handlePhoto">
            <div style="margin-bottom:16px">
                <label class="fl">1. Photo Evidence</label>
                <div class="photo-zone" onclick="document.getElementById('camera-input').click()">
                    <span class="material-symbols-outlined" style="font-size:32px; color:#3b82f6">photo_camera</span>
                    <span style="font-size:14px; color:#94a3b8" x-text="photoTaken ? '✓ Photo captured — tap to retake' : 'Tap to open camera'"></span>
                </div>
            </div>

            <div style="margin-bottom:4px">
                <label class="fl">2. Signature</label>
                <div class="sig-area">
                    <canvas id="sig-pad" style="width:100%; height:130px; display:block"></canvas>
                </div>
                <button class="sig-clear" @click="clearSig()">Clear signature</button>
            </div>

            <div style="margin-bottom:16px">
                <label class="fl">3. Units Delivered</label>
                <input type="number" id="confirmed_units" style="width:100%; border:1px solid var(--outline); border-radius:8px; padding:12px; font-size:16px; background:var(--surf); color:var(--on-surf); box-sizing:border-box">
            </div>

            <button class="complete-btn" @click="submitDelivery()" :disabled="isSubmitting">
                <span x-show="!isSubmitting" class="material-symbols-outlined">task_alt</span>
                <span x-text="isSubmitting ? 'Submitting…' : 'Complete Delivery'"></span>
            </button>
            <p style="text-align:center; font-size:11px; color:#94a3b8; margin-top:12px">GPS stamp captured automatically</p>
        </div>
    </div>

    <!-- ── Exception Modal ─────────────────────────────── -->
    <div class="modal-overlay" :class="{ 'open': showException }" @click.self="showException = false">
        <div class="exc-sheet">
            <div class="modal-header">
                <span class="modal-title" style="color:#fca5a5">Report Exception</span>
                <button class="modal-close" @click="showException = false">✕</button>
            </div>
            <form action="{{ route('driver.exception') }}" method="POST">
                @csrf
                <input type="hidden" name="leg_id" value="{{ isset($nextLeg) ? $nextLeg->leg_id : '' }}">

                @php
                    $exceptions = [
                        ['value' => 'Outlet closed', 'icon' => 'store', 'label' => 'Outlet Closed'],
                        ['value' => 'No receiving staff', 'icon' => 'person_off', 'label' => 'No Receiving Staff'],
                        ['value' => 'Access blocked', 'icon' => 'block', 'label' => 'Access Blocked'],
                        ['value' => 'Items damaged in transit', 'icon' => 'broken_image', 'label' => 'Items Damaged'],
                        ['value' => 'Vehicle breakdown', 'icon' => 'car_crash', 'label' => 'Vehicle Breakdown'],
                    ];
                @endphp
                @foreach($exceptions as $ex)
                <label class="exc-opt">
                    <input type="radio" name="reason" value="{{ $ex['value'] }}" style="display:none">
                    <div class="exc-icon"><span class="material-symbols-outlined">{{ $ex['icon'] }}</span></div>
                    <span class="exc-label">{{ $ex['label'] }}</span>
                </label>
                @endforeach

                <button type="submit" class="submit-exc">Log Exception</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('driverApp', () => ({
                isOnline: navigator.onLine,
                pendingSync: 0,
                showPoD: false,
                showException: false,
                photoTaken: false,
                isSubmitting: false,
                arriving: false,
                signaturePad: null,
                currentLegId: null,

                init() {
                    window.addEventListener('online',  () => this.isOnline = true);
                    window.addEventListener('offline', () => this.isOnline = false);

                    this.$watch('showPoD', val => {
                        if (val && !this.signaturePad) {
                            setTimeout(() => {
                                const canvas = document.getElementById('sig-pad');
                                if (!canvas) return;
                                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                canvas.width  = canvas.offsetWidth  * ratio;
                                canvas.height = canvas.offsetHeight * ratio;
                                canvas.getContext('2d').scale(ratio, ratio);
                                this.signaturePad = new SignaturePad(canvas, { penColor: '#000', backgroundColor: '#fff' });
                            }, 100);
                        }
                    });
                },

                markArrived(legId) {
                    this.arriving = true;
                    this.currentLegId = legId;

                    const doArrival = () => {
                        fetch('{{ route('driver.arrival') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ leg_id: legId })
                        }).then(() => {
                            this.arriving = false;
                            this.showPoD = true;
                        }).catch(() => {
                            // Offline: queue it
                            this.pendingSync++;
                            this.arriving = false;
                            this.showPoD = true;
                        });
                    };

                    if (this.isOnline) {
                        doArrival();
                    } else {
                        // Offline: store in localStorage, open PoD anyway
                        const queue = JSON.parse(localStorage.getItem('pendingArrivals') || '[]');
                        queue.push({ legId, ts: Date.now() });
                        localStorage.setItem('pendingArrivals', JSON.stringify(queue));
                        this.pendingSync = queue.length;
                        this.arriving = false;
                        this.showPoD = true;
                    }
                },

                handlePhoto(e) {
                    this.photoTaken = e.target.files.length > 0;
                },

                clearSig() {
                    this.signaturePad?.clear();
                },

                syncData() {
                    if (!this.isOnline) return;
                    const queue = JSON.parse(localStorage.getItem('pendingArrivals') || '[]');
                    if (!queue.length) return;

                    Promise.all(queue.map(item =>
                        fetch('{{ route('driver.arrival') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ leg_id: item.legId })
                        })
                    )).then(() => {
                        localStorage.removeItem('pendingArrivals');
                        this.pendingSync = 0;
                        location.reload();
                    });
                },

                submitDelivery() {
                    this.isSubmitting = true;
                    const sig = this.signaturePad?.isEmpty() ? null : this.signaturePad?.toDataURL('image/png');
                    const units = document.getElementById('confirmed_units').value;

                    navigator.geolocation.getCurrentPosition(
                        pos => this._finalSubmit(sig, units, pos.coords.latitude, pos.coords.longitude),
                        ()  => this._finalSubmit(sig, units, null, null),
                        { enableHighAccuracy: true, timeout: 4000 }
                    );
                },

                _finalSubmit(sig, units, lat, lng) {
                    const payload = {
                        trip_id: '{{ $trip?->trip_id ?? "" }}',
                        leg_id: this.currentLegId,
                        signature: sig,
                        confirmed_units: units,
                        lat, lng
                    };

                    if (!this.isOnline) {
                        const q = JSON.parse(localStorage.getItem('pendingDeliveries') || '[]');
                        q.push({ ...payload, ts: Date.now() });
                        localStorage.setItem('pendingDeliveries', JSON.stringify(q));
                        this.pendingSync += q.length;
                        this.isSubmitting = false;
                        this.showPoD = false;
                        alert('Saved offline. Will sync when back online.');
                        location.reload();
                        return;
                    }

                    fetch('{{ route('driver.confirm') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(payload)
                    }).then(r => r.json()).then(() => {
                        this.isSubmitting = false;
                        this.showPoD = false;
                        location.reload();
                    }).catch(() => {
                        this.isSubmitting = false;
                        alert('Submit failed. Try again.');
                    });
                }
            }));
        });

        // Select exception radio via div click
        document.addEventListener('click', e => {
            const opt = e.target.closest('.exc-opt');
            if (opt) {
                document.querySelectorAll('.exc-opt').forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
                opt.querySelector('input[type=radio]').checked = true;
            }
        });
    </script>
</body>
</html>
