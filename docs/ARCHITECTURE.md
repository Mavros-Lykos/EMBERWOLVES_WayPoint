# Waypoint Dispatch — System Architecture

This document describes the high-level system architecture built for the Waypoint Dispatch Hackathon.

## 1. System Overview

Waypoint Dispatch is built using a monolithic architecture on the **Laravel 11.x** framework, paired with a robust **PostgreSQL** database and Alpine.js for lightweight frontend reactivity.

```mermaid
graph TD
    %% Roles
    SM[Store Manager\n(Priya)]
    DS[Dispatcher\n(Kamal)]
    LD[Loader\n(Nuwan)]
    DV[Driver\n(Saman)]

    %% Frontend Layer
    subgraph Frontend [UI Layer / Alpine.js]
        SM_UI[Store Dashboard\nReacts to cutoff/stock]
        DS_UI[Dispatch Canvas\nDrag & Drop Override]
        LD_UI[Loader Queue\nLIFO Checklist]
        DV_UI[Driver Route\nOffline PWA]
    end

    SM --> SM_UI
    DS --> DS_UI
    LD --> LD_UI
    DV --> DV_UI

    %% Offline sync mechanism for Driver
    DV_UI -.->|localStorage queue| SYNC((Offline Sync))
    SYNC -.->|Background sync| Controller

    %% Backend Layer
    subgraph Backend [Laravel 11 Application]
        Controller[DashboardController\n(Handles all HTTP routes)]
        
        subgraph Services
            AE[AllocationService\n(Greedy Bin-Packing)]
        end
        
        Auth[Auth Middleware\nRole-Based Gates]
    end

    SM_UI --> Auth
    DS_UI --> Auth
    LD_UI --> Auth
    DV_UI --> Auth
    
    Auth --> Controller
    Controller --> AE

    %% Database Layer
    subgraph Storage [PostgreSQL Database]
        DB[(Waypoint DB)]
    end

    Controller --> DB
    AE --> DB

    %% Event Loops
    DB -.-> |Triggers| DB
```

## 2. Allocation Engine Constraints (Greedy Bin-Packing)

The `AllocationService` implements a greedy heuristic to pack orders into vehicles, respecting the following constraints from the SRS:
1. **Brand & District:** Vehicles must only serve outlets of the same brand and district in a single trip.
2. **Reefer Constraint:** Chilled/Frozen orders must go to `reefer` vehicles.
3. **Weight & Volume:** Accumulated order dimensions must not exceed the vehicle's `weight_cap_kg` and `volume_cap_m3`.
4. **Prioritization:** Orders are sorted primarily by `days_since_last_served` DESC and `urgency_flag` DESC to prevent silent deferral loops.

## 3. Data Integrity & Resilience

1. **Database Triggers:** We use native PostgreSQL triggers to enforce the Brand/District constraints at the database level.
2. **Offline Mode (PWA):** The Driver interface operates as a Progressive Web App (PWA) with a `sw.js` Service Worker caching assets and Alpine.js persisting mutation logs to `localStorage` when driving through dead zones (e.g. Hill Country).
3. **Deferral Logging:** Every skipped order generates a row in `deferral_log` tracking the *reason code* and the exact *dispatcher* who approved the deferral.

## 4. UI/UX Paradigm
- **Store Manager:** Light mode by default, large visual alerts for ETA and discrepancies.
- **Dispatcher:** Dark mode, data-dense tabular structures, high contrast for actionable exceptions.
- **Driver:** High-contrast dark mode for battery preservation, 64px+ touch targets for fatigued operation, strictly sequential flows.
