<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Canvas — Waypoint</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --surf: #0f172a; --surf-c: #1e293b; --on-surf: #e2e8f0; --outline: rgba(148,163,184,0.2); --primary: #3b82f6; }
        body { font-family: 'Roboto', sans-serif; background: var(--surf); color: var(--on-surf); margin: 0; padding: 24px; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--outline); padding-bottom: 16px; }
        .btn-outlined { display: inline-flex; align-items: center; gap: 8px; background: transparent; color: var(--primary); border: 1px solid rgba(59,130,246,0.4); border-radius: 999px; padding: 10px 24px; font-size: 14px; text-decoration: none; cursor: pointer; }
        .btn-filled { display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: #fff; border: none; border-radius: 999px; padding: 10px 24px; font-size: 14px; font-weight: 500; cursor: pointer; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
        .board { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 16px; align-items: flex-start; }
        .col { min-width: 300px; background: var(--surf-c); border: 1px solid var(--outline); border-radius: 12px; padding: 16px; display: flex; flex-direction: column; }
        .order-card { background: var(--surf); border: 1px solid var(--outline); padding: 12px; border-radius: 8px; margin-bottom: 8px; font-size: 13px; cursor: grab; }
        .order-card:active { cursor: grabbing; opacity: 0.8; }
        .drop-zone { border: 2px dashed rgba(148,163,184,0.2); border-radius: 8px; min-height: 150px; display: flex; flex-direction: column; gap: 8px; color: #94a3b8; margin-top: 12px; padding: 8px; transition: background 0.2s; }
        .drop-zone.dragover { background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.5); }
        .empty-text { margin: auto; font-size: 13px; pointer-events: none; }
    </style>
</head>
<body>
    <div class="top-bar">
        <h2>Drag & Drop Planning Canvas</h2>
        <div style="display:flex; gap:12px;">
            <button class="btn-filled" onclick="savePlan()" id="lock-btn">
                <span class="material-symbols-outlined">lock</span> Lock Plan & Dispatch
            </button>
            <a href="{{ route('dispatch.overview') }}" class="btn-outlined">
                <span class="material-symbols-outlined">arrow_back</span> Back
            </a>
        </div>
    </div>

    <p style="color: #94a3b8; margin-bottom: 24px;">
        <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">info</span>
        The automated Allocation Engine handles 99% of routing. This canvas allows manual overrides for edge cases.
    </p>

    <div class="board">
        <!-- Unallocated Queue Column -->
        <div class="col">
            <h3 style="font-size: 14px; color: #94a3b8; margin-bottom: 12px; text-transform: uppercase;">Unallocated Queue ({{ collect($pendingOrders)->flatten()->count() }})</h3>
            <div class="drop-zone" id="unallocated-queue" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                @foreach($pendingOrders as $group => $orders)
                    @foreach($orders as $order)
                    <div class="order-card" id="order-{{ $order->order_ref }}" draggable="true" ondragstart="drag(event)">
                        <strong>{{ $order->order_ref }}</strong> — {{ $order->outlet->district }}<br>
                        <span style="color: #94a3b8;">{{ $order->order_units }} units ({{ ucfirst($order->temp_requirement) }})</span>
                    </div>
                    @endforeach
                @endforeach
                @if(collect($pendingOrders)->flatten()->count() == 0)
                    <span class="empty-text">Queue is empty</span>
                @endif
            </div>
        </div>

        <!-- Vehicle Columns -->
        @foreach($vehicles->take(4) as $vehicle)
        <div class="col">
            <h3 style="font-size: 14px; margin-bottom: 4px;">{{ $vehicle->vehicle_id }}</h3>
            <p style="font-size: 12px; color: #94a3b8; margin-top:0;">{{ ucfirst($vehicle->temp) }} | Cap: {{ $vehicle->volume_cap_m3 }} m³</p>
            <div class="drop-zone" id="vehicle-{{ $vehicle->vehicle_id }}" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                <span class="empty-text">Drop orders here</span>
            </div>
        </div>
        @endforeach
    </div>

    <script>
        function allowDrop(ev) {
            ev.preventDefault();
            ev.currentTarget.classList.add('dragover');
        }

        function dragLeave(ev) {
            ev.currentTarget.classList.remove('dragover');
        }

        function drag(ev) {
            ev.dataTransfer.setData("text", ev.target.id);
            // Hide the empty text if it exists in the target
        }

        function drop(ev) {
            ev.preventDefault();
            let dropZone = ev.currentTarget;
            dropZone.classList.remove('dragover');
            
            var data = ev.dataTransfer.getData("text");
            var draggedElement = document.getElementById(data);
            
            // Remove the "Drop orders here" or "Queue is empty" text if present
            let emptyText = dropZone.querySelector('.empty-text');
            if (emptyText) {
                emptyText.style.display = 'none';
            }
            
            dropZone.appendChild(draggedElement);
        }

        function savePlan() {
            const btn = document.getElementById('lock-btn');
            btn.innerHTML = '<span class="material-symbols-outlined">sync</span> Saving...';
            btn.disabled = true;
            
            const allocations = {};
            
            // Query all vehicle columns
            document.querySelectorAll('[id^="vehicle-"]').forEach(col => {
                const vehicleId = col.id.replace('vehicle-', '');
                const orders = Array.from(col.querySelectorAll('.order-card')).map(card => card.id.replace('order-', ''));
                if(orders.length > 0) {
                    allocations[vehicleId] = orders;
                }
            });

            fetch('{{ route('dispatch.savePlan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ allocations })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    btn.innerHTML = '<span class="material-symbols-outlined">check</span> Success';
                    btn.style.background = '#22c55e';
                    setTimeout(() => window.location.href = '{{ route('dispatch.overview') }}', 1000);
                } else {
                    alert('Error saving plan: ' + data.message);
                    btn.innerHTML = '<span class="material-symbols-outlined">lock</span> Lock Plan & Dispatch';
                    btn.disabled = false;
                }
            }).catch(err => {
                alert('Network error while saving plan');
                btn.innerHTML = '<span class="material-symbols-outlined">lock</span> Lock Plan & Dispatch';
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>
