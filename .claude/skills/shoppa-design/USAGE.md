# Using the Shoppa Design System in your repo

This system is the **source of truth for how Shoppa looks and sounds**. There
are two ways to consume it, plus a playbook for the colour refactor you asked
about (sky-blue/blush → emerald + warm-stone).

---

## A. What's portable, and what it maps to

| In this system | In your Laravel app |
|---|---|
| `tokens/*.css` (CSS custom properties) | Your Tailwind `theme.extend` + any `:root` vars. The values already match Tailwind's `emerald` / `stone` palettes. |
| `assets/fonts/*.woff2` + `tokens/fonts.css` | Drop into `resources/fonts/` and register the same `@font-face` (or keep Bunny Fonts — same families). |
| `components/*` (React) | Your Blade components (`x-ui.button`, `x-ui.badge`, `x-trust.*`…). These React versions mirror the Blade ones 1:1 — use them as the visual spec, not as code to ship. |
| `ui_kits/*` | Reference screens — match spacing, hierarchy, copy. |
| `readme.md` voice + visual sections | Your design review checklist. |

You don't import the React bundle into Laravel. You treat this repo as the
**spec**: copy token values, font files, and match the component recipes in Blade + Tailwind.

---

## B. Drive it from local Claude Code (recommended)

This folder is already a valid **Agent Skill** (`SKILL.md` at the root).

1. **Download** this project (Download → whole project) and unzip it into your
   app, e.g. `.claude/skills/shoppa-design/`. (Any folder Claude Code can read works.)
2. In your repo, open Claude Code and the skill is auto-discoverable. Invoke it:
   > "Use the **shoppa-design** skill. Build the vendor listing-create form in Blade + Tailwind, matching our system."
3. Claude reads `readme.md` (voice + visual foundations), the `tokens/`, and the
   relevant `components/*.prompt.md`, then writes Blade that matches.

Keep `SKILL.md` and `readme.md` as the entry points — everything else is
discoverable from there.

---

## C. Refactor playbook — sky-blue/blush → emerald + warm-stone

Your codebase's `tailwind.config.js` already ships **emerald + stone** (that's
why this system uses them). If a branch or surface still uses the old
**sky/blue + rose/blush** direction, here's the migration.

### 1. Colour mapping

| Old (sky/blush) | New (emerald/stone) | Where |
|---|---|---|
| `sky-600`, `blue-600` (primary action) | `emerald-600` | buttons, links, focus rings, active nav |
| `sky-500` (hover) | `emerald-500` | hover states |
| `sky-50` / `blue-50` (tinted surface) | `emerald-50` | trust callouts, info chips → *but keep `blue-*` only for genuine "info", see below |
| `rose-*` / `pink-*` (blush accent/neutral) | `stone-*` | backgrounds, borders, secondary text |
| `slate-*` / `gray-*` / `zinc-*` (cool grey) | `stone-*` | **all** neutrals — warm, never cool |
| `rounded-md` everywhere | `rounded-lg` (controls) / `rounded-xl` (cards) | match the radius scale |

Keep semantic colours as **status only**: `amber` = pending, `blue` = info /
in-review, `red` = error/rejected, `purple` = staff. Don't let blue creep back
in as a primary/brand colour — emerald owns that.

### 2. Mechanical find-and-replace (review each hit)

```bash
# Tailwind class swaps across Blade views
grep -rl --include='*.blade.php' -E 'sky-|blue-(50|600|700)|rose-|pink-|slate-|zinc-|gray-' resources/views

# Then, per file, swap (examples — verify intent first):
#   text-sky-600 / bg-sky-600  -> text-emerald-600 / bg-emerald-600
#   hover:bg-sky-500           -> hover:bg-emerald-500
#   focus:ring-sky-600         -> focus:ring-emerald-600
#   ring-rose-200 / bg-rose-50 -> ring-stone-200 / bg-stone-50
#   text-slate-500/600/900     -> text-stone-500/600/900
#   border-gray-200            -> border-stone-200
```

For PostgreSQL search, remember the house rule: use `ilike`, not `like`.

### 3. Let Claude do the semantic pass

Mechanical swaps get ~80%. For the rest (is this blue "info" or "primary"?),
hand it to local Claude with the skill loaded:

> "Use the shoppa-design skill. Audit `resources/views/pages/**` for off-brand
> colour: replace sky/blue-as-primary with emerald, rose/blush and cool greys
> with warm stone. Keep blue only where it's genuinely 'info', amber for
> pending, red for error, purple for staff. Show me a diff per file."

### 4. Verify against the system

- Primary actions are `emerald-600`, hover `emerald-500`, focus ring `emerald-600`.
- Neutrals are `stone-*` (warm) — no `slate`/`zinc`/`gray` left.
- Cards are white + `ring-stone-950/5` + `shadow-sm`, `rounded-xl`.
- The **VerifiedPill** and **CertBadge** are present wherever trust is asserted.
- Voice is warm + plain (see `readme.md` → Content fundamentals).

When those hold, you're on-brand.
