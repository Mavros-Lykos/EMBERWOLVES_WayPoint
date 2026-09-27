# Product Context: Waypoint Dispatch
*Tech-Triathlon 2026 Core Product Specification*

## Purpose
Waypoint Dispatch is a mission-critical, multi-depot retail replenishment and fleet routing platform. It coordinates high-volume daily distribution across 120 retail outlets and 2 central depots (Peliyagoda and Kandy) in Sri Lanka under strict time windows, cold-chain integrity requirements, and mountain topography constraints.

## Primary Users & Operating Scenes

1. **Kamal Gunaratne (Central Dispatcher, Peliyagoda HQ)**
   - **Scene:** Low-light command center with dual-monitor control tower during peak allocation (14:00 to 18:00).
   - **Job:** Allocates 60 vehicles across 120 stores before the non-negotiable 16:00 order cutoff, balancing volume ($m^3$), weight ($kg$), van-only physical access constraints, and fuel quotas.
   - **Need:** High-density scannable allocation canvas, automated constraint validation bars, zero narrative fluff.

2. **Nuwan Perera (Dock Supervisor, Warehouse Bays)**
   - **Scene:** High-noise, dust-prone loading docks; operating rugged touch tablets with thick gloves.
   - **Job:** Manages 18 staging bays, verifying pallet loading in strict reverse-delivery sequence (LIFO) and sealing multi-temperature reefers.
   - **Need:** $\ge 64\text{px}$ touch targets, single-tap step checklists, high visual contrast, tamper-evident seal lock confirmation.

3. **Saman Kumara (Field Delivery Driver, On-the-Road)**
   - **Scene:** 03:00 AM dark truck cab, high-glare daytime sunlight, winding mountain corridors (Kadugannawa, Nuwara Eliya) with zero cellular reception.
   - **Job:** Navigates multi-stop routes, performs pre-trip inspections (PTI), monitors reefer temperature ($<4^\circ\text{C}$), logs delivery receipts, and captures digital Proof of Delivery (PoD).
   - **Need:** 100% offline-first PWA, bottom thumb-zone controls, zero blocking spinners, acoustic/haptic feedback.

4. **Priya Kulatunga (Store Manager, Retail Outlets)**
   - **Scene:** Busy supermarket customer floor and loading bay; operating counter tablet or smartphone under bright store lighting.
   - **Job:** Prepares receiving staff based on live countdown ETAs, verifies seal numbers upon arrival, inspects perishable goods before the mandatory 08:00 AM Fresh opening window, and signs electronic PoD.
   - **Need:** Prominent arrival countdown timer, transparent deferral reasons, instant barcode/seal verification.

## Core Capabilities & Lifecycle
The platform enforces the 6-stage operational cycle:
`Order Cutoff (16:00) -> Vehicle Allocation -> LIFO Loading & Seal Lock -> Field Transit & Cold-Chain Monitored -> Store Receiving & PoD -> Post-Delivery Reconciliation`.

## Degradation & Resilience Scenarios (Competition Priority)
- **Scenario 1: Festival Capacity Overload & Equity Mode (`/dispatch/crisis/overload`)** — Algorithmic rationing when aggregate outlet demand exceeds total fleet capacity.
- **Scenario 2: Mid-Load Plan Revision Interruption (`/depot/load/[trip_id]/alert-revision`)** — High-urgency bay alerts when order cancellations occur while loading.
- **Scenario 3: Mid-Route Vehicle Breakdown & Cold-Chain Rescue (`/field/emergency/breakdown`)** — Emergency cargo transfer to an intercepting vehicle before thermal breach.

## Radical Restraint (Zero-Bloat Invariants)
- **No in-app chat or driver social streams** (dispatchers use dispatch directives; drivers focus on driving).
- **No payroll, HR, or invoice calculators** (clean enterprise boundary with external HRMS/ERP).
- **No multi-warehouse inventory management** (handled by enterprise WMS).

## Voice & UI Copy Directives
- **Zero Fluff / Extreme Minimalism:** Ban instructional essays, disclaimers, and meta-text in UI.
- **Metric Density:** Display deltas (`+1.8m³ OVERLOADED`, `14m ETA`) instead of sentences.
- **Micro-copy limits:** 2–4 words per header/button.
