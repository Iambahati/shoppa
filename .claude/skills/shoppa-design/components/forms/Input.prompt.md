Labelled form field with optional hint, required marker and inline error — the standard input for auth, listings, KYC and search forms.

```jsx
<Input label="Email address" name="email" type="email" placeholder="you@example.com" required />
<Input label="Phone number" name="phone" type="tel" hint="Used for delivery coordination." />
<Input label="Password" name="password" type="password" error="Those credentials don't match our records." required />
<Input label="Notes" name="notes" textarea placeholder="Inspection notes…" />
```

Pass `error` to show the red ring + alert row, `hint` for helper text, `textarea` for multiline, and `rightSlot` for a trailing affordance (e.g. a password reveal toggle).
