<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Emergency Breakdown</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    <style>
        :root {
            --primary: #ef4444; /* Critical Red */
            --surf: #0f172a;
            --surf-c: #1e293b;
            --on-surf: #f8fafc;
            --outline: #334155;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surf);
            color: var(--on-surf);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background: #450a0a;
            padding: 24px;
            text-align: center;
            border-bottom: 2px solid var(--primary);
        }

        .content {
            flex: 1;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .icon-pulse {
            width: 80px; height: 80px;
            background: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto;
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 20px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .btn-massive {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 24px;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
        }

        .btn-call { background: var(--primary); }
        .btn-call:active { background: #dc2626; transform: scale(0.98); }

        .btn-secondary { background: var(--surf-c); border: 2px solid var(--outline); color: var(--on-surf); }
        .btn-secondary:active { background: #334155; }

        .info-card {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 16px;
            font-size: 14px;
            color: #cbd5e1;
            display: flex;
            gap: 12px;
        }

    </style>
</head>
<body>

    <div class="header">
        <div class="icon-pulse">
            <span class="material-symbols-outlined" style="font-size:40px; color:#fff">warning</span>
        </div>
        <h1 style="margin:20px 0 8px 0; font-size:24px; color:#fca5a5;">{{ __('Vehicle Breakdown') }}</h1>
        <p style="margin:0; font-size:15px; color:#f87171;">{{ __('Stay safe. Pull over if possible.') }}</p>
    </div>

    <div class="content">
        
        <div class="info-card">
            <span class="material-symbols-outlined" style="color:var(--primary)">location_on</span>
            <div>
                <strong>{{ __('Your location has been locked.') }}</strong><br>
                {{ __('Dispatch has been notified of your coordinates automatically.') }}
            </div>
        </div>

        <div style="flex:1"></div>

        <a href="tel:0112223334" class="btn-massive btn-call">
            <span class="material-symbols-outlined" style="font-size:32px;">support_agent</span>
            {{ __('Call Central Dispatch Now') }}
        </a>

        <button class="btn-massive btn-secondary" onclick="window.location.href='{{ route('driver.route') }}'">
            <span class="material-symbols-outlined" style="font-size:24px;">arrow_back</span>
            {{ __('Cancel / False Alarm') }}
        </button>

    </div>

</body>
</html>
