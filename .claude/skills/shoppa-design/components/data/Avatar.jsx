import React from 'react';

const SIZES = { sm: 28, md: 32, lg: 40 };

/**
 * Shoppa Avatar — circular initials chip. Solid emerald for the current
 * user; soft emerald tint for list/table rows.
 */
export function Avatar({ name = '', size = 'md', tone = 'solid', src = null, style = {}, ...rest }) {
  const px = SIZES[size] || SIZES.md;
  const initials = name.trim().slice(0, 2).toUpperCase();
  const tones = {
    solid: { background: 'var(--emerald-600)', color: 'var(--white)' },
    soft:  { background: 'var(--emerald-100)', color: 'var(--emerald-700)' },
  };
  const t = tones[tone] || tones.solid;
  return (
    <span
      style={{
        display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
        width: px, height: px, flexShrink: 0,
        borderRadius: 'var(--radius-full)', overflow: 'hidden',
        fontFamily: 'var(--font-sans)', fontWeight: 'var(--weight-semibold)',
        fontSize: px <= 28 ? 11 : (px <= 32 ? 12 : 14),
        ...t, ...style,
      }}
      {...rest}
    >
      {src ? <img src={src} alt={name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} /> : initials}
    </span>
  );
}
