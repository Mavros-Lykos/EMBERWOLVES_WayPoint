<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mandatory Rest Break</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    <style>
        :root {
            --primary: #3b82f6;
            --surf: #0f172a;
            --surf-c: #1e293b;
            --on-surf: #f8fafc;
            --outline: #334155;
            --success: #10b981;
            --warn: #f59e0b;
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

        .top-bar {
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--surf-c);
            border-bottom: 1px solid var(--outline);
        }

        .back-btn {
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(59,130,246,0.1);
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            text-align: center;
        }

        .timer-circle {
            width: 240px;
            height: 240px;
            border-radius: 50%;
            border: 8px solid var(--primary);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            box-shadow: 0 0 32px rgba(59,130,246,0.2);
            position: relative;
        }
        
        .timer-circle::after {
            content: '';
            position: absolute;
            top: -8px; left: -8px; right: -8px; bottom: -8px;
            border-radius: 50%;
            border: 8px solid rgba(59,130,246,0.2);
            border-top-color: transparent;
            animation: spin 4s linear infinite;
        }

        @keyframes spin { 100% { transform: rotate(360deg); } }

        .time {
            font-size: 48px;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            color: #fff;
            line-height: 1;
        }

        .time-label {
            color: #94a3b8;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
        }

        .btn-large {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 20px;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            color: #fff;
            margin-bottom: 16px;
            cursor: pointer;
        }

        .btn-resume { background: var(--success); box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4); transition: all 0.2s; }
        .btn-resume:active { background: #059669; transform: scale(0.98); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.5); }

        .btn-secondary { background: var(--surf-c); border: 1px solid var(--outline); }
        .btn-secondary:active { background: #334155; }

    </style>
</head>
<body>

    <div class="top-bar">
        <a href="{{ route('driver.route') }}" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div style="font-size:18px; font-weight:600;">{{ __('Driver Break Mode') }}</div>
    </div>

    <div class="content">
        
        <div style="margin-bottom:24px;">
            <span class="material-symbols-outlined" style="font-size:48px; color:#94a3b8;">local_cafe</span>
        </div>

        <div class="timer-circle">
            <div class="time" id="countdown">15:00</div>
            <div class="time-label">{{ __('Remaining') }}</div>
        </div>

        <h2 style="margin:0 0 12px 0; font-size:20px;">{{ __('Mandatory Rest Period') }}</h2>
        <p style="color:#94a3b8; margin:0 0 48px 0; font-size:15px; line-height:1.5;">
            {{ __('Your vehicle tracking is paused. Please take this time to rest. You can resume your route early if necessary.') }}
        </p>

        <button class="btn-large btn-resume" onclick="window.location.href='{{ route('driver.route') }}'">
            <span class="material-symbols-outlined">play_arrow</span>
            {{ __('Resume Route Now') }}
        </button>

    </div>

    <script>
        // Simple JS countdown for realism
        let time = 15 * 60;
        const el = document.getElementById('countdown');
        setInterval(() => {
            if(time <= 0) return;
            time--;
            let m = Math.floor(time / 60).toString().padStart(2, '0');
            let s = (time % 60).toString().padStart(2, '0');
            el.innerText = `${m}:${s}`;
        }, 1000);
    </script>
</body>
</html>
