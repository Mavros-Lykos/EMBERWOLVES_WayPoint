# Activity A2 — Domain Model Design
## Detailed Sub-Task Plan

> The Domain Model is the central nervous system of the entire Tech-Triathlon project. It defines the database schema for the Hackathon, dictates the state available to the UI in the Designathon, and represents the physical reality modeled in the Datathon. Getting this right prevents spaghetti code later.

---

## Why A2 Matters

If the data model is flawed, the allocation engine will be overly complex, the offline sync will fail, and the UI will require messy workarounds. A robust domain model enforces business rules at the lowest possible level.

---

## Sub-Task A2.1 — Core Entities & Relationships (ERD)

> **Goal:** Define the canonical schema connecting all actors and physical assets.

### The Core Schema

1.  **`outlets` (Reference Data - read only in app)**
    *   `outlet_id` (PK, string)
    *   `brand`, `district`, `depot`
    *   `dock_type`, `parking_constraint`
    *   `mall_window`, `window_open_time`, `window_close_time`

2.  **`vehicles` (Reference Data)**
    *   `vehicle_id` (PK, string)
    *   `type` (truck | van), `temp` (reefer | ambient)
    *   `weight_cap_kg`, `volume_cap_m3`
    *   `fuel_type`, `km_per_l`, `weekly_fuel_quota_l`
    *   `depot`

3.  **`orders` (Transactional)**
    *   `order_id` / `order_ref` (PK, string)
    *   `outlet_id` (FK -> outlets)
    *   `order_date` (Date requested for delivery)
    *   `temp_requirement` (ambient | chilled)
    *   `order_units`, `order_weight_kg`, `order_volume_m3`
    *   `status` (Enum: pending | allocated | deferred | loaded | in_transit | delivered | failed)
    *   `deferral_reason` (String/Enum, populated if status=deferred)
    *   `trip_id` (FK -> trips, nullable)

4.  **`trips` (Transactional - The Allocation Unit)**
    *   `trip_id` (PK, UUID)
    *   `vehicle_id` (FK -> vehicles)
    *   `trip_number` (1 or 2)
    *   `date` (Date of operation)
    *   `brand`, `district` (Enforced grouping)
    *   `status` (Enum: planned | loading | dispatched | completed)
    *   *Derived*: total weight/volume (sum of linked orders)

5.  **`route_legs` (Transactional - Tracking)**
    *   `leg_id` (PK, UUID)
    *   `trip_id` (FK -> trips)
    *   `seq` (Integer, 0-indexed route order)
    *   `from_point`, `to_outlet` (FK -> outlets)
    *   `planned_depart_time`, `planned_arrival_time`
    *   `actual_depart_time`, `actual_arrival_time`, `leave_outlet_time` (Populated by Driver App)

### Sub-task Actions
- [ ] Diagram the ERD (for the Hackathon `/docs` folder).
- [ ] Define precise data types (e.g., decimal precision for m³ and kg).
- [ ] Identify which fields are populated pre-4PM (Store Manager), at 4PM-8PM (Dispatcher), and post-dispatch (Driver).

---

## Sub-Task A2.2 — State Machines

> **Goal:** Map the exact lifecycle of the three most dynamic entities.

### 1. Order Lifecycle
```text
[Created] (by Store Mgr pre-4PM) 
   ↓
[Pending] (4PM Cutoff reached, ready for Dispatcher)
   ├─→ [Deferred] (Capacity/Time limit hit) ──> [Pending] (Next day)
   ↓
[Allocated] (Assigned to Trip)
   ↓
[Loaded] (Loader confirms on truck)
   ↓
[In Transit] (Trip dispatched)
   ↓
[Delivered] (Driver confirms at outlet) ─→ [Confirmed] (Store Mgr receipts)
```

### 2. Trip Lifecycle
```text
[Planned] (Dispatcher building allocation)
   ↓
[Loading] (Pushed to Warehouse, Loader actively scanning)
   ↓
[Dispatched] (Vehicle leaves depot)
   ↓
[Completed] (Vehicle returns to depot)
```

