import React from 'react';

/**
 * Shoppa Checkbox — square emerald checkbox with an inline label.
 * Used for "Keep me signed in", filters and consent rows.
 */
export function Checkbox({ label, name, checked, defaultChecked, disabled = false, onChange, style = {}, ...rest }) {
  return (
    <label style={{
      display: 'inline-flex', alignItems: 'center', gap: 8,
      cursor: disabled ? 'not-allowed' : 'pointer', opacity: disabled ? 0.5 : 1,
      font: 'var(--type-body)', color: 'var(--stone-600)', userSelect: 'none', ...style,
    }}>
      <input
        type="checkbox"
        id={name}
        name={name}
        checked={checked}
        defaultChecked={defaultChecked}
        disabled={disabled}
        onChange={onChange}
        style={{
          appearance: 'none', WebkitAppearance: 'none',
          width: 16, height: 16, margin: 0, flexShrink: 0,
          borderRadius: 'var(--radius-sm)',
          border: '1.5px solid var(--stone-300)',
          background: 'var(--white)',
          display: 'inline-grid', placeContent: 'center',
          cursor: disabled ? 'not-allowed' : 'pointer',
          transition: 'background-color var(--dur-base), border-color var(--dur-base)',
        }}
        {...rest}
      />
      {label}
      <style>{`
        #${name || 'cb'}:checked {
          background-color: var(--emerald-600);
          border-color: var(--emerald-600);
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3E%3Cpath fill-rule='evenodd' d='M16.704 5.29a1 1 0 010 1.42l-7.5 7.5a1 1 0 01-1.42 0l-3.5-3.5a1 1 0 111.42-1.42l2.79 2.79 6.79-6.79a1 1 0 011.42 0z' clip-rule='evenodd'/%3E%3C/svg%3E");
          background-size: 14px; background-position: center; background-repeat: no-repeat;
        }
        #${name || 'cb'}:focus-visible { outline: 2px solid var(--emerald-600); outline-offset: 2px; }
      `}</style>
    </label>
  );
}
