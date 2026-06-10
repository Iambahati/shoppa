import React from 'react';

const MAP = {
  info:    ['var(--blue-50)', 'var(--blue-200)', 'var(--blue-800)', 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
  success: ['var(--emerald-50)', 'var(--emerald-200)', 'var(--emerald-800)', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
  warning: ['var(--amber-50)', 'var(--amber-200)', 'var(--amber-900)', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
  error:   ['var(--red-50)', 'var(--red-200)', 'var(--red-800)', 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'],
};

/**
 * Shoppa Alert — inline status message with a leading icon. Soft tinted
 * surface + hairline border. Used for flash messages and form feedback.
 */
export function Alert({ type = 'info', title = null, children, style = {}, ...rest }) {
  const [bg, border, fg, iconPath] = MAP[type] || MAP.info;
  return (
    <div
      role={type === 'error' ? 'alert' : 'status'}
      style={{
        display: 'flex', alignItems: 'flex-start', gap: 12,
        background: bg, border: `1px solid ${border}`, color: fg,
        borderRadius: 'var(--radius-lg)', padding: 'var(--space-4)',
        font: 'var(--type-body)', ...style,
      }}
      {...rest}
    >
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round"
        aria-hidden="true" style={{ flexShrink: 0, marginTop: 1 }}>
        <path d={iconPath} />
      </svg>
      <div style={{ minWidth: 0 }}>
        {title && <p style={{ margin: 0, fontWeight: 'var(--weight-semibold)' }}>{title}</p>}
        <div style={{ margin: title ? '2px 0 0' : 0 }}>{children}</div>
      </div>
    </div>
  );
}
