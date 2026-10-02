<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatcher Overview - Waypoint Dispatch</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
</head>
<body class="bg-surface-container text-on-surface h-screen flex flex-col font-sans overflow-hidden">
    
    <!-- Top Navigation Bar -->
    <header class="bg-surface px-6 py-4 flex items-center justify-between border-b border-outline/20 z-20 shadow-sm shrink-0">
        <div class="flex items-center gap-4">
            <div class="bg-primary text-on-primary w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg">W</div>
            <div>
                <h1 class="font-semibold text-lg leading-tight">Control Tower</h1>
                <p class="text-xs text-outline">{{ auth()->user()->depot }} Depot • Logged in as {{ auth()->user()->name }}</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <!-- Theme Toggle -->
            <div class="flex items-center gap-2 bg-surface-container-low p-1 rounded-full border border-outline/10" x-data="themeToggle()">
                <button @click="setTheme('light')" :class="theme === 'light' ? 'bg-surface shadow-sm text-primary' : 'text-outline'" class="w-8 h-8 rounded-full flex items-center justify-center transition" aria-label="Light theme">☀️</button>
                <button @click="setTheme('dark')" :class="theme === 'dark' ? 'bg-surface shadow-sm text-primary' : 'text-outline'" class="w-8 h-8 rounded-full flex items-center justify-center transition" aria-label="Dark theme">🌙</button>
                <button @click="setTheme('high-contrast')" :class="theme === 'high-contrast' ? 'bg-surface shadow-sm text-primary' : 'text-outline'" class="w-8 h-8 rounded-full flex items-center justify-center transition" aria-label="High contrast">⚡</button>
            </div>

            <!-- Language Switcher -->
            <div class="flex gap-1 text-sm font-medium">
                <a href="{{ route('locale.set', 'en') }}" class="px-3 py-1 rounded-md {{ app()->getLocale() === 'en' ? 'bg-primary/10 text-primary' : 'text-outline hover:bg-surface-container' }}">EN</a>
                <a href="{{ route('locale.set', 'si') }}" class="px-3 py-1 rounded-md {{ app()->getLocale() === 'si' ? 'bg-primary/10 text-primary' : 'text-outline hover:bg-surface-container' }}">සිං</a>
                <a href="{{ route('locale.set', 'ta') }}" class="px-3 py-1 rounded-md {{ app()->getLocale() === 'ta' ? 'bg-primary/10 text-primary' : 'text-outline hover:bg-surface-container' }}">த</a>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="bg-surface-container text-on-surface hover:bg-surface-container-low px-4 py-2 rounded-full text-sm font-medium transition border border-outline/20">Logout</button>
            </form>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex overflow-hidden">
        <!-- Sidebar: Active Trips -->
        <aside class="w-96 bg-surface border-r border-outline/20 flex flex-col z-10 shadow-[4px_0_24px_rgba(0,0,0,0.02)] shrink-0">
            <div class="p-4 border-b border-outline/10">
                <h2 class="font-semibold text-lg flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                    Active Fleet
                </h2>
                <input type="text" placeholder="Search vehicle or driver..." class="mt-3 w-full bg-surface-container border-none rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="fleet-list">
                <!-- Alpine.js / SSE will populate this -->
                @forelse($activeTrips as $trip)
                <div class="p-4 rounded-xl border border-outline/20 bg-surface-container-low hover:border-primary/50 transition cursor-pointer">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-semibold text-primary">{{ $trip->vehicle_id }} ({{ $trip->vehicle->type ?? 'Vehicle' }})</div>
                            <div class="text-xs text-outline">Trip {{ substr($trip->trip_id, 0, 8) }}</div>
                        </div>
                        <span class="px-2 py-1 bg-success/10 text-success text-xs font-medium rounded-full">In Transit</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-1.5 mt-3">
                        <div class="bg-primary h-1.5 rounded-full" style="width: 45%"></div>
                    </div>
                    <div class="flex justify-between mt-1 text-[10px] text-outline font-medium uppercase tracking-wider">
                        <span>{{ $trip->orders()->count() ?? 0 }} Stops</span>
                        <span>Active</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-outline text-sm mt-10">No active vehicles on the road.</div>
                @endforelse
            </div>
        </aside>

        <!-- Map Area -->
        <div class="flex-1 relative bg-surface-container-low">
            <div id="map" class="absolute inset-0 z-0"></div>
            
            <!-- Map Overlay Controls -->
            <div class="absolute top-4 right-4 z-10 space-y-2">
                <button class="w-10 h-10 bg-surface text-on-surface rounded-full shadow-md flex items-center justify-center border border-outline/10 hover:bg-surface-container transition" aria-label="Recenter map">
                    🎯
                </button>
            </div>
        </div>
    </main>

    <!-- External scripts for Alpine & Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <script>
        // Alpine Theme Toggle Logic
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeToggle', () => ({
                theme: localStorage.getItem('theme') || 'light',
                init() {
                    this.applyTheme();
                },
                setTheme(val) {
                    this.theme = val;
                    localStorage.setItem('theme', val);
                    this.applyTheme();
                },
                applyTheme() {
                    document.documentElement.setAttribute('data-theme', this.theme);
                }
            }));
        });

        // Initialize Leaflet Map centered on Colombo
        const map = L.map('map').setView([6.9271, 79.8612], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Dummy Marker for Visual testing
        const marker = L.marker([6.9271, 79.8612]).addTo(map)
            .bindPopup('<b>VEH037</b><br>Currently near Colombo 03.');
            
        // TODO: Wire up SSE connection to auto-update marker positions
    </script>
</body>
</html>
