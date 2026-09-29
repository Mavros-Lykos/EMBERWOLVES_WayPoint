# Core Design Tradeoff — Waypoint Dispatch
*Tech-Triathlon 2026 Designathon Submission*

---

## The Central Tradeoff: Breadth of Coverage vs. Depth of Detail

### The Tension

The Challenge Booklet defines a 6-stage workflow across 4 distinct user roles, each operating in radically different physical environments (retail counter, planning office, warehouse dock, truck cab). Designing this system forces a fundamental tradeoff:

- **Option A: Fewer screens, deeper detail.** Design 8–12 screens with elaborate interaction states, micro-animations, and exhaustive edge-case handling. Risk: leaving entire workflow stages unrepresented, making it impossible for a judge to trace the complete delivery cycle.

- **Option B: Complete workflow coverage, focused detail.** Design all screens needed for every role to complete their job end-to-end, with targeted detail on the highest-risk interactions. Risk: perceived as scope sprawl if not carefully justified.

### Our Choice: Option B — Complete Coverage with Strategic Depth

We designed **30 screens** covering the full workflow for all 4 roles, with concentrated depth on the 3 degradation scenarios (which carry 15% judging weight) and the highest-risk operational moments:

```
COMPLETE WORKFLOW COVERAGE (30 screens)
├── Store Manager (6 screens): Order → Track → History → Receive → Dispute
├── Dispatcher (8 screens): Overview → Allocate → Sequence → Defer → Live → Telemetry → Rescue → Crisis
├── Loader (6 screens): Queue → Inspect → Load → Revision Alert → Seal → History
├── Driver (8 screens): PTI → Navigate → Arrive → Offload → Rest → Emergency → Summary → Schedule
├── Auth (1 screen): Judge Quick Launch
└── System (1 screen): Error Fallback

STRATEGIC DEPTH on:
├── DISP-08: Festival Overload — full strategy comparison matrix
├── LOAD-04: Mid-Load Revision — interruption modal with delta display
└── DRV-06: Vehicle Breakdown — SOS broadcast with GPS and issue typing
```

### Why This Is the Right Tradeoff

1. **The booklet requires cross-role connectivity.** *"A dispatcher's decision should reach the loader, and a driver's delivery record should give the store manager information they can act on."* This is only demonstrable when every role's workflow is fully represented. A judge cannot trace the dispatcher's deferral decision through to the store manager's notification if either end is missing.

2. **Degradation scenarios span multiple roles.** Scenario 3 (Vehicle Breakdown) involves the driver (DRV-06: SOS broadcast), the dispatcher (DISP-07: rescue vehicle selection), and the store manager (who sees the updated ETA). Designing only one role's perspective would miss the cross-role impact that makes the scenario operationally significant.

3. **Every screen serves the 6-stage cycle.** We applied the "Restraint" criterion not at the screen count level but at the feature level. No screen contains features outside the core delivery workflow. There are no chat systems, no payroll views, no inventory management, and no analytics dashboards beyond what directly supports allocation decisions.

### The Tradeoff We Accepted

By covering 30 screens, individual screen states are presented in a "representative state" rather than exhaustively covering every possible interaction variant (empty states, error states, loading states for each field). These interaction details are specified in our design guidelines documents and will be implemented during the Hackathon phase.
