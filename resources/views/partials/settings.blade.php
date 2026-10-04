<!-- Settings Component (Theme & Language) -->
<style>
    /* Light Theme Overrides */
    html[data-theme="light"] {
        --surf: #f8fafc !important;
        --surf-c: #ffffff !important;
        --surf-cl: #f1f5f9 !important;
        --on-surf: #0f172a !important;
        --outline: rgba(15,23,42,0.1) !important;
    }
    html[data-theme="light"] body { background-color: var(--surf) !important; color: var(--on-surf) !important; }
    html[data-theme="light"] .nav-rail, html[data-theme="light"] .top-bar, html[data-theme="light"] .col, html[data-theme="light"] .load-card { background-color: var(--surf-c) !important; border-color: var(--outline) !important; }

    /* Dark Theme Overrides (For originally light pages) */
    html[data-theme="dark"] {
        --surf: #0f172a !important;
        --surf-c: #1e293b !important;
        --surf-cl: #0f172a !important;
        --on-surf: #e2e8f0 !important;
        --outline: rgba(148,163,184,0.2) !important;
    }
    html[data-theme="dark"] body { background-color: var(--surf) !important; color: var(--on-surf) !important; }
    html[data-theme="dark"] .nav-rail, html[data-theme="dark"] .top-bar, html[data-theme="dark"] .col, html[data-theme="dark"] .load-card { background-color: var(--surf-c) !important; border-color: var(--outline) !important; color: var(--on-surf) !important; }
    html[data-theme="dark"] .page-title, html[data-theme="dark"] .nav-item { color: var(--on-surf) !important; }
    
    .settings-container {
        position: relative;
        display: inline-block;
    }
    .settings-btn {
        background: transparent;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s;
    }
    .settings-btn:hover {
        background: rgba(148, 163, 184, 0.2);
    }
    
    .settings-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 8px;
        background: var(--surf-c, #ffffff);
        border: 1px solid var(--outline, rgba(148,163,184,0.2));
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        width: 220px;
        z-index: 1000;
        padding: 8px;
        display: none;
        color: var(--on-surf, #0f172a);
    }
    .settings-dropdown.open {
        display: block;
        animation: dropIn 0.2s ease-out;
    }
    
    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .settings-section-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        padding: 8px 12px 4px;
    }
    
    .settings-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        color: var(--on-surf);
        text-decoration: none;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
    }
    .settings-option:hover {
        background: rgba(148, 163, 184, 0.1);
    }
    .settings-option.active {
        background: rgba(59, 130, 246, 0.1);
        color: var(--primary, #3b82f6);
        font-weight: 600;
    }
    .settings-divider {
        height: 1px;
        background: var(--outline, rgba(148,163,184,0.2));
        margin: 8px 0;
    }
</style>

<!-- Theme initialization script (runs immediately to prevent flash) -->
<script>
    (function() {
        var savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    })();
</script>

<div class="settings-container" x-data="{ 
    open: false, 
    theme: localStorage.getItem('theme') || 'dark',
    toggle() { this.open = !this.open; },
    close() { this.open = false; },
    setTheme(newTheme) {
        this.theme = newTheme;
        localStorage.setItem('theme', newTheme);
        document.documentElement.setAttribute('data-theme', newTheme);
    }
}" @click.away="close()">
    <button class="settings-btn" @click="toggle()" aria-label="Settings">
        <span class="material-symbols-outlined">settings</span>
    </button>
    
    <div class="settings-dropdown" :class="{ 'open': open }" x-cloak>
        <div class="settings-section-title">{{ __('Theme') }}</div>
        <button class="settings-option" :class="{ 'active': theme === 'light' }" @click="setTheme('light')">
            <span style="display:flex; align-items:center; gap:8px;">
                <span class="material-symbols-outlined" style="font-size:18px;">light_mode</span> {{ __('Light') }}
            </span>
            <span class="material-symbols-outlined" x-show="theme === 'light'" style="font-size:18px;">check</span>
        </button>
        <button class="settings-option" :class="{ 'active': theme === 'dark' }" @click="setTheme('dark')">
            <span style="display:flex; align-items:center; gap:8px;">
                <span class="material-symbols-outlined" style="font-size:18px;">dark_mode</span> {{ __('Dark') }}
            </span>
            <span class="material-symbols-outlined" x-show="theme === 'dark'" style="font-size:18px;">check</span>
        </button>
        
        <div class="settings-divider"></div>
        
        <div class="settings-section-title">{{ __('Language') }}</div>
        @php $currLocale = session('locale', 'en'); @endphp
        <button onclick="window.location.href='{{ route('locale.set', 'en') }}'" class="settings-option {{ $currLocale == 'en' ? 'active' : '' }}">
            <span>English (EN)</span>
            @if($currLocale == 'en') <span class="material-symbols-outlined" style="font-size:18px;">check</span> @endif
        </button>
        <button onclick="window.location.href='{{ route('locale.set', 'si') }}'" class="settings-option {{ $currLocale == 'si' ? 'active' : '' }}">
            <span>Sinhala (සිං)</span>
            @if($currLocale == 'si') <span class="material-symbols-outlined" style="font-size:18px;">check</span> @endif
        </button>
        <button onclick="window.location.href='{{ route('locale.set', 'ta') }}'" class="settings-option {{ $currLocale == 'ta' ? 'active' : '' }}">
            <span>Tamil (தமிழ்)</span>
            @if($currLocale == 'ta') <span class="material-symbols-outlined" style="font-size:18px;">check</span> @endif
        </button>
    </div>
</div>
