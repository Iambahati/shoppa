# Verifier UI kit — the trust engine

Interactive recreation of the Verifier console. The **Verifier is the only
role that can certify or reject a device**, so this is the most consequential
surface in the product. It is a *proposed* design for the planned Sprint 4
inspection workflow — grounded in the documented state machine and rules, not
in shipped Blade (those views don't exist yet).

## Flow
1. **Inspect queue** — devices in `pending` / `in_review`, each with model,
   IMEI (mono), vendor and status. Click **Inspect**.
2. **Device inspection** — claimed vs detected model, IMEI/serial, asking price,
   IMEI uniqueness, submitted photos, and a **verification checklist**.
   - A **duplicate IMEI** (try `iPhone 11` listed as "iPhone 17 Pro Max")
     raises a blocking error and the IMEI/hardware checks auto-fail.
   - All checks must pass before **Issue Trust Certificate** enables.
3. **Resolution** — issuing generates a UUID-signed certificate (90-day
   validity, verifier attribution, audit-trail note, public `/verify/{IMEI}`
   link). Rejecting shows the rejection state.

## Rules encoded (from the repo README + CLAUDE.md)
- IMEI must be unique across active listings; duplicates are blocked.
- Cert UUIDs are server-generated; certificates expire after 90 days.
- Every transition is logged with the acting verifier's identity.

## Files
- `VerifierShell.jsx` — sidebar, topbar, queue table, seed data, checklist.
- `VerifierApp.jsx` — inspection detail, certify/reject, issued certificate.
- `index.html` — mounts `<VerifierApp />`.
