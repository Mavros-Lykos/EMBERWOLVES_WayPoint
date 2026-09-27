---
name: design-system
description: Industry standards for translating Figma designs to code, token naming conventions, typography hierarchy, and UI component architecture.
---

# Design System Implementation Skill

**Context:** The Waypoint Dispatch project strictly adheres to enterprise-grade Design System architecture. Whenever you are building UI components or translating designs from Figma, you must activate this skill.

## 1. Design Token Architecture
Do not hardcode hex values in components. Use a semantic token hierarchy.

### Naming Convention (Semantic over Literal)
- **Backgrounds:** `--color-bg-primary`, `--color-bg-secondary`, `--color-bg-tertiary`
- **Text/Content:** `--color-text-primary`, `--color-text-muted`, `--color-text-inverse`
- **Interactive:** `--color-action-primary`, `--color-action-hover`, `--color-action-disabled`
- **Feedback (Semantic):** `--color-status-success` (emerald), `--color-status-warning` (amber), `--color-status-critical` (terracotta)

*Bad Practice:* `bg-blue-500` or `text-gray-900` hardcoded in 50 places.
*Good Practice:* Mapping Tailwind configuration to the semantic tokens above.

## 2. Figma Handoff & Asset Standards
- **Vector Assets (Icons):** Always export as `SVG`. Strip inline fills/strokes and replace with `currentColor` so the icon inherits the text color of its parent container.
- **Raster Graphics:** Export as optimized `WebP`. Never use PNG/JPG for UI elements unless strictly required for legacy fallback.
- **Auto-Layout:** When analyzing Figma designs, assume elements are built with Flexbox (Figma Auto-Layout). Translate these directly to Tailwind Flex/Grid utilities.

## 3. Component Architecture (Atomic Design)
- **Atoms:** Base UI elements (Buttons, Inputs, Badges). Must be dumb, stateless, and fully driven by props.
- **Molecules:** Groups of atoms functioning together (e.g., A Search Input + Button).
- **Organisms:** Complex, distinct sections of an interface (e.g., The Loader Checklist, The Driver Route Card).

## 4. Spacing & Typography Scale
- **8pt Grid System:** All padding, margin, and sizing must be a multiple of 4 or 8 (e.g., 4px, 8px, 16px, 24px, 32px).
- **Typography Hierarchy:**
  - `Display`: For massive dashboards (36px+)
  - `Heading`: Section titles (20px - 28px)
  - `Body`: Primary reading text (16px) - *Minimum for touch interfaces*
  - `Caption`: Secondary metadata (12px - 14px)

## 5. Accessibility (a11y) & User Control
Logistics workers suffer from eye strain, fatigue, and varying environmental conditions. The UI MUST support:
- **Dynamic Text Scaling:** Base all font sizes on `rem` units, never `px`. The UI must scale gracefully if the user increases system font size by 200%.
- **High-Contrast Toggle:** Include a fallback "Industrial Utility" high-contrast theme option that replaces subtle shades with stark black/white/amber for pure legibility in direct sunlight.
- **Reduced Motion:** Respect `@media (prefers-reduced-motion: reduce)`. Do not use bouncing animations or sliding transitions for drivers; use instant state changes.
- **Screen Reader Readiness:** All icon-only buttons MUST have `aria-label` or visually hidden text (e.g., `<span className="sr-only">Refresh Route</span>`).
