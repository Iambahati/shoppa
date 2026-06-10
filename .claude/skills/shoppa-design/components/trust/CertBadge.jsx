import React from 'react';

/**
 * Shoppa CertBadge — the verification status of a device listing, with the
 * Trust Certificate id + issue date in mono when verified.
 * One-way state machine: pending → in_review → verified ↘ rejected.
 */
const STATUS = {
  verified:   { label: 'Verified',     bg: 'var(--emerald-50)', fg: 'var(--emerald-700)', ring: 'rgba(5,150,105,0.20)', icon: 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z' },
  in_review:  { label: 'In review',    bg: 'var(--blue-50)',    fg: 'var(--blue-700)',    ring: 'var(--blue-200)',     icon: 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' },
  pending:    { label: 'Pending',      bg: 'var(--amber-50)',   fg: 'var(--amber-700)',   ring: 'var(--amber-200)',    icon: 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' },
  rejected:   { label: 'Rejected',     bg: 'var(--red-50)',     fg: 'var(--red-700)',     ring: 'var(--red-200)',      icon: 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
  unverified: { label: 'Not verified', bg: 'var(--stone-100)',  fg: 'var(--stone-600)',   ring: 'var(--stone-200)',    icon: 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z' },
};

export function CertBadge({ status = 'unverified', certId = null, issuedAt = null, style = {}, ...rest }) {
  const s = STATUS[status] || STATUS.unverified;
  const showCert = certId && status === 'verified';
  const fmtDate = (d) => {
    try {
      return new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    } catch { return null; }
  };
  return (
    <div style={{ display: 'inline-flex', flexDirection: 'column', gap: 4, ...style }} {...rest}>
      <span style={{
        display: 'inline-flex', alignItems: 'center', gap: 6,
        padding: '4px 10px', borderRadius: 'var(--radius-full)',
        background: s.bg, color: s.fg, boxShadow: `inset 0 0 0 1px ${s.ring}`,
        fontFamily: 'var(--font-sans)', fontWeight: 'var(--weight-medium)', fontSize: 'var(--text-xs)',
        whiteSpace: 'nowrap', width: 'fit-content',
      }}>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true" style={{ flexShrink: 0 }}>
          <path d={s.icon} />
        </svg>
        {s.label}
      </span>
      {showCert && (
        <span style={{ font: 'var(--type-mono)', color: 'var(--stone-400)', paddingLeft: 4, letterSpacing: '0.02em' }}>
          # {String(certId).slice(0, 8).toUpperCase()}
          {issuedAt && fmtDate(issuedAt) ? ` · ${fmtDate(issuedAt)}` : ''}
        </span>
      )}
    </div>
  );
}
