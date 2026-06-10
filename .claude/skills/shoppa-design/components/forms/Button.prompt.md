Primary call-to-action button — use for the single most important action on a surface (Sign in, Add listing, Create account); reach for secondary/ghost for supporting actions and danger for destructive ones.

```jsx
<Button variant="primary" size="md">Add listing</Button>
<Button variant="secondary" size="sm">Filter</Button>
<Button variant="ghost" size="sm">Clear</Button>
<Button variant="danger">Reject device</Button>
<Button loading>Signing in…</Button>
```

Variants: `primary` (emerald), `secondary` (white + stone ring), `ghost` (text only), `danger` (red). Sizes: `sm | md | lg`. Pass `iconLeft` / `iconRight` for icons, `fullWidth` to stretch, `loading` for the spinner state.
