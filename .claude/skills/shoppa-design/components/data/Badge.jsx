import React from 'react';

const MAP = {
  stone:   ['var(--stone-100)', 'var(--stone-700)', 'var(--stone-200)'],
  emerald: ['var(--emerald-50)', 'var(--emerald-700)', 'var(--emerald-200)'],
  amber:   ['var(--amber-50)', 'var(--amber-700)', 'var(--amber-200)'],
  red:     ['var(--red-50)', 'var(--red-700)', 'var(--red-200)'],
  blue:    ['var(--blue-50)', 'var(--blue-700)', 'var(--blue-200)'],
  purple:  ['var(--purple-50)', 'var(--purple-700)', 'var(--purple-200)'],
};

const SIZES = {
  xs: { padding: '2px 6px', fontSize: '11px' },
  sm: { padding: '4px 8px', fontSize: 'var(--text-xs)' },
  md: { padding: '4px 10px', fontSize: 'var(--text-sm)' },
};

/**
 * Shoppa Badge — compact status / category label. Soft tinted fill with a
 * matching inset ring. Purple is reserved for staff roles.
 */
export function Badge({ color = 'stone', size = 'sm', children, style = {}, ...rest }) {
  const [bg, fg, ring] = MAP[color] || MAP.stone;
  const sz = SIZES[size] || SIZES.sm;
  return (
    <span
      style={{
        display: 'inline-flex', alignItems: 'center', gap: 5,
        fontFamily: 'var(--font-sans)', fontWeight: 'var(--weight-medium)',
        borderRadius: 'var(--radius-md)',
        background: bg, color: fg, boxShadow: `inset 0 0 0 1px ${ring}`,
        whiteSpace: 'nowrap', ...sz, ...style,
      }}
      {...rest}
    >
      {children}
    </span>
  );
}
