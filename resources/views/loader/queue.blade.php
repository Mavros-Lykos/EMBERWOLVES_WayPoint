<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Loading Bay — Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --surf:#0f172a; --surf-c:#1e293b; --on-surf:#e2e8f0; --outline:rgba(148,163,184,0.2); --primary:#3b82f6; --success:#22c55e; --warn:#f59e0b; --crit:#ef4444; }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Roboto',sans-serif; display:flex; flex-direction:column; min-height:100vh; max-width:820px; margin:0 auto; background:var(--surf); color:var(--on-surf); }
        
        /* A11y Focus States */
        button:focus-visible, a:focus-visible, input:focus-visible, textarea:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }

        /* Top Bar */
        .top-bar { height:64px; padding:0 24px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--outline); background:var(--surf-c); box-shadow: 0 4px 16px rgba(0,0,0,0.4); position:relative; z-index:20; }
        .page-title { font-size:20px; font-weight:500; }
        .v-id { font-size:14px; color:#94a3b8; display:flex; align-items:center; gap:8px; }
        .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:500; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .badge-success { background:rgba(34,197,94,0.15); color:#86efac; }
        .badge-warn { background:rgba(245,158,11,0.15); color:#fcd34d; }
        .badge-info { background:rgba(59,130,246,0.15); color:#93c5fd; }
        
        /* Body */
        .page-body { flex:1; overflow-y:auto; padding:24px; display:flex; flex-direction:column; gap:24px; }
        
        /* Instruction Banner */
        .instruction-banner { background:rgba(59,130,246,0.1); border:1px solid rgba(59,130,246,0.3); color:#93c5fd; padding:16px 24px; border-radius:12px; display:flex; align-items:center; gap:12px; font-size:14px; box-shadow: 0 4px 12px rgba(59,130,246,0.1); }
        .section-label { font-size:10px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:12px; }
        
        /* LIFO Load List */
        .load-list { display:flex; flex-direction:column; gap:12px; position:relative; }
        .load-list::before { content:''; position:absolute; left:32px; top:32px; bottom:32px; width:2px; background:var(--outline); z-index:0; }
        .load-step { display:flex; align-items:stretch; gap:16px; position:relative; z-index:1; }
        .step-marker { width:64px; display:flex; flex-direction:column; align-items:center; gap:4px; }
        .step-num { width:48px; height:48px; border-radius:24px; background:var(--surf-c); color:#94a3b8; display:flex; align-items:center; justify-content:center; font-size:18px; font-weight:500; border:2px solid var(--outline); z-index:2; transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 4px rgba(0,0,0,0.3); }
        .step-label { font-size:10px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; }
        .load-card { flex:1; background:var(--surf-c); border:1px solid var(--outline); border-radius:12px; padding:20px; transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        
        /* States */
        .load-step.active .step-num { background:var(--primary); color:#fff; border-color:var(--primary); box-shadow: 0 4px 12px rgba(59,130,246,0.4), 0 0 0 4px rgba(59,130,246,0.2); transform: scale(1.1); }
        .load-step.active .load-card { border-color:var(--primary); border-width:2px; box-shadow: 0 8px 24px rgba(59,130,246,0.15), 0 4px 12px rgba(0,0,0,0.3); transform: translateY(-2px); }
        .load-step.done .step-num { background:var(--success); color:#fff; border-color:var(--success); box-shadow: 0 2px 8px rgba(34,197,94,0.3); }
        .load-step.done .load-card { opacity:0.7; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transform: translateY(0); }
        .load-step.done .card-action { display:none; }
        .load-step.done .card-done { display:flex; }
        
        .card-done { display:none; align-items:center; gap:4px; color:var(--success); font-size:13px; margin-top:16px; font-weight:500; }
        .c-head { display:flex; justify-content:space-between; margin-bottom:8px; }
        .c-dest { font-size:16px; font-weight:600; }
        .c-order { font-size:12px; color:#94a3b8; }
        .c-details { display:flex; gap:10px; margin-bottom:16px; flex-wrap:wrap; }
        .pill { display:flex; align-items:center; gap:4px; background:rgba(148,163,184,0.1); padding:4px 10px; border-radius:8px; font-size:12px; color:#e2e8f0; font-weight:500; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        
        /* Action buttons inside card */
        .card-action .confirm-btn { width:100%; height:56px; background:var(--primary); color:#fff; border:none; border-radius:12px; font-size:16px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow: 0 4px 12px rgba(59,130,246,0.3); transition:all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-action .confirm-btn:active { transform: scale(0.97); box-shadow: 0 2px 8px rgba(59,130,246,0.4); }
        .card-action .flag-btn { width:100%; height:48px; background:transparent; color:var(--warn); border:1px solid rgba(245,158,11,0.4); border-radius:12px; font-size:14px; font-weight:500; cursor:pointer; margin-top:8px; display:flex; align-items:center; justify-content:center; gap:6px; transition:all 0.2s; }
        .card-action .flag-btn:active { transform: scale(0.97); background:rgba(245,158,11,0.1); }
        
        /* Upcoming */
        .trip-card { background:var(--surf-c); border:1px solid var(--outline); border-radius:12px; padding:16px; display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition:transform 0.2s; }
        .trip-card:active { transform: scale(0.98); }
        
        /* Action Row (Bottom) */
        .action-row { padding:24px; border-top:1px solid var(--outline); display:flex; gap:12px; background:var(--surf-c); box-shadow: 0 -4px 16px rgba(0,0,0,0.4); position:relative; z-index:20; }
        .action-row .primary-btn { flex:1; height:64px; background:var(--success); color:#fff; border:none; border-radius:12px; font-size:18px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow: 0 8px 24px rgba(34,197,94,0.3); transition:all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .action-row .primary-btn:active:not(:disabled) { transform: scale(0.97); box-shadow: 0 4px 12px rgba(34,197,94,0.4); }
        .action-row .primary-btn:disabled { opacity: 0.5; box-shadow: none; cursor: not-allowed; }
        
        /* Modal */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:50; align-items:flex-end; justify-content:center; backdrop-filter: blur(4px); }
        .modal-overlay.open { display:flex; }
        .modal-sheet { background:var(--surf-c); border-radius:20px 20px 0 0; padding:24px; width:100%; max-width:560px; border-top:1px solid var(--outline); box-shadow: 0 -8px 32px rgba(0,0,0,0.6); animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .modal-title { font-size:18px; font-weight:600; }
        .modal-close { background:none; border:none; font-size:24px; cursor:pointer; color:#94a3b8; transition:color 0.2s; padding:4px; border-radius:50%; }
        .modal-close:active { background: rgba(148,163,184,0.2); }
        label.field-label { display:block; font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:6px; }
        .field-input { width:100%; border:1px solid var(--outline); border-radius:10px; padding:16px; font-size:15px; outline:none; background:var(--surf); color:var(--on-surf); box-sizing:border-box; transition:border-color 0.2s, box-shadow 0.2s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); }
        .field-input:focus { border-color:var(--primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.2), inset 0 2px 4px rgba(0,0,0,0.1); }
        .btn-crit { width:100%; height:56px; background:var(--crit); color:#fff; border:none; border-radius:12px; font-size:16px; font-weight:600; cursor:pointer; margin-top:24px; box-shadow: 0 4px 12px rgba(239,68,68,0.3); transition:all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .btn-crit:active { transform: scale(0.97); box-shadow: 0 2px 8px rgba(239,68,68,0.4); }
        .seal-form { display:none; flex-direction:column; gap:16px; }
        .seal-form.show { display:flex; }
        @media(max-width:640px) { .top-bar { padding:0 16px; } .page-body { padding:16px; } }
    </style>
</head>
<body x-data="loaderApp()">

    <header class="top-bar">
        <div>
            <div class="page-title">{{ __('Loading Bay') }}</div>
            <div class="v-id">
                @if($currentTrip)
                    <span class="badge badge-info">{{ $currentTrip->vehicle_id }}</span>
                    {{ __('Trip') }} {{ $currentTrip->trip_number }} · {{ $currentTrip->brand }} · {{ $currentTrip->district }}
                @else
                    <span class="badge badge-warn">{{ __('No active trip') }}</span>
                @endif
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="display:flex; align-items:center; gap: 8px;">
                @include('partials.notifications')
                @include('partials.settings')
            </div>
            <span class="badge badge-success">
                <span class="material-symbols-outlined" style="font-size:14px">cloud_done</span>
                {{ __('Online') }}
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:13px">{{ __('Exit') }}</button>
            </form>
        </div>
    </header>



    <div class="page-body">

        @if($currentTrip)

        <!-- Instruction Banner -->
        <div class="instruction-banner">
            <span class="material-symbols-outlined">info</span>
            <span><strong>{{ __('Reverse LIFO Sequence:') }}</strong> {{ __('Load the') }} <u>{{ __('last stop first') }}</u> — {{ __('it sits deepest in the vehicle and exits last.') }}</span>
        </div>

        <!-- LIFO Checklist -->
        <div>
            <div class="section-label">{{ __('Loading Checklist — Load in this order') }}</div>
            <div class="load-list" id="loadList">
                @forelse($lifoLegs as $i => $leg)
                @php
                    $order = $orders->firstWhere('trip_id', $currentTrip->trip_id);
                    $isFirst = $i === 0;
                    $stepState = $isFirst ? 'active' : '';
                @endphp
                <div class="load-step {{ $stepState }}" id="step-{{ $i }}" data-index="{{ $i }}">
                    <div class="step-marker">
                        <div class="step-num">
                            @if($stepState === 'done')
                                <span class="material-symbols-outlined">check</span>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <div class="step-label">{{ __('Load') }} {{ $i + 1 }}</div>
                    </div>
                    <div class="load-card">
                        <div class="c-head">
                            <span class="c-dest">{{ $leg->to_outlet }}</span>
                            <span class="c-order">{{ __('Stop') }} {{ (count($lifoLegs) - $i) }} ({{ __('delivers') }} {{ $leg->seq + 1 }}{{ ['st','nd','rd'][$leg->seq] ?? 'th' }})</span>
                        </div>
                        <div class="c-details">
                            @if($order)
                                <div class="pill">{{ $order->order_units }} {{ __('units') }}</div>
                                <div class="pill">{{ number_format($order->order_weight_kg, 0) }} kg</div>
                                <div class="pill">
                                    @if($order->temp_requirement === 'chilled')
                                        <span class="material-symbols-outlined" style="font-size:14px">ac_unit</span>
                                    @endif
                                    {{ __(ucfirst($order->temp_requirement)) }}
                                </div>
                            @endif
                        </div>
                        <div class="card-action">
                            <button class="confirm-btn" onclick="confirmStep({{ $i }}, '{{ $leg->leg_id }}')">
                                <span class="material-symbols-outlined">check_circle</span>
                                {{ __('Confirm Loaded') }}
                            </button>
                            <button class="flag-btn" @click="shortfallLeg = '{{ $leg->leg_id }}'; shortfallOrder = '{{ $order?->order_ref }}'; showShortfall = true">
                                <span class="material-symbols-outlined" style="font-size:16px">warning</span>
                                {{ __('Flag Shortfall') }}
                            </button>
                        </div>
                        <div class="card-done">
                            <span class="material-symbols-outlined" style="font-size:18px">verified</span>
                            {{ __('Loaded') }} ✓
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align:center; padding:40px; color:#94a3b8">
                    <span class="material-symbols-outlined" style="font-size:48px; display:block; margin-bottom:12px">inventory_2</span>
                    {{ __('No route legs found for this trip.') }}
                </div>
                @endforelse
            </div>
        </div>

        @else
        <!-- No Active Trip -->
        <div style="text-align:center; padding:60px 20px; background:var(--surf-c); border:1px solid var(--outline); border-radius:16px; box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
            <span class="material-symbols-outlined" style="font-size:64px; color:#94a3b8; display:block; margin-bottom:16px">local_shipping</span>
            <p style="font-size:18px; font-weight:500; margin-bottom:8px">{{ __('No Active Trip') }}</p>
            <p style="font-size:14px; color:#94a3b8">{{ __('Waiting for dispatcher to finalize allocation and push loading list.') }}</p>
        </div>
        @endif

        <!-- Upcoming Trips -->
        @if($upcomingTrips->count() > 0)
        <div>
            <div class="section-label">{{ __('Upcoming Queue') }}</div>
            @foreach($upcomingTrips as $t)
            <div class="trip-card">
                <div>
                    <div style="font-weight:500; font-size:15px">{{ $t->vehicle_id }} · {{ $t->brand }}</div>
                    <div style="font-size:12px; color:#94a3b8; margin-top:2px">{{ $t->district }} · {{ __('Trip') }} {{ $t->trip_number }}</div>
                </div>
                <span class="badge badge-info">{{ __('Planned') }}</span>
            </div>
            @endforeach
        </div>
        @endif

    </div>

    <!-- Seal + Complete Loading -->
    @if($currentTrip)
    <div class="action-row">
        <button class="primary-btn" @click="showSeal = true">
            <span class="material-symbols-outlined">lock</span>
            {{ __('Complete Loading & Seal') }}
        </button>
    </div>
    @endif

    <!-- ── Shortfall Modal ───────────────────────── -->
    <div class="modal-overlay" :class="{ 'open': showShortfall }" @click.self="showShortfall = false">
        <div class="modal-sheet">
            <div class="modal-header">
                <span class="modal-title" style="color:#f59e0b">⚠ {{ __('Flag Shortfall') }}</span>
                <button class="modal-close" @click="showShortfall = false">✕</button>
            </div>
            <form action="{{ route('loader.exception') }}" method="POST">
                @csrf
                <input type="hidden" name="trip_id" value="{{ $currentTrip?->trip_id }}">
                <input type="hidden" name="order_ref" :value="shortfallOrder">
                <div style="margin-bottom:16px">
                    <label class="field-label">{{ __('Reason') }}</label>
                    <select name="reason" class="field-input">
                        <option>{{ __('Warehouse out of stock') }}</option>
                        <option>{{ __('Packaging damaged — cannot load') }}</option>
                        <option>{{ __('Wrong items in storage') }}</option>
                        <option>{{ __('Temperature breach in cold room') }}</option>
                    </select>
                </div>
                <div style="margin-bottom:16px">
                    <label class="field-label">{{ __('Actual Quantity Loaded') }}</label>
                    <input type="number" name="quantity" class="field-input" value="0" min="0">
                </div>
                <button type="submit" class="btn-crit">{{ __('Log Shortfall — Notify Dispatcher') }}</button>
            </form>
        </div>
    </div>

    <!-- ── Seal Protocol Modal ───────────────────── -->
    <div class="modal-overlay" :class="{ 'open': showSeal }" @click.self="showSeal = false">
        <div class="modal-sheet">
            <div class="modal-header">
                <span class="modal-title">
                    <span class="material-symbols-outlined" style="font-size:20px; vertical-align:middle">lock</span>
                    {{ __('Seal & Release Vehicle') }}
                </span>
                <button class="modal-close" @click="showSeal = false">✕</button>
            </div>
            <p style="font-size:14px; color:#94a3b8; margin-bottom:20px">{{ __('Enter the tamper-evident seal number applied to the vehicle door. This establishes chain of custody.') }}</p>
            <form action="{{ route('loader.dispatch') }}" method="POST">
                @csrf
                <input type="hidden" name="trip_id" value="{{ $currentTrip?->trip_id }}">
                <div style="margin-bottom:24px">
                    <label class="field-label">{{ __('Seal Number') }}</label>
                    <input type="text" name="seal_number" class="field-input" placeholder="e.g. SL-9942" required style="font-size:20px; font-family:monospace; text-align:center; letter-spacing:0.1em">
                </div>
                <button type="submit" class="primary-btn" style="width:100%; height:60px; border-radius:12px; border:none; font-size:16px; cursor:pointer">
                    <span class="material-symbols-outlined">local_shipping</span>
                    {{ __('Dispatch Vehicle') }}
                </button>
            </form>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loaderApp', () => ({
                showShortfall: false,
                showSeal: false,
                shortfallLeg: null,
                shortfallOrder: null,
            }));
        });

        function confirmStep(index, legId) {
            fetch('/loader/confirm-item', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ leg_id: legId })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    const steps = document.querySelectorAll('.load-step');
                    steps[index].classList.remove('active');
                    steps[index].classList.add('done');
                    // Set check icon
                    steps[index].querySelector('.step-num').innerHTML = '<span class="material-symbols-outlined">check</span>';

                    // Activate next step
                    if (steps[index + 1]) {
                        steps[index + 1].classList.add('active');
                    }
                }
            }).catch(err => {
                console.error("Failed to confirm item:", err);
                alert("Failed to confirm load. Please check your connection.");
            });
        }
    </script>
</body>
</html>
