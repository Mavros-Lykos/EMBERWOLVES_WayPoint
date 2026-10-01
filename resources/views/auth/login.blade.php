<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waypoint Dispatch - Enterprise Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container text-on-surface h-screen flex items-center justify-center font-sans">
    
    <main class="bg-surface p-8 rounded-xl shadow-lg w-full max-w-md border border-outline/20">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-primary mb-2">Waypoint Dispatch</h1>
            <p class="text-outline text-sm">Tech-Triathlon 2026</p>
        </div>

        @if($errors->any())
            <div class="bg-critical/10 text-critical text-sm p-3 rounded-md mb-6 flex gap-2 items-center">
                <span class="material-symbols-outlined text-lg">error</span>
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Hackathon Seeded Logins -->
        <div class="space-y-4">
            <h2 class="text-sm font-medium text-outline uppercase tracking-wider mb-4">Seeded Walkthrough Accounts</h2>
            
            <a href="{{ route('magic.login', 'store_manager') }}" class="w-full flex items-center gap-3 p-3 rounded-lg border border-outline/30 hover:bg-surface-container transition group">
                <div class="bg-primary/10 text-primary w-10 h-10 flex items-center justify-center rounded-full">
                    🏪
                </div>
                <div class="text-left">
                    <div class="font-medium">Store Manager</div>
                    <div class="text-xs text-outline">Priya K. (OUT007)</div>
                </div>
                <span class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">→</span>
            </a>

            <a href="{{ route('magic.login', 'dispatcher') }}" class="w-full flex items-center gap-3 p-3 rounded-lg border border-outline/30 hover:bg-surface-container transition group">
                <div class="bg-primary/10 text-primary w-10 h-10 flex items-center justify-center rounded-full">
                    📊
                </div>
                <div class="text-left">
                    <div class="font-medium">Dispatcher (Lead)</div>
                    <div class="text-xs text-outline">Kamal (Peliyagoda)</div>
                </div>
                <span class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">→</span>
            </a>

            <a href="{{ route('magic.login', 'loader') }}" class="w-full flex items-center gap-3 p-3 rounded-lg border border-outline/30 hover:bg-surface-container transition group">
                <div class="bg-primary/10 text-primary w-10 h-10 flex items-center justify-center rounded-full">
                    📦
                </div>
                <div class="text-left">
                    <div class="font-medium">Warehouse Loader</div>
                    <div class="text-xs text-outline">Nuwan B. (Bay 4)</div>
                </div>
                <span class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">→</span>
            </a>

            <a href="{{ route('magic.login', 'driver') }}" class="w-full flex items-center gap-3 p-3 rounded-lg border border-outline/30 hover:bg-surface-container transition group">
                <div class="bg-primary/10 text-primary w-10 h-10 flex items-center justify-center rounded-full">
                    🚚
                </div>
                <div class="text-left">
                    <div class="font-medium">Delivery Driver</div>
                    <div class="text-xs text-outline">Saman K. (VEH003)</div>
                </div>
                <span class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">→</span>
            </a>
        </div>
        
    </main>

</body>
</html>
