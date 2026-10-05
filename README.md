# 🚚 Waypoint Dispatch System — Tech Triathlon 2026

Welcome to the Waypoint Dispatch repository. This system is a fully functional, constraint-aware logistics platform built to handle the rigorous demands of S-1 peak day scenarios.

---

## 📝 Hackathon Submission Details

*   **Repository Link:** [EMBERWOLVES_WayPoint](https://github.com/Mavros-Lykos/EMBERWOLVES_WayPoint) 
*   **Deployed System URL:** [emberwolveswaypoint-production.up.railway.app](emberwolveswaypoint-production.up.railway.app) 
*   **Demo Video:** [YouTube/Vimeo Link](https://youtube.com/...) 
*   **Design Changes from Day 5:** No significant changes from Day 5 design. *(Update if there are any)*

### 🔑 Seeded Accounts Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| Store Manager | `priya@waypoint.lk` | `priya2026` |
| Dispatcher | `kamal@waypoint.lk` | `kamal2026` |
| Loader | `nuwan@waypoint.lk` | `nuwan2026` |
| Driver | `saman@waypoint.lk` | `saman2026` |

---

## 🏆 Judge Walkthrough Guide

This guide outlines exactly how to navigate the application and verify that all requirements from the **Tech Triathlon 2026 Challenge** have been successfully implemented.

### 🚀 Setup & Launch
1. Ensure the system is running via Docker Compose:
   ```bash
   docker compose up -d
   ```
2. Run database migrations and seed the peak day scenarios:
   ```bash
   docker compose exec app php artisan migrate:fresh --seed
   ```
3. Open your browser to `http://localhost:8000/login`.

---

### 👨‍💼 Phase 1: Store Manager Experience
**Objective:** Verify stock ordering, urgency flags, and dynamic delivery tracking.

1. **Login:** Use the magic login or log in as a **Store Manager**.
2. **Dashboard UI:** Notice the high-fidelity UI matching the provided prototypes.
3. **Place Order:** Click the **"Order"** button in the sidebar.
    *   Select "Frozen" or "Chilled" and input units.
    *   Check the **"Urgency Flag"** checkbox.
    *   Submit the order.
4. **Validation:** Ensure the order appears in the "All orders" table with a status of `Pending`.
5. **Cutoff Logic:** If you place an order after 16:00, the system automatically flags it for the next operating day.

---

### 🧑‍💻 Phase 2: Dispatcher & The Allocation Engine
**Objective:** Verify the 20% grading criteria — the constraint-aware Allocation Engine.

1. **Login:** Log in as the **Dispatcher** (Peliyagoda DC).
2. **Overview Dashboard:**
    *   Observe the "Pending Orders" aggregate. It should reflect the seeded 85 pending orders from the S1 peak day scenario.
    *   Observe the Fleet Breakdown and Capacity bars.
3. **Run Allocation Engine:** Click the **"Run Allocation Engine"** button.
    *   **What happens behind the scenes:** The `AllocationService.php` runs. It evaluates all 10 Feasibility Rules (FR-001 to FR-010).
    *   It checks Vehicle Capacities (Weight/Volume).
    *   It checks Reefer requirements (Chilled -> Reefer truck).
    *   It checks Parking Constraints (Van Only -> Van).
    *   It checks Time Budgets (Max 4.5 hrs for Fresh, 8 hrs for Style/Tech).
    *   It creates Trips and allocates Route Legs.
4. **Result:** The system will report how many orders were successfully allocated and how many were deferred (e.g., due to volume limits or lack of reefer vans).
5. **View Trips:** Scroll down to the "Active Trips" table to see the newly generated trips grouped by Brand/District.

---

### 👷 Phase 3: Loading Bay Worker
**Objective:** Verify Reverse-LIFO logic and loading shortfalls.

1. **Login:** Log in as the **Loader**.
2. **Queue Screen:** Notice the active Trip assigned to the bay.
3. **LIFO Checklist:** The system forces a Reverse-LIFO loading sequence. The *last stop* on the route is loaded *first*.
4. **Flag Shortfall:** Click "Flag Shortfall" on a route leg. Log an exception (e.g., "Warehouse out of stock").
5. **Seal & Dispatch:** Complete the checklist and click "Complete Loading & Seal". Enter a dummy seal number (e.g., `SL-9999`) to dispatch the truck.

---

### 🚚 Phase 4: Driver (Offline PWA)
**Objective:** Verify Proof of Delivery (PoD) and offline resilience.

1. **Login:** Log in as the **Driver**.
2. **Pre-Trip Inspection (PTI):** Complete the digital checklist (Tires, Brakes, Reefer Temp, Seal check). Click **"Unlock Route"**.
3. **Offline Mode Test:**
    *   *Simulate Offline:* Disconnect your internet or set the browser to "Offline" via DevTools.
    *   Click **"Arrived"** at the next stop. The UI will work seamlessly and queue the event.
    *   Click **"Complete Delivery"**, sign the digital canvas, and submit. The system will save it locally.
    *   *Simulate Online:* Reconnect. Click the "Sync" badge at the top to push all cached data to the server.
4. **Exceptions:** Use the "Issue" button to report blocked access or closed outlets.
5. **Trilingual Support:** Test the EN | සිං | தமிழ் switcher in the top right.

---

### 🔐 Phase 5: Security & Code Quality Check
*   **Role Isolation:** Try accessing `/dispatch/overview` while logged in as a Store Manager. You will be blocked by middleware.
*   **Clean Code:** Review `app/Services/AllocationService.php`. The logic is isolated from controllers, fully commented, and maps directly to the SRS rules.
*   **Architecture Documentation:** Review the `docs/ARCHITECTURE.md` file for Mermaid diagrams and scaling philosophy.

---

## 🛠️ Tech Stack & Architecture

Waypoint Dispatch is built with modern, enterprise-grade technologies optimized for high performance, rapid deployment, and offline resilience in logistics environments.

### Core Technologies
*   **Backend Framework:** [Laravel 11](https://laravel.com/) (PHP 8.2+) — providing robust routing, ORM (Eloquent), and the heavy-lifting logic for the Allocation Engine.
*   **Database:** [PostgreSQL](https://www.postgresql.org/) — ensuring ACID compliance for critical transactional data and robust relational mapping for complex logistics constraints.
*   **Frontend UI:** Laravel Blade templating combined with semantic HTML5 and scoped vanilla CSS. The UI heavily utilizes CSS grid/flexbox, CSS variables for theming, and modern glassmorphism aesthetics to meet strict design fidelity standards.
*   **Reactivity:** [Alpine.js](https://alpinejs.dev/) — providing lightweight, declarative reactivity for the frontend without the overhead of heavy SPA frameworks. Used for modal states, dropdowns, and offline synchronization queues.

### Specialized Integrations
*   **Offline Support (PWA):** Custom Service Workers (`sw.js`) and `localStorage` caching ensure the Driver app remains 100% operational in dead zones.
*   **Proof of Delivery (PoD):** Integration with `signature_pad.js` for digital sign-offs and native HTML5 Geolocation API for automatic coordinate stamping upon delivery completion.
*   **Localization:** Built-in Laravel localization (`__('')` helpers) providing a fully trilingual interface (English, Sinhala, Tamil) accessible instantly via UI toggles.

### Deployment & DevOps
*   **Containerization:** Multi-stage `Dockerfile` and `docker-compose.yml` for guaranteed environment parity across local development and production.
*   **Hosting:** Seamlessly deployed on [Railway](https://railway.app/) using automated CI/CD directly from the GitHub repository main branch.

---

## 📁 Project Structure

```
EMBERWOLVES_WayPoint/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Login, logout, magic-link auth
│   │   │   ├── DashboardController.php     # All role-based page controllers (main hub)
│   │   │   └── NotificationController.php  # Real-time notification API
│   │   └── Middleware/
│   │       ├── RoleMiddleware.php          # Guards routes by user role
│   │       └── SetLocale.php               # Per-request locale injection from session
│   ├── Models/                             # 16 Eloquent models mapping domain tables
│   │   ├── Order.php | Trip.php | Vehicle.php | Outlet.php
│   │   ├── RouteLeg.php | DeliveryConfirmation.php | DeferralLog.php
│   │   └── SyncMutation.php | LoadingException.php | ...
│   ├── Services/
│   │   ├── AllocationService.php           # ⭐ Core constraint-aware greedy bin-packer
│   │   └── RoutingService.php              # Route leg sequencing and distance calc
│   ├── Events/ & Listeners/                # Laravel event system for notifications
│   └── Notifications/                      # Broadcast notification payloads
│
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_domain_schema.php    # ⭐ Full PostgreSQL schema with ENUMs & triggers
│   │   └── ..._create_notifications_table.php
│   └── seeders/
│       └── DatabaseSeeder.php              # S-1 peak day dataset (85 orders, 10 vehicles)
│
├── resources/
│   ├── views/
│   │   ├── auth/login.blade.php            # Glassmorphism login with magic-links
│   │   ├── store/dashboard.blade.php       # Store Manager portal
│   │   ├── dispatch/
│   │   │   ├── overview.blade.php          # Fleet command centre
│   │   │   ├── live.blade.php              # Live trip tracking
│   │   │   └── plan.blade.php              # Day planning & deferral management
│   │   ├── driver/
│   │   │   ├── route.blade.php             # Offline-capable PWA route manifest
│   │   │   └── trip-end.blade.php          # End-of-day summary
│   │   ├── loader/queue.blade.php          # Loading bay LIFO queue
│   │   └── partials/
│   │       ├── settings.blade.php          # Reusable theme & language switcher
│   │       └── notifications.blade.php     # Real-time notification bell
│   └── css/ & js/                          # Vite-compiled assets
│
├── routes/web.php                          # All 30+ routes, role-prefixed and grouped
├── lang/
│   ├── en.json | si.json | ta.json         # Trilingual translations
├── public/
│   ├── sw.js                               # Service Worker for offline PWA
│   └── manifest.json                       # PWA web app manifest
├── Dockerfile                              # Alpine-based production container
├── docker-compose.yml                      # Local dev with PostgreSQL service
└── docs/ARCHITECTURE.md                    # Mermaid diagrams & scaling philosophy
```

---

## 🗄️ Database Schema

The schema is defined in a **single migration** using raw PostgreSQL DDL for maximum fidelity with the SRS specification.

### Custom PostgreSQL ENUMs

| ENUM Type | Values |
| :--- | :--- |
| `order_status` | `pending`, `allocated`, `deferred`, `loaded`, `in_transit`, `delivered`, `failed` |
| `trip_status` | `planned`, `loading`, `dispatched`, `completed` |
| `temp_req` | `ambient`, `chilled`, `reefer` |
| `vehicle_type` | `truck`, `van` |
| `parking_constraint` | `normal`, `van_only`, `mall_dock` |
| `deferral_reason_code` | `NO_REEFER_VAN`, `CAPACITY_EXCEEDED`, `TIME_BUDGET`, `VOLUME_OVER_ANY_VEHICLE`, `NO_REEFER_CAPACITY`, `EQUITY_CHOICE`, ... |

### Core Tables

| Table | Purpose |
| :--- | :--- |
| `users` | Extended with `role`, `depot`, `vehicle_id`, `outlet_id` columns |
| `outlets` | Retail outlet reference data with dock type and delivery windows |
| `vehicles` | Fleet data with capacity, reefer capability, depot, and fuel quota |
| `orders` | Customer orders with urgency flag, deferred state, and trip linkage |
| `trips` | Daily dispatch trips grouped by brand + district (max 2/vehicle/day) |
| `route_legs` | Individual stop records with planned/actual timestamps and reefer temp |
| `delivery_confirmations` | PoD records — signature data, photo path, unit count |
| `sync_mutations` | Offline JSONB mutation queue for field sync |
| `loading_exceptions` | Shortfall and exception records logged by loaders |
| `deferral_log` | Auditable deferral history with reason codes |

### Database Integrity — PostgreSQL Triggers

Two database-level triggers enforce business rules that go beyond application-layer validation:

1. **`enforce_trip_grouping`** — Prevents any order from being attached to a trip with a mismatched `brand` or `district`. This is a hard DB-level guard for `FR-001`.
2. **`enforce_reefer_requirement`** — Prevents a `chilled` order from ever being allocated to a non-reefer vehicle, even via direct SQL. Hard guard for `FR-002`.

---

## ⚙️ Allocation Engine — Feasibility Rules

The `AllocationService.php` implements a **constraint-aware greedy bin-packing algorithm** that evaluates all 10 SRS feasibility rules.

| Rule | Code | Description |
| :--- | :--- | :--- |
| FR-001 | `BRAND_DISTRICT` | Orders are grouped by Brand + District before allocation |
| FR-002 | `NO_REEFER_CAPACITY` | Chilled/reefer orders must use a temperature-controlled vehicle |
| FR-003 | `NO_VAN` | Outlets with `van_only` parking constraint require a van-type vehicle |
| FR-004 | `DEPOT_MISMATCH` | Vehicle and outlet must share the same depot hub |
| FR-005 | `WEIGHT_EXCEEDED` | Cumulative order weight cannot exceed vehicle `weight_cap_kg` |
| FR-006 | `VOLUME_OVER_ANY_VEHICLE` | Single order volume cannot exceed the largest available vehicle |
| FR-007 | — | Maximum 2 trips per vehicle per operating day |
| FR-008 | `TIME_BUDGET` | Fresh brand trips capped at 4.5 hours (270 min) |
| FR-009 | `TIME_BUDGET` | Style/Tech brand trips capped at 8 hours (480 min) |
| FR-010 | `EQUITY_CHOICE` | Urgency-flagged and long-waiting orders are prioritised first |

**Priority Queue**: Orders are sorted `urgency_flag DESC`, `days_since_last_served DESC` before processing, ensuring equity for chronically under-served outlets.

---

## 🔐 Security & Middleware

```
Route /store/*       → auth + role:store_manager
Route /dispatch/*    → auth + role:dispatcher
Route /loader/*      → auth + role:loader
Route /driver/*      → auth + role:driver
```

-   **`RoleMiddleware`**: Compares `auth()->user()->role` against the required role. Returns `403 Forbidden` on mismatch, preventing any horizontal privilege escalation.
-   **`SetLocale`**: Reads `session('locale')` on every request and calls `App::setLocale()`, ensuring all `__()` translation calls render in the correct language.
-   **CSRF Protection**: All `POST`/`PUT`/`DELETE` routes use Laravel's built-in `@csrf` token verification.

---

## 🌐 API Endpoints Overview

All routes are defined in [`routes/web.php`](routes/web.php) and protected by authentication middleware.

| Method | URI | Role | Action |
| :--- | :--- | :--- | :--- |
| `POST` | `/login` | Public | Authenticate user |
| `GET` | `/magic-login/{role}` | Public | One-click hackathon demo login |
| `GET` | `/store/dashboard` | Store Manager | Main dashboard |
| `POST` | `/store/order` | Store Manager | Place a new supply order |
| `POST` | `/store/accept` | Store Manager | Confirm delivery receipt |
| `GET` | `/dispatch/overview` | Dispatcher | Fleet command centre |
| `POST` | `/dispatch/allocate` | Dispatcher | **Run allocation engine** |
| `GET` | `/dispatch/live` | Dispatcher | Real-time trip tracking |
| `GET` | `/loader/queue` | Loader | Loading bay queue |
| `POST` | `/loader/dispatch` | Loader | Seal & dispatch truck |
| `POST` | `/loader/exception` | Loader | Log a loading shortfall |
| `GET` | `/driver/route` | Driver | Offline PWA route manifest |
| `POST` | `/driver/arrival` | Driver | Mark arrived at stop (offline-queued) |
| `POST` | `/driver/confirm-delivery` | Driver | Submit PoD with signature + GPS |
| `GET` | `/api/notifications` | All | Fetch unread notifications |
| `GET` | `/lang/{locale}` | All | Switch language (en/si/ta) |

---

## 🚀 Local Development Setup

### Prerequisites

| Requirement | Version |
| :--- | :--- |
| PHP | ≥ 8.2 |
| Composer | ≥ 2.x |
| Node.js | ≥ 18.x |
| PostgreSQL | ≥ 14 OR Docker |

### Option A — Docker (Recommended)

```bash
# 1. Clone the repository
git clone https://github.com/Mavros-Lykos/EMBERWOLVES_WayPoint.git
cd EMBERWOLVES_WayPoint

# 2. Copy environment file
cp .env.example .env

# 3. Start the containers (app + postgres)
docker compose up -d

# 4. Install dependencies and seed the S-1 dataset
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed

# 5. Open in browser
open http://localhost:8000/login
```

### Option B — Bare Metal

```bash
git clone https://github.com/Mavros-Lykos/EMBERWOLVES_WayPoint.git
cd EMBERWOLVES_WayPoint

cp .env.example .env
# Edit .env — set DB_CONNECTION=pgsql and DB_* credentials

composer install
php artisan key:generate
npm install && npm run build

php artisan migrate:fresh --seed
php artisan serve
```

---

## 🌍 Localization (Trilingual Support)

The application ships with full translations for **English**, **Sinhala**, and **Tamil**, accessible via the settings gear icon on every page.

| Language | File | Code |
| :--- | :--- | :--- |
| English | `lang/en.json` | `en` |
| Sinhala | `lang/si.json` | `si` |
| Tamil | `lang/ta.json` | `ta` |

Language preference is persisted in the PHP session. All UI strings use `{{ __('key') }}` helpers which resolve to the active locale's JSON file.

---

## 🧱 Key Engineering Decisions

| Decision | Rationale |
| :--- | :--- |
| **Service Layer Pattern** | `AllocationService` and `RoutingService` are decoupled from controllers, keeping the HTTP layer thin and the business logic independently testable. |
| **PostgreSQL ENUMs + Triggers** | Business invariants (reefer requirements, brand grouping) are enforced at the database level — not just the application layer — making them impossible to bypass. |
| **Alpine.js over a full SPA** | Avoids build complexity and JavaScript framework overhead while still achieving rich UI reactivity for modals, state, and offline sync queues. |
| **Service Worker + localStorage** | The driver PWA remains functional with zero connectivity. Mutations are stored as a JSON queue and flushed on reconnection. |
| **LIFO Loading Order** | The loader queue enforces Reverse-LIFO (last stop loaded first) to ensure the first-stop's goods are accessible at the truck's front — a real-world cold-chain best practice. |
| **Greedy + Priority Queuing** | The allocation engine processes urgency-flagged orders first, then longest-unserved outlets, satisfying equity constraints alongside hard feasibility rules. |

---

## 👥 Team

**Team EMBERWOLVES** — Tech Triathlon 2026

Built under pressure in a competitive hackathon environment, this system demonstrates production-grade engineering discipline from schema design to DevOps.

---

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
  </a>
</p>

<p align="center"><sub>Built with ❤️ by Team EMBERWOLVES · Tech Triathlon 2026</sub></p>
