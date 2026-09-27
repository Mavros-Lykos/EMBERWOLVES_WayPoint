---
name: Serene Oceanic Light Theme
description: A clean, minimalistic, calming light mode design system for Waypoint Dispatch
colors:
  canvas-base: "#f8fafc"
  surface-dim: "#f1f5f9"
  primary-teal: "#0ea5e9"
  text-primary: "#0f172a"
  text-secondary: "#334155"
  text-muted: "#64748b"
  status-live: "#10b981"
  status-amber: "#f59e0b"
  status-error: "#ef4444"
  glass-bg: "rgba(255, 255, 255, 0.7)"
  glass-border: "rgba(0, 0, 0, 0.08)"
typography:
  primary:
    fontFamily: "Inter, sans-serif"
  mono:
    fontFamily: "JetBrains Mono, monospace"
---

## Overview

The Serene Oceanic Light Theme refines Waypoint Dispatch into an ultra-modern, uncluttered light interface. Built on user feedback requesting a sleek, calming, minimalistic aesthetic, this system eliminates harsh contrasting lines and utilizes soothing whites, slates, and ocean sky-blues.

By leveraging semi-transparent white frosted glass panels (`backdrop-blur`) with extremely subtle dark drop shadows, the interface achieves a sense of deep spatial hierarchy on top of the calming Slate-50 canvas.

---

## Colors

- **Canvas Base (`#f8fafc`)**: A very light, calming Slate 50. It acts as a serene backdrop that prevents eye strain.
- **Glass Surfaces (`rgba(255, 255, 255, 0.7)`)**: Semi-transparent white frosted glass. Provides structural component boundaries without heavy borders.
- **Primary Brand (`#0ea5e9`)**: A soothing, corporate Sky Blue used for main call-to-actions, active rings, and hover glows.
- **Text Hierarchy**: Deep Slate-900 (`#0f172a`) for primary headers, Slate-700 (`#334155`) for secondary labels, Slate-500 (`#64748b`) for tertiary/muted metadata.
- **Ambient Glows**: Extremely faint radial gradients (`rgba(14, 165, 233, 0.08)`) in the background to provide a sense of depth without clutter.

---

## Typography

- **Headlines & Body (Inter)**: Clean, geometric, and exceptionally readable at all sizes.
- **Data (JetBrains Mono)**: Used extensively for tabular data, labels, and metrics. Unstyled and unadorned for precision.

---

## The Impeccable Refactoring

Under the `/impeccable distill` rules, we aggressively strip:
- **Explanatory Text**: Users in this domain don't need paragraphs telling them how to use the tool.
- **Heavy Solid Backgrounds**: Avoid heavy navy or gray blocks. Use glass panels.
- **Dense Text Blocks**: Cut word counts by 70%. Replace sentences with simple scannable KPIs.

---

## Do's and Don'ts

### DO:
- **Use Whitespace**: Let metrics breathe.
- **Use Glass Panels**: `.glass-panel` class handles the heavy lifting for backdrop filters and box shadows.
- **Keep it Calming**: Rely on Sky Blue (`#0ea5e9`) and soft Slates to keep cognitive strain low.

### DON'T:
- **NO Heavy Solid Cards**: Never use dark blocks that destroy the airy, lightweight aesthetic.
- **NO Complex Layouts**: Stick to simple, scannable grids.
