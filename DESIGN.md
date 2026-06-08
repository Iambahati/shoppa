# Design

## Overview

Shoppa is a warm, product-focused marketplace UI. The visual system favors a clean, approachable canvas with strategic sky-blue and blush accents. Rounded sans-serif typography and soft geometric forms reinforce the friendly personality. Auth surfaces balance returning and new-user actions with equal visual weight.

## Theme

- **Mode**: Light (warm, airy)
- **Energy**: Calm and welcoming, not aggressive or playful
- **Atmosphere**: A bright, friendly shopfront feel. Think clean white walls with a soft blue awning and a blush accent sign.

## Color

### Strategy

Restrained palette with two named accents (sky and blush) on a true neutral ground. The body background is a clean near-white with a barely perceptible cool tint, not a warm cream.

### Palette

| Token | Hex | Usage |
|---|---|---|
| `--bg` | `#f8fafc` | Page background (cool near-white) |
| `--surface` | `#ffffff` | Cards, form panels |
| `--ink` | `#1e293b` | Primary text, headings |
| `--ink-secondary` | `#64748b` | Body copy, placeholders, labels |
| `--sky` | `#38bdf8` | Primary accent: links, focus rings, active states, illustration accents |
| `--sky-dark` | `#0284c7` | Hover states for sky elements |
| `--blush` | `#f472b6` | Secondary accent: highlights, decorative shapes, CTAs where contrast allows |
| `--blush-dark` | `#db2777` | Hover states for blush elements |
| `--warm-gray` | `#e2e8f0` | Subtle borders, dividers, disabled backgrounds |
| `--success` | `#10b981` | Existing emerald retained for success states / verified badges |

### Usage rules

- Body text on `--bg` or `--surface` must hit ≥4.5:1. `--ink-secondary` on `--surface` is 5.2:1 (safe).
- Placeholder text uses `--ink-secondary` at full opacity, not a lighter gray.
- Accent colors are used sparingly: sky for interactive elements, blush for decorative / secondary emphasis.

## Typography

### Family

- **Primary**: `Nunito` (rounded sans-serif, 400/500/600/700) via Google Fonts or Bunny Fonts.
- **Fallback**: system-ui, sans-serif.
- **Mono**: `JetBrains Mono` (retained from existing system for code/technical surfaces).

### Scale

| Token | Size | Weight | Usage |
|---|---|---|---|
| `display` | `2rem` (32px) | 700 | Auth page headings |
| `heading` | `1.25rem` (20px) | 600 | Section titles, card headers |
| `body` | `1rem` (16px) | 400 | Form labels, input text |
| `small` | `0.875rem` (14px) | 500 | Buttons, supporting text |
| `caption` | `0.75rem` (12px) | 500 | Fine print, helper text |

### Rules

- Display headings use `text-wrap: balance`.
- Measure capped at ~65ch for any prose block.
- Single font family; weight contrast creates hierarchy.

## Spacing

- Base unit: `4px` (Tailwind default).
- Auth card padding: `2rem` (32px).
- Section gaps: `1.5rem` (24px) to `2rem` (32px).
- Form field gaps: `1.25rem` (20px).

## Components

### Buttons

- **Primary**: `bg-sky-500 text-white rounded-lg px-5 py-2.5 font-medium`.
  - Hover: `bg-sky-600`.
  - Focus-visible: `ring-2 ring-sky-400 ring-offset-2`.
- **Secondary / Link-style**: Text with sky color and underline on hover.

### Inputs

- `bg-white border border-warm-gray rounded-lg px-4 py-2.5 text-ink`
- Focus: `border-sky-400 ring-2 ring-sky-100`
- Error: `border-red-400 ring-2 ring-red-100`
- Placeholder: `text-ink-secondary`

### Cards / Panels

- `bg-surface rounded-2xl shadow-sm border border-warm-gray/50`
- No nested cards.
- Border radius: `1rem` (16px) max for panels.

## Layout

### Auth pages (login / register)

- **Desktop**: Split layout.
  - Left pane (~55%): Clean white/light background with friendly illustration (abstract soft shapes, sky + blush blobs) and community-oriented messaging.
  - Right pane (~45%): Centered form card with heading, fields, and balanced sign-in / sign-up CTAs.
- **Tablet / Mobile**: Stacked. Illustration collapses to a top banner; form fills the viewport.
- **Maximum form width**: `420px`.

### Responsive

- Breakpoints follow Tailwind defaults.
- `lg` (1024px) triggers split → stacked transition.
- Touch targets ≥ 44×44 px.

## Motion

- **Approach**: Purposeful but gentle. Motion should feel like a door softly opening, not a curtain dramatically rising.
- **Easing**: `ease-out` / `cubic-bezier(0.25, 1, 0.5, 1)` for entrances.
- **Durations**: `200ms` for micro-interactions (focus, hover), `400ms` for page transitions.
- **Reduced motion**: `@media (prefers-reduced-motion: reduce)` disables transforms; use opacity-only or instant transitions.

## Assets

- **Illustration style**: Clean, friendly vector shapes (circles, soft arcs) in sky and blush. No hand-drawn sketchy SVGs.
- **Icons**: Use the project's existing icon set (Lucide via Laravel / Blade components) for consistency.

## Dependencies

- Tailwind CSS (existing)
- Vite (existing)
- Nunito font (to be added via Bunny Fonts)
