---
name: Glassmorphic Minimalist
description: Unique, minimalist, glassmorphic design system for Waypoint Dispatch
colors:
  bg-canvas: "#050914"
  glass-base: "rgba(255, 255, 255, 0.03)"
  glass-border: "rgba(255, 255, 255, 0.08)"
  glass-hover: "rgba(255, 255, 255, 0.06)"
  primary-blue: "#3b82f6"
  accent-ice: "#38bdf8"
  text-primary: "#ffffff"
  text-secondary: "#94a3b8"
  text-muted: "#64748b"
  status-live: "#10b981"
  status-amber: "#f59e0b"
typography:
  display:
    fontFamily: "Space Grotesk, sans-serif"
    fontSize: "2.25rem"
    fontWeight: 500
    lineHeight: "2.5rem"
    letterSpacing: "-0.02em"
  heading:
    fontFamily: "Space Grotesk, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 500
    lineHeight: "1.75rem"
  body:
    fontFamily: "Geist, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: "1.25rem"
  mono:
    fontFamily: "JetBrains Mono, monospace"
    fontSize: "0.75rem"
    fontWeight: 400
    lineHeight: "1rem"
rounded:
  sm: "6px"
  md: "12px"
  lg: "16px"
  full: "9999px"
spacing:
  xs: "8px"
  sm: "12px"
  md: "24px"
  lg: "32px"
  xl: "48px"
---

## Overview

The Glassmorphic Minimalist design system refines Waypoint Dispatch into an ultra-modern, uncluttered interface. Built on user feedback requesting a sleek, "unique" aesthetic with heavy "bluish nature," this system eliminates unnecessary text, borders, and cognitive noise.

By leveraging blurred backdrops (`backdrop-blur`), semi-transparent surfaces, and ambient glowing orbs, the interface achieves a sense of deep spatial hierarchy without harsh contrasting lines.

---

## Colors

- **Canvas Base (`#050914`)**: A near-black, deep space navy. Serves as the negative space where ambient glows manifest.
- **Glass Surfaces (`rgba(255, 255, 255, 0.02 - 0.08)`)**: Pure white with extremely low opacity. Provides structure without opaque blockage.
- **Ambient Glows**: Large, heavily blurred (e.g., `blur-120px`) radial gradients positioned behind the glass elements, typically using blues and teals.
- **Text Hierarchy**: White for primary data, Slate-400 (`#94a3b8`) for secondary labels, Slate-500 (`#64748b`) for tertiary/muted metadata.

---

## Typography

- **Headlines (Space Grotesk)**: Medium weight, slightly tracked tightly. Used sparsely for core page titles and major metric values.
- **Body (Geist)**: Extremely light and readable.
- **Data (JetBrains Mono)**: Used extensively for tabular data, labels, and metrics. Unstyled and unadorned.

---

## The Impeccable Refactoring

Under the `/impeccable distill` rules, we aggressively strip:
- **Explanatory Text**: Users in this domain don't need paragraphs telling them how to use the tool.
- **Excess Borders**: Removed in favor of faint glass borders (`border-white/5` or `border-white/10`).
- **Heavy Fills**: Replaced with frosted glass panels.

---

## Do's and Don'ts

### DO:
- **Use Whitespace**: Let metrics breathe.
- **Use Glass Panels**: `.glass-panel` class handles the heavy lifting for backdrop filters and box shadows.
- **Keep it Bluish**: Emphasize blues, teals, and cyan tones in glows and text accents.

### DON'T:
- **NO Heavy Solid Cards**: Avoid opaque navy or gray blocks.
- **NO Dense Text Blocks**: Cut word counts by 70%.
- **NO Complex Layouts**: Stick to simple, scannable grids.
