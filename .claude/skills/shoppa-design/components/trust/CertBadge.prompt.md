Shows where a device sits in the verification pipeline. When `verified`, it also renders the Trust Certificate id + issue date in mono — the publicly-auditable proof.

```jsx
<CertBadge status="verified" certId="9f2a17c4-..." issuedAt="2026-05-02" />
<CertBadge status="in_review" />
<CertBadge status="pending" />
<CertBadge status="rejected" />
<CertBadge status="unverified" />
```

States follow the one-way machine `pending → in_review → verified ↘ rejected`. The cert id only appears on `verified`.
