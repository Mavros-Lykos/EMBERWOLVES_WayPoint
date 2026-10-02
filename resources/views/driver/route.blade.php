<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Route - Waypoint Dispatch</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1565C0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
</head>
<body class="bg-surface-container text-on-surface flex flex-col min-h-screen font-sans" x-data="driverApp()">
    
    <!-- Mobile Header -->
    <header class="bg-surface px-4 py-3 flex items-center justify-between border-b border-outline/20 sticky top-0 z-20">
        <div>
            <h1 class="font-semibold text-lg">Route Manifest</h1>
            <p class="text-xs text-outline">{{ auth()->user()->vehicle_id }} • <span x-text="isOnline ? 'Online' : 'Offline Mode'" :class="isOnline ? 'text-success' : 'text-warning'"></span></p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="syncData()" :disabled="!isOnline || pendingSync === 0" class="relative bg-surface-container-low p-2 rounded-full border border-outline/10 text-primary disabled:opacity-50 transition">
                🔄
                <span x-show="pendingSync > 0" x-text="pendingSync" class="absolute -top-1 -right-1 bg-warning text-on-surface w-4 h-4 rounded-full text-[10px] flex items-center justify-center font-bold"></span>
            </button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-sm font-medium text-outline">Exit</button>
            </form>
        </div>
    </header>

    <main class="flex-1 p-4 pb-24 space-y-4">
        
        <!-- Next Stop Card -->
        @if(count($routeLegs) > 0)
        <div class="bg-surface p-5 rounded-2xl shadow-sm border-2 border-primary/20">
            <div class="flex justify-between items-start mb-2">
                <span class="bg-primary text-on-primary text-xs font-bold px-2 py-1 rounded-md uppercase tracking-wide">Next Stop</span>
                <span class="text-sm font-medium text-outline">Stop 1 of {{ count($routeLegs) }}</span>
            </div>
            
            <h2 class="text-xl font-bold mb-1">Outlet {{ $routeLegs[0]->to_outlet }}</h2>
            <p class="text-sm text-outline mb-4">ETA: {{ \Carbon\Carbon::parse($routeLegs[0]->planned_arrival_time)->format('H:i') }}</p>

            <div class="flex gap-2">
                <!-- Zero API Cost Navigation -->
                <a href="geo:6.9271,79.8612?q=6.9271,79.8612" class="flex-1 bg-surface-container-low text-primary border border-primary/30 font-medium py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-primary/10 transition">
                    🧭 Navigate
                </a>
                <button @click="startDelivery()" class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl shadow hover:bg-primary/90 transition text-lg active:scale-95">
                    Arrived
                </button>
            </div>
        </div>
        @else
        <div class="mt-8 text-center py-10 bg-surface rounded-2xl border border-outline/10">
            <span class="text-4xl">🏁</span>
            <h3 class="mt-4 font-bold text-lg">Route Completed</h3>
            <p class="text-sm text-outline mt-1">No more stops scheduled for today.</p>
        </div>
        @endif

        <!-- Future Stops List -->
        @if(count($routeLegs) > 1)
        <h3 class="font-medium text-sm text-outline uppercase tracking-wider mt-6 mb-2">Remaining Stops</h3>
        <div class="space-y-3">
            @foreach($routeLegs->skip(1) as $leg)
            <div class="bg-surface p-4 rounded-xl shadow-sm border border-outline/10 opacity-70">
                <h4 class="font-semibold">Outlet {{ $leg->to_outlet }}</h4>
                <p class="text-xs text-outline">ETA: {{ \Carbon\Carbon::parse($leg->planned_arrival_time)->format('H:i') }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </main>

    <!-- Proof of Delivery Modal (Alpine) -->
    <div x-show="showPoD" style="display: none;" class="fixed inset-0 bg-surface-container/90 z-50 flex flex-col p-4 backdrop-blur-sm transition-opacity" x-transition>
        <div class="bg-surface flex-1 rounded-3xl shadow-xl flex flex-col overflow-hidden border border-outline/20">
            
            <div class="p-4 border-b border-outline/10 flex justify-between items-center bg-surface-container-low">
                <h2 class="font-bold text-lg">Proof of Delivery</h2>
                <button @click="showPoD = false" class="bg-surface-container w-8 h-8 rounded-full flex items-center justify-center">✕</button>
            </div>

            <div class="p-6 flex-1 overflow-y-auto space-y-6">
                
                <!-- Native Camera Capture -->
                <div>
                    <label class="block text-sm font-semibold mb-2">1. Photo Evidence</label>
                    <input type="file" accept="image/*" capture="environment" @change="handlePhoto" class="hidden" id="camera-input">
                    <button onclick="document.getElementById('camera-input').click()" class="w-full bg-surface-container py-8 rounded-2xl border-2 border-dashed border-primary text-primary font-medium hover:bg-primary/5 transition flex flex-col items-center justify-center gap-2">
                        <span class="text-3xl">📷</span>
                        <span x-text="photoTaken ? 'Photo Captured (Tap to retake)' : 'Open Camera'"></span>
                    </button>
                </div>

                <!-- Canvas Signature -->
                <div>
                    <label class="block text-sm font-semibold mb-2">2. Store Manager Signature</label>
                    <div class="border-2 border-outline/30 rounded-2xl overflow-hidden bg-white touch-none">
                        <canvas id="signature-pad" class="w-full h-40"></canvas>
                    </div>
                    <div class="flex justify-end mt-2">
                        <button @click="clearSignature" class="text-xs font-medium text-outline">Clear Signature</button>
                    </div>
                </div>

            </div>

            <!-- Submission Footer -->
            <div class="p-4 bg-surface-container-low border-t border-outline/10">
                <button @click="submitDelivery" class="w-full bg-success text-white text-lg font-bold py-4 rounded-2xl shadow hover:bg-success/90 transition active:scale-95 flex justify-center items-center gap-2">
                    <span x-show="!isSubmitting">Complete Delivery</span>
                    <span x-show="isSubmitting" class="animate-spin">⏳</span>
                </button>
                <p class="text-[10px] text-center text-outline mt-3">GPS Location will be automatically recorded.</p>
            </div>
        </div>
    </div>

    <!-- Core Logic -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('driverApp', () => ({
                isOnline: navigator.onLine,
                pendingSync: 0,
                showPoD: false,
                photoTaken: false,
                isSubmitting: false,
                signaturePad: null,
                
                init() {
                    window.addEventListener('online', () => this.isOnline = true);
                    window.addEventListener('offline', () => this.isOnline = false);
                    
                    // Initialize signature pad when modal opens
                    this.$watch('showPoD', value => {
                        if (value && !this.signaturePad) {
                            setTimeout(() => {
                                const canvas = document.getElementById('signature-pad');
                                // Fix canvas scaling for retina displays
                                const ratio =  Math.max(window.devicePixelRatio || 1, 1);
                                canvas.width = canvas.offsetWidth * ratio;
                                canvas.height = canvas.offsetHeight * ratio;
                                canvas.getContext("2d").scale(ratio, ratio);
                                
                                this.signaturePad = new SignaturePad(canvas, { penColor: "rgb(0, 0, 0)" });
                            }, 50);
                        }
                    });
                },

                startDelivery() {
                    this.showPoD = true;
                },

                handlePhoto(e) {
                    if(e.target.files.length > 0) {
                        this.photoTaken = true;
                    }
                },

                clearSignature() {
                    if(this.signaturePad) {
                        this.signaturePad.clear();
                    }
                },

                submitDelivery() {
                    this.isSubmitting = true;
                    
                    // Get GPS Stamp (Zero API Cost)
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            this.finalizeSubmission(pos.coords.latitude, pos.coords.longitude);
                        },
                        (err) => {
                            console.warn("GPS failed, submitting without location.");
                            this.finalizeSubmission(null, null);
                        },
                        { enableHighAccuracy: true, timeout: 5000 }
                    );
                },

                finalizeSubmission(lat, lng) {
                    // Collect signature as base64
                    const sigData = this.signaturePad.isEmpty() ? null : this.signaturePad.toDataURL('image/png');
                    
                    // In a real implementation, we would save to IndexedDB here via localForage
                    // and background sync when online. For demo, we just simulate.
                    
                    setTimeout(() => {
                        this.isSubmitting = false;
                        this.showPoD = false;
                        
                        if(!this.isOnline) {
                            this.pendingSync++;
                            alert("Saved offline. Will sync when connection is restored.");
                        } else {
                            alert("Delivery Confirmed! Synced to Dispatch.");
                        }
                    }, 1000);
                }
            }));
        });
    </script>
</body>
</html>
