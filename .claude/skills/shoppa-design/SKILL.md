---
name: shoppa-design
description: Use this skill to generate well-branded interfaces and assets for Shoppa — the Trust-as-a-Service electronics marketplace for Kenya & East Africa — either for production or throwaway prototypes/mocks. Contains essential design guidelines, colors, type, fonts, the Verified/Trust components, and UI kit screens for prototyping.
user-invocable: true
---

Read the `readme.md` file within this skill, and explore the other available files.

This is the Shoppa design system. The brand is **trust-first**: emerald = verified/primary, warm stone neutrals, Inter + JetBrains Mono, generous rounding, ring-first cards. The single most important brand signal is the **VerifiedPill** ("Shoppa Verified") and the **CertBadge** verification states — lead with them.

Where to look:
- `readme.md` — full guide: content fundamentals (voice), visual foundations, iconography, manifest.
- `styles.css` + `tokens/*` — color, type, spacing, font tokens (CSS custom properties).
- `components/<group>/` — React primitives (Button, Input, Checkbox, Badge, Avatar, StatCard, Alert, Icon, VerifiedPill, CertBadge). Each has a `.prompt.md` with usage.
- `ui_kits/auth/` and `ui_kits/console/` — full-screen interactive recreations (auth flow; buyer/vendor/admin console).
- `guidelines/*.html` — foundation specimen cards.

If creating visual artifacts (slides, mocks, throwaway prototypes), copy assets/tokens out and produce static HTML for the user to view — load `styles.css` for tokens and reuse the component patterns. If working in the real product, it's Laravel + Blade + Alpine.js + Tailwind (no React/Vue); use the tokens and the `Iambahati/shoppa` Blade components as the source of truth, and read the rules here to design like a Shoppa expert.

If the user invokes this skill without other guidance, ask what they want to build or design, ask a few focused questions, and act as an expert designer who outputs HTML artifacts *or* production code, depending on the need. Keep the voice warm, clear and trust-first; never corporate.
