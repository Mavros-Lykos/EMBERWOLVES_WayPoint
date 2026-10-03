<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Release & Seal Locking — Waypoint Depot</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        :root {
            --bg-page: #020617;
            --surface: #0f172a;
            --border: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #38bdf8;
            --success: #22c55e;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-page); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
        header { height: 72px; padding: 0 24px; background: var(--surface); border-bottom: 2px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .back-btn { height: 52px; padding: 0 16px; border-radius: 12px; background: #1e293b; color: var(--text-main); text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.95rem; border: 1px solid var(--border); }
        .container { max-width: 640px; margin: 32px auto; padding: 0 20px; width: 100%; display: flex; flex-direction: column; gap: 24px; align-items: center; }
        .lock-badge { width: 88px; height: 88px; border-radius: 50%; background: rgba(56,189,248,0.15); color: var(--primary); display: flex; align-items: center; justify-content: center; }
        .seal-card { width: 100%; background: var(--surface); border: 2px solid var(--border); border-radius: 20px; padding: 28px; display: flex; flex-direction: column; gap: 20px; align-items: center; }
        .seal-display { font-size: 2.2rem; font-weight: 800; font-family: monospace; letter-spacing: 4px; color: #fff; background: #020617; border: 2px solid var(--border); border-radius: 12px; padding: 12px 24px; width: 100%; text-align: center; }
        .keypad-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; width: 100%; }
        .key-btn { height: 64px; border-radius: 12px; background: #1e293b; border: 2px solid var(--border); color: #fff; font-size: 1.5rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.1s; }
        .key-btn:active { background: var(--primary); color: #000; transform: scale(0.96); }
        .btn-dispatch { width: 100%; height: 72px; border-radius: 16px; background: var(--success); border: none; color: #000; font-size: 1.2rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('loader.load.view', $trip->trip_id) }}" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Load
        </a>
        <div style="font-weight: 800; font-size: 1.2rem;">
            Security Seal Lockdown: {{ $trip->vehicle_id }}
        </div>
        <span style="background: rgba(56,189,248,0.2); color: var(--primary); padding: 6px 14px; border-radius: 10px; font-weight: 800; font-size: 0.85rem;">LOAD-05</span>
    </header>

    <div class="container">
        <div class="lock-badge">
            <span class="material-symbols-outlined" style="font-size: 48px;">lock</span>
        </div>

        <div style="text-align: center;">
            <h1 style="font-size: 1.4rem; font-weight: 800;">Apply Plastic Bolt Seal to Rear Door</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">Punch in the 4-digit physical seal serial number to clear security gate.</p>
        </div>

        <form action="{{ route('loader.dispatch') }}" method="POST" style="width: 100%;">
            @csrf
            <input type="hidden" name="trip_id" value="{{ $trip->trip_id }}">

            <div class="seal-card">
                <input type="text" id="seal_input" name="seal_number" class="seal-display" value="9942" maxlength="6" required readonly>

                <div class="keypad-grid">
                    @for($i = 1; $i <= 9; $i++)
                        <button type="button" class="key-btn" onclick="appendDigit('{{ $i }}')">{{ $i }}</button>
                    @endfor
                    <button type="button" class="key-btn" style="color: #f87171;" onclick="clearSeal()">C</button>
                    <button type="button" class="key-btn" onclick="appendDigit('0')">0</button>
                    <button type="button" class="key-btn" onclick="backspace()"><span class="material-symbols-outlined">backspace</span></button>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 12px; background: #020617; border-radius: 10px;">
                    <span style="color: var(--text-muted); font-size: 0.85rem;">Departure Temp Check:</span>
                    <strong style="color: var(--success); font-size: 1rem;">2.9°C (Verified Safe &le; 4.0°C)</strong>
                </div>

                <button type="submit" class="btn-dispatch">
                    <span class="material-symbols-outlined" style="font-size: 28px;">verified</span>
                    ISSUE DIGITAL GATE PASS (64px)
                </button>
            </div>
        </form>
    </div>

    <script>
        function appendDigit(d) {
            let el = document.getElementById('seal_input');
            if (el.value.length < 6) el.value += d;
        }
        function clearSeal() {
            document.getElementById('seal_input').value = '';
        }
        function backspace() {
            let el = document.getElementById('seal_input');
            el.value = el.value.slice(0, -1);
        }
    </script>
</body>
</html>
