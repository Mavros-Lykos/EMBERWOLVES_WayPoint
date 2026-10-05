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

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo"></a></p>
