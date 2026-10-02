<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loader Queue - Waypoint Dispatch</title>
    <meta name="theme-color" content="#1565C0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container text-on-surface flex flex-col min-h-screen font-sans" x-data="loaderApp()">
    
    <!-- Mobile Header -->
    <header class="bg-surface px-4 py-3 flex items-center justify-between border-b border-outline/20 sticky top-0 z-20 shadow-sm">
        <div>
            <h1 class="font-semibold text-lg">Loading Bay Queue</h1>
            <p class="text-xs text-outline">{{ auth()->user()->depot }} Depot • Logged in as {{ auth()->user()->name }}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-surface-container text-on-surface hover:bg-surface-container-low px-4 py-2 rounded-full text-sm font-medium transition border border-outline/20">Exit</button>
        </form>
    </header>

    <main class="flex-1 p-4 space-y-4">
        
        <!-- Active Trip Card -->
        @if($currentTrip)
        <div class="bg-surface p-5 rounded-2xl shadow-sm border-l-4 border-l-primary">
            <div class="flex justify-between items-start mb-2">
                <span class="bg-primary/10 text-primary text-xs font-bold px-2 py-1 rounded-md uppercase tracking-wide">Currently Loading</span>
            </div>
            
            <h2 class="text-xl font-bold mb-1">Trip {{ $currentTrip->trip_id }}</h2>
            <p class="text-sm text-outline mb-4">Vehicle {{ $currentTrip->vehicle_id }}</p>

            <div class="space-y-3">
                @foreach($orders as $order)
                <div class="flex justify-between items-center text-sm">
                    <span class="font-medium">Order {{ $order->order_ref }}</span>
                    <span class="text-outline">{{ $order->order_units }} Units ({{ $order->order_weight_kg }} kg)</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-2">
                    <div class="{{ $loop->first ? 'bg-success' : 'bg-primary' }} h-2 rounded-full" style="width: {{ $loop->first ? '100' : '0' }}%"></div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 flex gap-2">
                <button @click="showScanner = true" class="flex-1 bg-surface-container-low text-on-surface border border-outline/30 font-medium py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-surface-container transition">
                    📷 Scan Barcode
                </button>
                <button class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl shadow hover:bg-primary/90 transition">
                    Dispatch Trip
                </button>
            </div>
        </div>
        @else
        <div class="mt-8 text-center py-10 bg-surface rounded-2xl border border-outline/10">
            <span class="text-4xl">🏗️</span>
            <h3 class="mt-4 font-bold text-lg">No Active Trips</h3>
            <p class="text-sm text-outline mt-1">There are no vehicles currently assigned to load.</p>
        </div>
        @endif

        <!-- Upcoming Trips List -->
        @if(count($upcomingTrips) > 0)
        <h3 class="font-medium text-sm text-outline uppercase tracking-wider mt-6 mb-2">Upcoming Queue</h3>
        <div class="space-y-3">
            @foreach($upcomingTrips as $uTrip)
            <div class="bg-surface p-4 rounded-xl shadow-sm border border-outline/10 opacity-70 flex justify-between items-center">
                <div>
                    <h4 class="font-semibold">Trip {{ substr($uTrip->trip_id, 0, 8) }}</h4>
                    <p class="text-xs text-outline">{{ $uTrip->vehicle_id }} • {{ $uTrip->brand }}</p>
                </div>
                <span class="px-2 py-1 bg-surface-container text-xs rounded-md font-medium">Waiting</span>
            </div>
            @endforeach
        </div>
        @endif
    </main>

    <!-- Barcode Scanner Modal (Placeholder) -->
    <div x-show="showScanner" style="display: none;" class="fixed inset-0 bg-surface-container/90 z-50 flex flex-col p-4 backdrop-blur-sm" x-transition>
        <div class="bg-surface flex-1 rounded-3xl shadow-xl flex flex-col overflow-hidden border border-outline/20">
            <div class="p-4 border-b border-outline/10 flex justify-between items-center">
                <h2 class="font-bold text-lg">Scan Order Barcode</h2>
                <button @click="showScanner = false" class="bg-surface-container w-8 h-8 rounded-full flex items-center justify-center">✕</button>
            </div>
            <div class="p-6 flex-1 flex flex-col items-center justify-center">
                <div class="w-64 h-64 border-4 border-dashed border-primary/50 rounded-xl flex items-center justify-center mb-4 relative overflow-hidden">
                    <div class="absolute inset-0 bg-primary/10 animate-pulse"></div>
                    <span class="text-outline text-sm z-10 text-center">Camera Viewport<br>(Simulated)</span>
                    <div class="absolute w-full h-1 bg-primary top-1/2 animate-[bounce_2s_infinite]"></div>
                </div>
                <p class="text-sm text-center text-outline">Position barcode within the frame</p>
                <button @click="simulateScan()" class="mt-8 bg-primary text-on-primary px-8 py-3 rounded-xl font-bold w-full max-w-xs shadow">
                    Simulate Successful Scan
                </button>
                <button @click="simulateException()" class="mt-3 bg-critical/10 text-critical px-8 py-3 rounded-xl font-bold w-full max-w-xs">
                    Simulate Damaged Goods
                </button>
            </div>
        </div>
    </div>

    <!-- Exception Modal -->
    <div x-show="showException" style="display: none;" class="fixed inset-0 bg-surface-container/90 z-50 flex flex-col p-4 backdrop-blur-sm justify-end" x-transition>
        <div class="bg-surface p-6 rounded-t-3xl shadow-xl border-t border-outline/20">
            <div class="flex items-center gap-3 text-critical mb-4">
                <span class="text-2xl">⚠️</span>
                <h2 class="font-bold text-lg">Loading Exception</h2>
            </div>
            <p class="text-sm mb-4">Order ORD0092309 expects 12 units. Record discrepancy:</p>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-outline block mb-1">Reason</label>
                    <select class="w-full bg-surface-container border-none rounded-lg p-3 outline-none focus:ring-2 focus:ring-primary">
                        <option>Packaging Damaged</option>
                        <option>Missing from Warehouse</option>
                        <option>Incorrect Item</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-outline block mb-1">Actual Quantity Loaded</label>
                    <input type="number" value="11" class="w-full bg-surface-container border-none rounded-lg p-3 outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="showException = false" class="flex-1 py-3 text-outline font-medium hover:bg-surface-container rounded-xl transition">Cancel</button>
                <button @click="submitException()" class="flex-1 bg-critical text-white font-bold py-3 rounded-xl shadow hover:bg-critical/90 transition">Log Exception</button>
            </div>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loaderApp', () => ({
                showScanner: false,
                showException: false,
                
                simulateScan() {
                    alert("Order ORD0092309 scanned and loaded successfully!");
                    this.showScanner = false;
                },

                simulateException() {
                    this.showScanner = false;
                    setTimeout(() => this.showException = true, 300);
                },

                submitException() {
                    alert("Exception logged. Dispatcher notified.");
                    this.showException = false;
                }
            }));
        });
    </script>
</body>
</html>
