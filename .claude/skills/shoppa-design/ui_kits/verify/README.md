# Verify UI kit — public Trust Certificate lookup

Interactive recreation of the **public** `/verify/{identifier}` route — the
consumer-facing proof surface. No login: anyone weighing a second-hand purchase
can check a device against Shoppa's certificate registry before paying.

A *proposed* design for the planned public lookup (the route is documented in
the repo but the Blade view isn't built yet).

## Outcomes (try the chips)
- **Verified** — emerald Trust Certificate with device, IMEI, serial, condition
  grade, cert id, issue + 90-day expiry, vendor, verifier, and a QR re-verify block.
- **Expired** — amber notice + dimmed certificate; prompts re-inspection.
- **Flagged / rejected** — red "Do not buy" with the failure reason (e.g. an
  iPhone 11 dressed as an iPhone 17 Pro Max with a duplicate IMEI).
- **Not found** — neutral "no certificate" with a caution note.

## Source of truth
Route `/verify/{identifier}` and the Trust Certificate rules (UUID-signed,
queryable by IMEI/serial, 90-day validity) from the `Iambahati/shoppa` README
and CLAUDE.md.

## Files
- `VerifyApp.jsx` — lookup form, sample registry, all four result states.
- `index.html` — mounts `<VerifyApp />`.
