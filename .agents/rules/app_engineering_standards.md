---
description: Enforces flawless application engineering, advanced accessibility, and state management.
trigger: always_on
---

# Perfect Application Engineering Standards

All agents must adhere to the following exhaustive standards when generating application code (HTML, JS, CSS, React, etc.) so that "nothing remains silent" and every app acts like a flawless, professional, production-ready system.

## 1. Advanced Navigation Architecture
*   **Contextual Routing**: Never use raw `<a>` tags without considering the routing context. For SPAs, use router wrappers. For standard HTML, ensure logical directory structures.
*   **Active State Indication**: The currently active navigation route MUST ALWAYS be visually distinguished (e.g., `text-primary-teal`, increased font-weight, or a structural active indicator bar).
*   **Breadcrumbs & Deep Linking**: Deep nested pages must always include a clear back button or breadcrumb trail allowing the user to return to the parent context instantly.

## 2. Accessibility (A11y) & Keyboard Navigation
*   **ARIA Attributes**: All interactive elements that are not native `<button>` or `<a>` elements MUST have `role="..."` and `tabindex="0"`.
*   **Screen Reader Context**: Use `aria-label`, `aria-describedby`, and `aria-hidden="true"` on purely decorative icons. 
*   **Focus Management**: 
    *   No "silent" focuses. Every focusable element MUST have a highly visible `:focus` or `:focus-visible` state (e.g., `focus:ring-2 focus:ring-primary-teal focus:outline-none`).
    *   When a modal opens, focus MUST be trapped inside the modal. When it closes, focus MUST return to the trigger element.

## 3. Event Handling & State Feedback
*   **No "Silent" Interactions**: Every interactive element (button, chip, table row) MUST have instantaneous visual feedback for:
    *   `Hover`: (e.g., brighten background, slight elevation)
    *   `Active / Click`: (e.g., slight scale down `active:scale-95`, darker background)
    *   `Disabled`: (e.g., `opacity-50`, `cursor-not-allowed`)
*   **Loading States**: All async events (API calls, form submissions) must instantly transition the triggering button to a loading state (e.g., showing a spinner) and disable it to prevent double-submissions.
*   **Error Handling Feedback**: Errors must trigger a clear, contrasting visual warning (e.g., a toast notification or inline red text). Never fail silently in the console.

## 4. DOM and Performance Constraints
*   **Semantic HTML**: Use `<header>`, `<main>`, `<nav>`, `<section>`, and `<article>`. Do not rely purely on nested `<div>`s.
*   **Responsive Fluidity**: UIs must never break on small screens. Use CSS grid/flexbox and responsive prefixes (e.g., `lg:`, `md:`) extensively. Container queries (`@container`) are preferred for components.
*   **Debouncing**: All continuous events (scroll, window resize, search input typing) MUST be debounced to avoid layout thrashing and CPU spikes.

## 5. UI/UX Consistency Enforcement
*   **Theme Binding**: Never hardcode hex values in HTML files. Always bind to the CSS variables or Tailwind configuration classes.
*   **Micro-interactions**: Everything that can be interacted with should have a subtle CSS `transition` (e.g., `transition: all 0.3s ease;`).

Follow these rules unconditionally for all future screens and components. A perfect app is one that communicates its state back to the user instantly, flawlessly, and accessibly.
