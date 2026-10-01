<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatcher Overview - Waypoint Dispatch</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container text-on-surface p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">Dispatcher Overview</h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-primary text-on-primary px-4 py-2 rounded-md">Logout</button>
        </form>
    </div>
    <div class="bg-surface p-6 rounded-lg shadow-sm border border-outline/20">
        <p>Welcome, {{ auth()->user()->name }}. Your depot is {{ auth()->user()->depot }}.</p>
    </div>
</body>
</html>
