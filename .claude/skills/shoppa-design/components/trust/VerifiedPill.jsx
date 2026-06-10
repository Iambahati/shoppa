import React from 'react';

const SIZES = {
  sm: { padding: '2px 8px', font: 'var(--text-xs)', icon: 12, gap: 4 },
  md: { padding: '4px 10px', font: 'var(--text-xs)', icon: 13, gap: 4 },
  lg: { padding: '5px 12px', font: 'var(--text-sm)', icon: 15, gap: 6 },
};

/**
 * Shoppa VerifiedPill — the trust mark. A rounded emerald capsule with the
 * filled check-badge glyph. Reads "Shoppa Verified" by default. This is the
 * single most important brand signal in the product.
 */
export function VerifiedPill({ size = 'md', children, style = {}, ...rest }) {
  const sz = SIZES[size] || SIZES.md;
  return (
    <span
      style={{
        display: 'inline-flex', alignItems: 'center', gap: sz.gap,
        padding: sz.padding, borderRadius: 'var(--radius-full)',
        background: 'var(--emerald-50)', color: 'var(--emerald-700)',
        boxShadow: 'inset 0 0 0 1px rgba(5,150,105,0.20)',
        fontFamily: 'var(--font-sans)', fontWeight: 'var(--weight-medium)',
        fontSize: sz.font, whiteSpace: 'nowrap', ...style,
      }}
      {...rest}
    >
      <svg width={sz.icon} height={sz.icon} viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" style={{ flexShrink: 0 }}>
        <path fillRule="evenodd" clipRule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" />
      </svg>
      {children || 'Shoppa Verified'}
    </span>
  );
}
