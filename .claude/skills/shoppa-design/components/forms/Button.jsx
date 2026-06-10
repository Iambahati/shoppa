import React from 'react';

/**
 * Shoppa Button — the primary call to action across the marketplace.
 * Emerald primary, stone-ringed secondary, ghost, and danger variants.
 */
export function Button({
  variant = 'primary',
  size = 'md',
  type = 'button',
  loading = false,
  disabled = false,
  iconLeft = null,
  iconRight = null,
  fullWidth = false,
  children,
  style = {},
  ...rest
}) {
  const sizes = {
    sm: { padding: '6px 12px', fontSize: 'var(--text-sm)', height: 32 },
    md: { padding: '8px 16px', fontSize: 'var(--text-sm)', height: 38 },
    lg: { padding: '12px 24px', fontSize: 'var(--text-base)', height: 48 },
  };

  const variants = {
    primary: {
      background: 'var(--emerald-600)',
      color: 'var(--white)',
      boxShadow: 'none',
    },
    secondary: {
      background: 'var(--white)',
      color: 'var(--stone-700)',
      boxShadow: 'inset 0 0 0 1px var(--stone-300)',
    },
    ghost: {
      background: 'transparent',
      color: 'var(--stone-600)',
      boxShadow: 'none',
    },
    danger: {
      background: 'var(--red-600)',
      color: 'var(--white)',
      boxShadow: 'none',
    },
  };

  const sz = sizes[size] || sizes.md;
  const vr = variants[variant] || variants.primary;
  const isDisabled = disabled || loading;

  const hoverBg = {
    primary: 'var(--emerald-500)',
    secondary: 'var(--stone-50)',
    ghost: 'var(--stone-100)',
    danger: 'var(--red-500)',
  }[variant];

  const [hover, setHover] = React.useState(false);

  return (
    <button
      type={type}
      disabled={isDisabled}
      aria-busy={loading || undefined}
      onMouseEnter={() => setHover(true)}
      onMouseLeave={() => setHover(false)}
      style={{
        display: 'inline-flex',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 8,
        width: fullWidth ? '100%' : 'auto',
        minHeight: sz.height,
        padding: sz.padding,
        fontFamily: 'var(--font-sans)',
        fontSize: sz.fontSize,
        fontWeight: 'var(--weight-medium)',
        lineHeight: 1,
        borderRadius: 'var(--radius-lg)',
        border: 'none',
        cursor: isDisabled ? 'not-allowed' : 'pointer',
        opacity: isDisabled ? 0.5 : 1,
        transition: 'background-color var(--dur-base) var(--ease-in-out), box-shadow var(--dur-base)',
        background: hover && !isDisabled && variant !== 'secondary' && variant !== 'ghost' ? hoverBg
          : (hover && !isDisabled && (variant === 'secondary' || variant === 'ghost') ? hoverBg : vr.background),
        color: vr.color,
        boxShadow: vr.boxShadow,
        ...style,
      }}
      {...rest}
    >
      {loading && (
        <>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"
            style={{ animation: 'shoppa-spin 1s linear infinite', marginLeft: -2 }}>
            <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" opacity="0.25" />
            <path fill="currentColor" opacity="0.75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
          </svg>
          <style>{'@keyframes shoppa-spin{to{transform:rotate(360deg)}}'}</style>
        </>
      )}
      {!loading && iconLeft}
      {children}
      {!loading && iconRight}
    </button>
  );
}
