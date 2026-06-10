# Console UI kit

Interactive recreation of Shoppa's authenticated app — the dark-sidebar /
white-topbar / warm-canvas shell from `layouts/dashboard.blade.php` and
`components/nav/*`, with role-aware navigation and three dashboards.

## Roles (switch in the topbar)
- **Buyer** — greeting, KPI stats, the "every device is inspected" trust
  callout, recent orders with status badges.
- **Vendor** — seller header + "Add listing", listing stats, recent listings
  each carrying a `CertBadge` (verified / in review / pending / rejected).
- **Admin** — platform KPIs and the users table (also reachable via the
  Users nav item) with staff vs buyer role badges.

The sidebar nav set changes per role exactly the way `Sidebar::buildNav()`
does server-side.

## Source of truth
`resources/views/pages/{buyer,vendors,admin}/dashboard.blade.php`,
`pages/admin/users/index.blade.php`, and `components/nav/*`,
`components/card/stat-card.blade.php` in `Iambahati/shoppa`.

## Files
- `Shell.jsx` — Sidebar, Topbar, RoleSwitch, `Console` layout.
- `Dashboards.jsx` — Buyer / Vendor / Admin content + users table.
- `index.html` — wires role + active-nav state together.