### 3. Vehicle Daily State
```text
[Available] ─→ [Trip 1 Planned] ─→ [Trip 1 Active] ─→ [Returned] 
                                                         ↓ (If time allows)
                                                     [Trip 2 Planned] ─→ [Trip 2 Active] ─→ [Done for Day]
```

### Sub-task Actions
- [ ] Document valid state transitions to prevent impossible jumps (e.g., `Allocated` directly to `Delivered` without `Loaded`).
- [ ] Define the triggers for notifications (e.g., transition to `Deferred` triggers push to Store Manager).

---

## Sub-Task A2.3 — Constraint Enforcement Layer

> **Goal:** Decide where rules live. Hard physical rules belong in the database. Soft planning rules belong in application logic.

### Database Constraints (PostgreSQL / Relational DB)
*   **Capacity Limit:** `CHECK (sum(order_weight) <= vehicle.weight_cap)` (Can be implemented via triggers or materialized views).
*   **Trip Grouping:** `CHECK` constraint ensuring all orders on a `trip_id` have matching `brand` and `district`.
*   **Valid Vehicle Assignment:** Trigger ensuring `vehicle.depot == outlet.depot`.
*   **Reefer Rule:** Trigger ensuring if order is `chilled`, `vehicle.temp` must be `reefer`.

### Application Logic Constraints (Allocation Engine)
*   **Time Windows:** Calculating if a trip exceeds the 270-minute (Fresh) or 480-minute (Style/Tech) budget. (Too complex for simple DB constraints due to travel matrix math).
*   **Mall Window Overlaps:** Detecting if two mall outlets on the same trip have conflicting fixed windows.
*   **Van Only Checks:** Ensuring `van_only` outlets are routed exclusively to `van` types.

### Sub-task Actions
- [ ] Draft the SQL migration schema for the DB constraints.
- [ ] Write the validation utility functions for the Application logic layer.

---

## Sub-Task A2.4 — Offline Sync Data Model

> **Goal:** Design the data structures that allow the Driver app to function in hill country without a network connection.

### The "Sync Queue" Pattern
When a driver is offline, updates cannot hit the central database directly.
1.  **Local State (IndexedDB/SQLite):** The driver's device downloads the full `Trip` and related `route_legs` upon dispatch.
2.  **Mutation Log:** When a driver completes a stop, it writes a mutation event locally:
    `{ type: "STOP_COMPLETE", leg_id: "...", actual_arrival: "05:12", leave_time: "05:28", timestamp: 1711345000 }`
3.  **Sync Engine:** When connectivity is restored, the app replays the mutation log to the backend API.
4.  **Conflict Resolution:** Last-Write-Wins based on the local `timestamp`, not the server receipt time.

### Sub-task Actions
- [ ] Define the JSON schema for the sync mutation payload.
- [ ] Design the UI state flag (`is_offline`, `pending_sync_count`) for the Driver Persona (A3).

---

## Sub-Task A2.5 — ML Integration Layer (Datathon to Hackathon)

> **Goal:** Define how the predictive models from Task 1 and Task 2A surface in the application.

### Task 1: Arrival Prediction Integration
*   The system needs fields on `route_legs` for `predicted_arrival_time` and `lateness_probability`.
*   *Workflow:* When Dispatcher builds a Trip, the backend calls the ML inference service. It returns the expected delay, which updates the UI. If `lateness_probability > 0.5`, flag it red on the Dispatcher's canvas.

### Task 2A: Demand Forecast Integration
*   Create a separate table: `demand_forecasts (depot, brand, iso_year, iso_week, predicted_total_m3, predicted_chilled_m3)`.
*   *Workflow:* Pre-calculated weekly by the Datathon model, stored in the DB, and visualized in a "Capacity Planning" dashboard for the Dispatcher to anticipate fleet shortfalls.

### Sub-task Actions
- [ ] Define the API contract (request/response JSON) for the ML Inference endpoints.
- [ ] Sketch the UI components that will consume these predictions.

---

*Next Activity: → A3 (Designathon UX Design) uses these entities and states to define the screens.*
