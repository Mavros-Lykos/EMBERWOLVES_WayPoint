<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Waypoint Dispatch</title>
    <meta name="theme-color" content="#1565C0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Custom Keyframes for floating background elements */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-surface text-on-surface font-sans min-h-screen flex relative overflow-hidden" x-data="{ role: 'driver', loading: false }">
    
    <!-- Background Animated Blobs -->
    <div class="absolute top-0 -left-4 w-72 h-72 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-72 h-72 bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-tertiary rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

    <!-- Login Container -->
    <div class="m-auto w-full max-w-md relative z-10 px-4">
        
        <!-- Glassmorphism Card -->
        <div class="backdrop-blur-xl bg-surface/70 border border-outline/20 rounded-3xl shadow-2xl p-8 transition-all duration-300">
            
            <div class="flex justify-end mb-4 gap-3">
                <a href="{{ route('locale.set', 'en') }}" class="text-xs font-semibold {{ session('locale') == 'en' || !session('locale') ? 'text-primary border-b-2 border-primary' : 'text-outline' }}">EN</a>
                <a href="{{ route('locale.set', 'si') }}" class="text-xs font-semibold {{ session('locale') == 'si' ? 'text-primary border-b-2 border-primary' : 'text-outline' }}">සිං</a>
                <a href="{{ route('locale.set', 'ta') }}" class="text-xs font-semibold {{ session('locale') == 'ta' ? 'text-primary border-b-2 border-primary' : 'text-outline' }}">தமிழ்</a>
            </div>

            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary text-on-primary rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-4 shadow-lg shadow-primary/30">
                    W
                </div>
                <h1 class="text-2xl font-bold tracking-tight mb-1">Waypoint Dispatch</h1>
                <p class="text-sm text-outline">Tech-Triathlon 2026 Fleet Operations</p>
            </div>

            <form action="{{ route('login') }}" method="POST" @submit="loading = true">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-outline mb-2">{{ __('Email Address') }}</label>
                        <input type="email" name="email" value="saman@waypoint.lk" required class="w-full bg-surface-container-low border border-outline/30 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-outline mb-2">{{ __('Password') }}</label>
                        <input type="password" name="password" value="saman2026" required class="w-full bg-surface-container-low border border-outline/30 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    </div>
                    
                    @if($errors->any())
                    <div class="bg-error/10 text-error p-3 rounded-lg text-sm font-medium text-center">
                        {{ __('Invalid credentials provided.') }}
                    </div>
                    @endif

                    <button type="submit" class="w-full bg-primary text-on-primary font-bold py-3.5 rounded-xl shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                        <span x-show="!loading">{{ __('Sign In') }}</span>
                        <span x-show="loading" class="animate-spin">⌛</span>
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-outline/10">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-outline text-center mb-4">Hackathon Magic Links</h3>
                
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('magic.login', 'driver') }}" class="py-2.5 px-3 bg-surface-container text-xs font-semibold rounded-lg text-center border border-outline/10 hover:border-primary/50 hover:text-primary transition flex flex-col items-center gap-1 group">
                        <span class="text-lg group-hover:scale-110 transition">🚚</span>
                        Driver
                    </a>
                    <a href="{{ route('magic.login', 'dispatcher') }}" class="py-2.5 px-3 bg-surface-container text-xs font-semibold rounded-lg text-center border border-outline/10 hover:border-primary/50 hover:text-primary transition flex flex-col items-center gap-1 group">
                        <span class="text-lg group-hover:scale-110 transition">📡</span>
                        Dispatcher
                    </a>
                    <a href="{{ route('magic.login', 'loader') }}" class="py-2.5 px-3 bg-surface-container text-xs font-semibold rounded-lg text-center border border-outline/10 hover:border-primary/50 hover:text-primary transition flex flex-col items-center gap-1 group">
                        <span class="text-lg group-hover:scale-110 transition">🏗️</span>
                        Loader
                    </a>
                    <a href="{{ route('magic.login', 'store_manager') }}" class="py-2.5 px-3 bg-surface-container text-xs font-semibold rounded-lg text-center border border-outline/10 hover:border-primary/50 hover:text-primary transition flex flex-col items-center gap-1 group">
                        <span class="text-lg group-hover:scale-110 transition">🏪</span>
                        Store
                    </a>
                </div>
            </div>

        </div>
        
        <p class="text-center text-xs text-outline/60 mt-6">Secure Gateway • Powered by Laravel</p>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
