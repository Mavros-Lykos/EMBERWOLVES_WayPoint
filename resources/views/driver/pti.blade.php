<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pre-Trip Inspection — Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --surf:#0f172a; --surf-c:#1e293b; --on-surf:#e2e8f0; --outline:rgba(148,163,184,0.2); --primary:#3b82f6; --success:#22c55e; --warn:#f59e0b; --crit:#ef4444; }
        * { box-sizing:border-box; margin:0; padding:0; }
        html { font-size:16px; }
        body { font-family:'Roboto',sans-serif; display:flex; flex-direction:column; min-height:100vh; max-width:420px; margin:0 auto; background:var(--surf); color:var(--on-surf); }
        
        /* A11y Focus States */
        button:focus-visible, a:focus-visible, input:focus-visible, textarea:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }

        .mobile-top-bar { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1px solid var(--outline); background:var(--surf-c); box-shadow: 0 4px 16px rgba(0,0,0,0.4); z-index: 10; position: relative; }
        .title { font-size:18px; font-weight:500; }
        .sync-badge { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:999px; font-size:12px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .online { background:rgba(34,197,94,0.1); color:#86efac; border: 1px solid rgba(34,197,94,0.3); }
        
        .vehicle-bar { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:rgba(59,130,246,0.05); border-bottom:1px solid var(--outline); }
        .vehicle-id { font-size:14px; font-weight:500; color: var(--primary); }
        .driver-name { font-size:12px; color:#94a3b8; }
        
        /* Checklist */
        .checklist { flex:1; overflow-y:auto; padding:16px; display:flex; flex-direction:column; gap:12px; }
        .check-item { display:flex; align-items:center; gap:16px; min-height:72px; padding:16px; background:var(--surf-c); border:1px solid var(--outline); border-radius:16px; cursor:pointer; transition:all 0.2s cubic-bezier(0.4, 0, 0.2, 1); -webkit-tap-highlight-color:transparent; user-select:none; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .check-item:active { transform:scale(0.96); box-shadow: 0 2px 6px rgba(0,0,0,0.3); }
        .check-item.checked { background:rgba(34,197,94,0.08); border-color:rgba(34,197,94,0.4); box-shadow: 0 4px 12px rgba(34,197,94,0.15); }
        .check-icon { width:44px; height:44px; border-radius:22px; border:2px solid #94a3b8; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:transparent; transition:all 0.2s; }
        .check-item.checked .check-icon { background:var(--success); border-color:var(--success); color:#fff; box-shadow: 0 2px 8px rgba(34,197,94,0.4); }
        .check-label { font-size:16px; color:var(--on-surf); flex:1; font-weight:500; }
        .check-detail { font-size:13px; color:#94a3b8; margin-top:4px; }
        
        /* Bottom Action */
        .bottom-action { padding:16px; border-top:1px solid var(--outline); display:flex; gap:12px; background:var(--surf-c); box-shadow: 0 -4px 16px rgba(0,0,0,0.4); }
        .defect-btn { height:64px; min-width:64px; padding:0 16px; background:rgba(239,68,68,0.05); border:1px solid rgba(239,68,68,0.4); color:var(--crit); border-radius:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition: all 0.2s; }
        .defect-btn:active { transform: scale(0.95); background: rgba(239,68,68,0.15); }
        .unlock-btn { flex:1; height:64px; background:var(--primary); color:#fff; border:none; border-radius:12px; font-size:18px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; opacity:0.4; pointer-events:none; transition:all 0.3s; }
        .unlock-btn.ready { opacity:1; pointer-events:auto; box-shadow: 0 8px 24px rgba(59,130,246,0.4); }
        .unlock-btn.ready:active { transform: scale(0.96); box-shadow: 0 4px 12px rgba(59,130,246,0.5); }
        
        .progress-bar { height:4px; background:rgba(148,163,184,0.2); margin:0; }
        .progress-fill { height:100%; background:var(--success); transition:width 0.4s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 0 8px var(--success); }
    </style>
</head>
<body x-data="ptiApp()">

    <header class="mobile-top-bar">
        <span class="title">Pre-Trip Inspection</span>
        <div style="display:flex; align-items:center; gap:8px;">
            <!-- Language Switcher -->
            <div style="display:flex; gap: 8px;">
                <a href="{{ route('locale.set', 'en') }}" style="font-size: 12px; text-decoration: none; color: {{ session('locale') == 'en' || !session('locale') ? '#1565C0; font-weight: bold;' : '#74777F;' }}">EN</a>
                <a href="{{ route('locale.set', 'si') }}" style="font-size: 12px; text-decoration: none; color: {{ session('locale') == 'si' ? '#1565C0; font-weight: bold;' : '#74777F;' }}">සිං</a>
                <a href="{{ route('locale.set', 'ta') }}" style="font-size: 12px; text-decoration: none; color: {{ session('locale') == 'ta' ? '#1565C0; font-weight: bold;' : '#74777F;' }}">தமிழ்</a>
            </div>
            <span class="sync-badge online">
                <span class="material-symbols-outlined" style="font-size:14px">cloud_done</span>
                Synced
            </span>
        </div>
    </header>

    <div class="progress-bar">
        <div class="progress-fill" :style="`width:${(checked / total) * 100}%`"></div>
    </div>

    <div class="vehicle-bar">
        <div>
            <div class="vehicle-id">{{ $vehicle?->vehicle_id ?? 'VEH-???' }} · {{ $vehicle?->type === 'truck' ? 'Truck' : 'Van' }}</div>
            <div class="driver-name">{{ $user->name }}</div>
        </div>
        <div style="font-size:13px; color:#94a3b8" x-text="`${checked} / ${total} checks`"></div>
    </div>

    <div class="checklist" role="list" aria-label="Pre-trip inspection checklist">

        <div class="check-item" :class="{ checked: c[0] }" role="listitem" tabindex="0"
             @click="toggle(0)" @keydown.enter="toggle(0)" @keydown.space.prevent="toggle(0)">
            <div class="check-icon"><span class="material-symbols-outlined">check</span></div>
            <div>
                <div class="check-label">Tires &amp; wheel nuts</div>
            </div>
        </div>

        <div class="check-item" :class="{ checked: c[1] }" role="listitem" tabindex="0"
             @click="toggle(1)" @keydown.enter="toggle(1)">
            <div class="check-icon"><span class="material-symbols-outlined">check</span></div>
            <div><div class="check-label">Foot &amp; hand brakes</div></div>
        </div>

        <div class="check-item" :class="{ checked: c[2] }" role="listitem" tabindex="0"
             @click="toggle(2)" @keydown.enter="toggle(2)">
            <div class="check-icon"><span class="material-symbols-outlined">check</span></div>
            <div><div class="check-label">Headlights &amp; hazard flashers</div></div>
        </div>

        <div class="check-item" :class="{ checked: c[3] }" role="listitem" tabindex="0"
             @click="toggle(3)" @keydown.enter="toggle(3)">
            <div class="check-icon"><span class="material-symbols-outlined">check</span></div>
            <div><div class="check-label">Wipers &amp; washer fluid</div></div>
        </div>

        <div class="check-item" :class="{ checked: c[4] }" role="listitem" tabindex="0"
             @click="toggle(4)" @keydown.enter="toggle(4)">
            <div class="check-icon"><span class="material-symbols-outlined">check</span></div>
            <div>
                <div class="check-label">Fuel level</div>
                <div class="check-detail">Must be ≥ 80% for today's route</div>
            </div>
        </div>

        @if($vehicle && $vehicle->temp === 'reefer')
        <div class="check-item" :class="{ checked: c[5] }" role="listitem" tabindex="0"
             @click="toggle(5)" @keydown.enter="toggle(5)">
            <div class="check-icon"><span class="material-symbols-outlined">check</span></div>
            <div>
                <div class="check-label">Reefer temperature</div>
                <div class="check-detail">Must be ≤ 4.0°C before loading</div>
            </div>
        </div>
        <div class="check-item" :class="{ checked: c[6] }" role="listitem" tabindex="0"
             @click="toggle(6)" @keydown.enter="toggle(6)">
            <div class="check-icon"><span class="material-symbols-outlined">check</span></div>
            <div>
                <div class="check-label">Rear tamper seal intact</div>
                <div class="check-detail">Verify seal number matches dispatch record</div>
            </div>
        </div>
        @endif

    </div>

    <div class="bottom-action">
        <button class="defect-btn" aria-label="Report defect"
                @click="alert('Defect logged. Bay supervisor notified.')">
            <span class="material-symbols-outlined">warning</span>
        </button>
        <a href="{{ route('driver.route') }}" class="unlock-btn" :class="{ ready: allClear }"
           :aria-disabled="!allClear" role="button">
            <span class="material-symbols-outlined">lock_open</span>
            Unlock Route
        </a>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ptiApp', () => ({
                total: {{ $vehicle && $vehicle->temp === 'reefer' ? 7 : 5 }},
                c: Array({{ $vehicle && $vehicle->temp === 'reefer' ? 7 : 5 }}).fill(false),

                get checked() { return this.c.filter(Boolean).length; },
                get allClear() { return this.checked === this.total; },

                toggle(i) {
                    this.c[i] = !this.c[i];
                }
            }));
        });
    </script>
</body>
</html>
