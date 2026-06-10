# Auth UI kit

Interactive recreation of Shoppa's authentication surfaces, built on the
`guest` Blade layout. Composes DS primitives only (`Button`, `Input`,
`Checkbox`, `VerifiedPill`, `Alert`).

## Screens
- **Sign in** — email + password (reveal toggle), "Keep me signed in", forgot link.
- **Register** — name / email / phone / password, plus the escrow + verification trust note.
- **Reset password** — single email field with a success alert.
- **Signed in** — confirmation state.

## Try it
Open `index.html`. Switch between sign-in and register via the footer link,
hit "Forgot password?" for the reset view, toggle password visibility, and
submit to reach the signed-in state.

## Source of truth
`resources/views/pages/auth/{login,register,forgot-password}.blade.php` and
`resources/views/layouts/guest.blade.php` in `Iambahati/shoppa`.

## Files
- `AuthApp.jsx` — the full flow (Shell, BrandLock, forms).
- `index.html` — mounts `<AuthApp />`.
