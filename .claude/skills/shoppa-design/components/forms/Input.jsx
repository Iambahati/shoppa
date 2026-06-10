import React from 'react';

/**
 * Shoppa Input — labelled form field with hint, error and required marker.
 * Borderless with an inset ring; focuses to an emerald 2px ring.
 */
export function Input({
  label,
  name,
  type = 'text',
  placeholder = '',
  hint = '',
  error = '',
  required = false,
  value,
  defaultValue,
  textarea = false,
  rightSlot = null,
  style = {},
  ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const hasError = Boolean(error);

  const ring = hasError
    ? (focus ? '0 0 0 2px var(--red-500)' : '0 0 0 1px var(--red-400)')
    : (focus ? '0 0 0 2px var(--emerald-600)' : '0 0 0 1px var(--stone-300)');

  const fieldStyle = {
    display: 'block',
    width: '100%',
    boxSizing: 'border-box',
    padding: textarea ? '8px 12px' : '8px 12px',
    minHeight: textarea ? 96 : 38,
    fontFamily: 'var(--font-sans)',
    fontSize: 'var(--text-sm)',
    color: 'var(--stone-900)',
    background: 'var(--white)',
    border: 'none',
    borderRadius: 'var(--radius-lg)',
    boxShadow: ring,
    outline: 'none',
    transition: 'box-shadow var(--dur-base) var(--ease-in-out)',
    resize: textarea ? 'vertical' : undefined,
    ...style,
  };

  const Tag = textarea ? 'textarea' : 'input';

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
      {label && (
        <label htmlFor={name} style={{
          font: 'var(--type-label)', color: 'var(--stone-700)',
        }}>
          {label}
          {required && <span aria-hidden="true" style={{ color: 'var(--red-500)', marginLeft: 2 }}>*</span>}
        </label>
      )}
      {hint && <p style={{ font: 'var(--type-meta)', color: 'var(--stone-400)', margin: 0 }}>{hint}</p>}
      <div style={{ position: 'relative', display: 'flex', alignItems: 'center' }}>
        <Tag
          id={name}
          name={name}
          type={textarea ? undefined : type}
          placeholder={placeholder}
          required={required}
          value={value}
          defaultValue={defaultValue}
          onFocus={() => setFocus(true)}
          onBlur={() => setFocus(false)}
          style={fieldStyle}
          {...rest}
        />
        {rightSlot && (
          <span style={{ position: 'absolute', right: 10, display: 'inline-flex', color: 'var(--stone-400)' }}>
            {rightSlot}
          </span>
        )}
      </div>
      {hasError && (
        <p role="alert" style={{
          font: 'var(--type-meta)', color: 'var(--red-600)', margin: 0,
          display: 'flex', alignItems: 'center', gap: 4,
        }}>
          <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" style={{ flexShrink: 0 }}>
            <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clipRule="evenodd" />
          </svg>
          {error}
        </p>
      )}
    </div>
  );
}
