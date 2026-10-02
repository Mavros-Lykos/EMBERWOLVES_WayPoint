<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Dashboard - Waypoint Dispatch</title>
    <meta name="theme-color" content="#1565C0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container text-on-surface flex flex-col min-h-screen font-sans" x-data="storeApp()">
    
    <!-- Mobile Header -->
    <header class="bg-surface px-4 py-3 flex items-center justify-between border-b border-outline/20 sticky top-0 z-20 shadow-sm">
        <div>
            <h1 class="font-semibold text-lg">Store Manager Portal</h1>
            <p class="text-xs text-outline">{{ auth()->user()->depot }} Store • Logged in as {{ auth()->user()->name }}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-surface-container text-on-surface hover:bg-surface-container-low px-4 py-2 rounded-full text-sm font-medium transition border border-outline/20">Exit</button>
        </form>
    </header>

    <main class="flex-1 p-4 space-y-4">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-surface p-4 rounded-xl shadow-sm border border-outline/10 text-center">
                <div class="text-3xl font-bold text-primary">{{ $expectedOrders->count() + ($incoming ? 1 : 0) }}</div>
                <div class="text-xs font-medium text-outline uppercase mt-1">Expected Today</div>
            </div>
            <div class="bg-surface p-4 rounded-xl shadow-sm border border-outline/10 text-center">
                <div class="text-3xl font-bold text-critical">{{ $disputes }}</div>
                <div class="text-xs font-medium text-outline uppercase mt-1">Disputes</div>
            </div>
        </div>

        <!-- Incoming Delivery -->
        @if($incoming)
        <h3 class="font-medium text-sm text-outline uppercase tracking-wider mt-6 mb-2">Arriving Soon</h3>
        <div class="bg-surface p-5 rounded-2xl shadow-sm border-l-4 border-l-warning">
            <div class="flex justify-between items-start mb-2">
                <span class="bg-warning/10 text-warning-dark text-xs font-bold px-2 py-1 rounded-md uppercase tracking-wide">ETA: {{ \Carbon\Carbon::parse($incoming->planned_arrival_time ?? now())->format('H:i') }}</span>
            </div>
            
            <h2 class="text-xl font-bold mb-1">Order {{ $incoming->order_ref }}</h2>
            <p class="text-sm text-outline mb-4">Trip {{ $incoming->trip_id }}</p>

            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center text-sm border-b border-outline/10 pb-2">
                    <span class="text-outline">Expected Units</span>
                    <span class="font-bold">{{ $incoming->order_units }}</span>
                </div>
                <div class="flex justify-between items-center text-sm border-b border-outline/10 pb-2">
                    <span class="text-outline">Temperature Requirement</span>
                    <span class="font-bold text-primary">{{ ucfirst($incoming->temp_requirement) }}</span>
                </div>
            </div>

            <div class="flex gap-2">
                <button @click="showDispute = true" class="flex-1 bg-critical/10 text-critical font-medium py-3 rounded-xl hover:bg-critical/20 transition">
                    Dispute Delivery
                </button>
                <button @click="acceptDelivery()" class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl shadow hover:bg-primary/90 transition">
                    Sign & Accept
                </button>
            </div>
        </div>
        @else
        <div class="mt-8 text-center py-10 bg-surface rounded-2xl border border-outline/10">
            <span class="text-4xl">🚚</span>
            <h3 class="mt-4 font-bold text-lg">No Incoming Deliveries</h3>
            <p class="text-sm text-outline mt-1">You are all caught up for the day!</p>
        </div>
        @endif

    </main>

    <!-- Dispute Modal -->
    <div x-show="showDispute" style="display: none;" class="fixed inset-0 bg-surface-container/90 z-50 flex flex-col p-4 backdrop-blur-sm justify-end" x-transition>
        <div class="bg-surface p-6 rounded-t-3xl shadow-xl border-t border-outline/20">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-3 text-critical">
                    <span class="text-2xl">⚠️</span>
                    <h2 class="font-bold text-lg">Dispute Delivery</h2>
                </div>
                <button @click="showDispute = false" class="text-outline text-2xl">✕</button>
            </div>
            
            <p class="text-sm mb-4">You are disputing Order ORD0092309. Please provide details:</p>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-outline block mb-1">Dispute Reason</label>
                    <select class="w-full bg-surface-container border-none rounded-lg p-3 outline-none focus:ring-2 focus:ring-primary">
                        <option>Items missing from vehicle</option>
                        <option>Temperature violation (Spoiled)</option>
                        <option>Items damaged in transit</option>
                    </select>
                </div>
                
                <!-- Native Camera for Dispute Evidence -->
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-outline block mb-1">Photographic Evidence</label>
                    <input type="file" accept="image/*" capture="environment" @change="photoTaken = true" class="hidden" id="dispute-camera">
                    <button onclick="document.getElementById('dispute-camera').click()" class="w-full bg-surface-container border border-dashed border-outline/50 py-4 rounded-lg text-sm text-outline flex items-center justify-center gap-2 hover:bg-surface-container-low transition">
                        <span x-text="photoTaken ? '📸 Photo Attached' : '📷 Take Photo'"></span>
                    </button>
                </div>
                
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-outline block mb-1">Driver Name / Signature</label>
                    <div class="border border-outline/30 rounded-lg overflow-hidden bg-white touch-none">
                        <canvas id="dispute-signature" class="w-full h-24"></canvas>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="submitDispute()" class="w-full bg-critical text-white font-bold py-3 rounded-xl shadow hover:bg-critical/90 transition flex justify-center items-center gap-2">
                    <span x-show="!isSubmitting">Submit to Dispatch</span>
                    <span x-show="isSubmitting" class="animate-spin">⏳</span>
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('storeApp', () => ({
                showDispute: false,
                photoTaken: false,
                isSubmitting: false,
                signaturePad: null,
                
                init() {
                    this.$watch('showDispute', value => {
                        if (value && !this.signaturePad) {
                            setTimeout(() => {
                                const canvas = document.getElementById('dispute-signature');
                                const ratio =  Math.max(window.devicePixelRatio || 1, 1);
                                canvas.width = canvas.offsetWidth * ratio;
                                canvas.height = canvas.offsetHeight * ratio;
                                canvas.getContext("2d").scale(ratio, ratio);
                                this.signaturePad = new SignaturePad(canvas);
                            }, 50);
                        }
                    });
                },

                acceptDelivery() {
                    alert("Delivery Accepted. The driver's device will sync this confirmation.");
                },

                submitDispute() {
                    if (!this.photoTaken) {
                        alert("Photographic evidence is required to dispute a delivery.");
                        return;
                    }
                    if (this.signaturePad.isEmpty()) {
                        alert("Driver must sign acknowledging the dispute.");
                        return;
                    }
                    
                    this.isSubmitting = true;
                    setTimeout(() => {
                        this.isSubmitting = false;
                        this.showDispute = false;
                        alert("Dispute logged. HQ has been notified.");
                    }, 1500);
                }
            }));
        });
    </script>
</body>
</html>
