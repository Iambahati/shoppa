# Shoppa Design System

**Trust-as-a-Service electronics marketplace for Kenya & East Africa.**

Shoppa does not sell devices — it *verifies* them. Every device is physically
inspected by a licensed Verifier before listing; a cryptographically signed
**Trust Certificate** (queryable by IMEI or serial) is issued per device;
buyers pay into escrow that releases only on confirmed receipt. The
verification pipeline and escrow engine *are* the product — listings, orders
and payments are infrastructure around that trust core. Never treat Shoppa as
"just another marketplace": the **Verified** mark is the brand.

This design system is reverse-engineered from the real Laravel + Blade +
Tailwind codebase, so every token, component and screen matches what ships.

---

## Sources

- **GitHub — [`Iambahati/shoppa`](https://github.com/Iambahati/shoppa)** *(main)* — the
  Laravel 11 / Blade / Alpine.js / Tailwind app. Primary source of truth.
  Worth exploring further to build higher-fidelity designs:
  - `tailwind.config.js` — emerald brand + stone neutrals, Inter / JetBrains Mono, rounded scale.
  - `resources/css/app.css` — `.card`, `.form-input`, table + page-heading patterns.
  - `resources/views/components/ui/*` — Button, Badge, Alert.
  - `resources/views/components/trust/*` — **VerifiedPill, CertBadge** (the brand core).
  - `resources/views/components/{nav,card,form}/*` — Sidebar, Topbar, Icon, StatCard, Field.
  - `resources/views/layouts/{guest,dashboard}.blade.php` — auth + app shells.
  - `resources/views/pages/{auth,buyer,vendors,admin}/*` — the real screens.

> The repo's `welcome.blade.php` is still the default Laravel starter page —
> there is **no marketing site yet**, so this system covers the *authenticated*
> product (auth + console) only. When the landing page is built, add a kit for it.

---

## Content fundamentals

**Voice — warm, clear, trust-first. Never corporate or cold.**

- **Second person, friendly.** "Welcome back", "Sign in to your Shoppa account",
  "Buy and sell verified electronics with confidence", "Create one free". The UI
  talks *to* the user like a trusted local seller, not at them.
- **Plain over clever.** Labels say exactly what they do — "Keep me signed in",
  "Add listing", "Email me a reset link". No jargon ("authenticate your session"),
  no hype ("Sign up now!!"), no enterprise filler ("leverage our solution").
- **Trust is stated plainly, repeatedly.** Microcopy reinforces the core promise:
  "Every device on Shoppa is physically inspected before listing. Your purchase is
  protected by escrow until you confirm delivery."
- **Local and concrete.** Kenyan names (Jane Wanjiru, Otieno), `KSh` currency with
  thousands separators, `+254` phone format, M-Pesa as the payment rail.
- **Casing.** Sentence case everywhere — headings, buttons, labels. The only
  uppercase is table column headers (tracked-out, 12px, stone-500). The wordmark
  is "Shoppa"; the trust tag is lowercase "verified".
- **Machine identity is monospaced.** Cert UUIDs, IMEIs and serials always render
  in JetBrains Mono so they never blend into prose.
- **No emoji** in product UI. Status is carried by the icon + colour system. The
  only glyph that does brand work is the filled **check-badge**.
- **Punctuation.** A right-arrow "→" ends "view more" affordances; a middot "·"
  separates inline meta (`#9F2A17C4 · 02 May 2026`).

---

## Visual foundations

**Overall feel:** a warm, trustworthy fintech-grade marketplace. Calm stone
canvas, confident emerald for anything verified or actionable, generous
rounding, soft low-contrast shadows. Restraint is the point — colour is earned,
never decorative.

- **Colour.** Emerald is the brand and the single accent: `emerald-600` for
  primary actions, `emerald-700` for hover/pressed, `emerald-50/100` for tinted
  trust surfaces. Neutrals are **stone** (warm grey) — never cool slate/zinc.
  `stone-900` carries headings and the sidebar; `stone-50/100` are the canvases.
  Semantic colours appear **only** as status: amber = pending/awaiting, blue =
  in review/info, red = rejected/error, purple = staff roles. See the Colors cards.
- **Type.** Inter for all UI (400/500/600/700); headings are semibold with
  `-0.025em` tracking. JetBrains Mono for machine identity. Body is 14px; page
  headings 20px; stat values 24px. Nothing smaller than 12px.
- **Spacing.** 4px base grid. Card padding 20px (`space-5`); section gaps 32px
  (`space-8`); sidebar/topbar height 64px.
- **Corners.** Friendly but not bubbly: inputs & buttons `lg` (8px), cards `xl`
  (14px — a deliberate Tailwind override), panels `2xl` (20px), pills & avatars
  full. Badges are `md` (6px).
- **Cards & elevation.** The signature surface is white with a **hairline ring
  (`ring-stone-950/5`) plus `shadow-sm`** — a ring-first look, not a heavy drop
  shadow. Raised menus use `shadow-lg`. No borders-as-decoration; hairlines are
  `stone-100`/`stone-200`.
- **Borders & focus.** Inputs are borderless with an inset `stone-300` ring that
  thickens to a 2px `emerald-600` ring on focus (red on error). This emerald
  focus ring is consistent across every interactive control.
- **Backgrounds.** Flat warm neutrals only — **no gradients, no patterns, no
  imagery** in the current product. Trust callouts use a flat `emerald-50` fill
  with an `emerald-200` border. Device photography (when present) lives inside
  cards, never full-bleed.
- **Sidebar.** Dark `stone-900` surface, `stone-400` idle nav text that goes
  white on `stone-800` hover/active — the one dark region in an otherwise light UI.
- **Motion.** Subtle and fast. Colour/background transitions 150ms; nav hovers
  100ms; dropdowns scale-and-fade 100–200ms with `ease-out`. The only looping
  animation is the button spinner. Honour `prefers-reduced-motion`.
- **Hover / press.** Primary buttons lighten (`600 → 500`) on hover; secondary &
  ghost darken their background tint; links shift `emerald-600 → 700`. Presses
  rely on colour, not scale.
- **Transparency / blur.** Used sparingly — the mobile sidebar scrim is
  `stone-950/40`; emerald ring tints use `rgba(5,150,105,0.20)`. No glassmorphism.
- **Imagery vibe.** When product photos arrive they should read clean, bright and
  neutral-warm on white — catalogue-honest, not moody. (None ship today.)

---

## Iconography

- **Heroicons** (outline, 1.5 stroke, 24×24) are the icon language. The product
  hand-rolls a path registry in `components/nav/icon.php` to stay off a CDN — we
  mirror that exactly in the **`Icon`** component (`components/navigation/Icon.jsx`),
  so the same names work here: `home, search, box, layers, users, store, package,
  flag, shield, cpu, message-sq, bell, user, check, x, chevron-r, chevron-d, bars,
  qr, plus, arrow-right`.
- **Filled trust glyphs.** Two solid Heroicons do brand work and only appear in
  the trust system: **`check-badge`** (the VerifiedPill mark) and **`shield-check`**
  (verified CertBadge). Access via `<Icon name="check-badge" solid />`.
- **Sizing.** 16px inside buttons & nav, 18–20px for stat chips & topbar, colour
  always `currentColor` so icons inherit their context.
- **No emoji, no PNG icons, no multicolour icons.** One stroke weight, one family.
- *Substitution note:* the codebase ships no logo image — the brand is the
  wordmark "Shoppa" + the lowercase emerald "verified" pill (see Brand cards). If
  you have an actual logomark, drop it into `assets/` and update the lock-up.

---

## Index / manifest

**Foundations** (root)
- `styles.css` — the single entry point consumers link. `@import`s the four token files.
- `tokens/colors.css` · `typography.css` · `spacing.css` · `fonts.css` — CSS custom properties + webfont loading.

**Components** (`components/<group>/` — React primitives, each with `.jsx` + `.d.ts` + `.prompt.md` + a group card)
- `forms/` — **Button**, **Input**, **Checkbox**
- `data/` — **Badge**, **Avatar**, **StatCard**
- `feedback/` — **Alert**
- `trust/` — **VerifiedPill**, **CertBadge** *(the brand core)*
- `navigation/` — **Icon**

**UI kits** (`ui_kits/<product>/`)
- `auth/` — interactive sign in · register · reset · signed-in.
- `console/` — authenticated app shell with a buyer / vendor / admin role switch.
- `verifier/` — **the trust engine**: inspection queue → checklist → certify / reject → issued Trust Certificate (the only role that can certify).
- `verify/` — **public** `/verify/{IMEI}` Trust Certificate lookup (verified · expired · flagged · not found).

> The `verifier/` and `verify/` kits are *proposed* designs for documented-but-unbuilt
> Sprint 4 routes — grounded in the repo's rules, not in shipped Blade.

**Assets** (`assets/`)
- `fonts/` — self-hosted Inter (400/500/600/700) + JetBrains Mono (400/500/600) woff2.

**Specimen cards** (`guidelines/`) — Colors, Type, Spacing and Brand cards rendered in the Design System tab.

**Other**
- `USAGE.md` — how to consume this system in the Laravel repo + drive it from local Claude (incl. the sky-blue/blush → emerald/stone refactor playbook).
- `SKILL.md` — makes this folder a downloadable Claude/Agent skill.

> Namespace for cards & kits: `window.ShoppaDesignSystem_cbc994`. Fonts are now
> **self-hosted woff2** in `assets/fonts/` (Inter — OFL, from rsms/inter; JetBrains
> Mono — OFL, from JetBrains/JetBrainsMono), declared as `@font-face` in
> `tokens/fonts.css` — offline- and GDPR-friendly, matching the product's
> self-hosting intent.
