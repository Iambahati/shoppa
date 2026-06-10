import React from 'react';
import { Icon } from '../navigation/Icon.jsx';

const ICON_TINT = {
  emerald: ['var(--emerald-50)', 'var(--emerald-600)'],
  blue:    ['var(--blue-50)', 'var(--blue-600)'],
  amber:   ['var(--amber-50)', 'var(--amber-600)'],
  red:     ['var(--red-50)', 'var(--red-600)'],
  purple:  ['var(--purple-50)', 'var(--purple-600)'],
};

const TREND = {
  up:      'var(--emerald-600)',
  down:    'var(--red-600)',
  neutral: 'var(--stone-400)',
};

/**
 * Shoppa StatCard — KPI tile for dashboards. Label + tinted icon chip,
 * a large semibold value, and an optional month-over-month trend.
 */
export function StatCard({ label, value, icon = 'package', iconColor = 'emerald', trend = null, trendDir = 'up', style = {}, ...rest }) {
  const [chipBg, chipFg] = ICON_TINT[iconColor] || ICON_TINT.emerald;
  const arrow = trendDir === 'up' ? '↑' : trendDir === 'down' ? '↓' : '–';
  return (
    <div
      style={{
        background: 'var(--surface-card)', borderRadius: 'var(--radius-xl)',
        padding: 'var(--space-5)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--elevation-card)',
        ...style,
      }}
      {...rest}
    >
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 }}>
        <p style={{ font: 'var(--type-label)', color: 'var(--stone-500)', margin: 0 }}>{label}</p>
        <span style={{
          display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
          width: 36, height: 36, borderRadius: 'var(--radius-lg)',
          background: chipBg, color: chipFg,
        }}>
          <Icon name={icon} size={18} />
        </span>
      </div>
      <p style={{ margin: '12px 0 0', font: 'var(--type-stat)', color: 'var(--stone-900)', letterSpacing: 'var(--tracking-tight)' }}>{value}</p>
      {trend && (
        <p style={{ margin: '4px 0 0', font: 'var(--type-meta)', fontWeight: 'var(--weight-medium)', color: TREND[trendDir] || TREND.up }}>
          {arrow} {trend} vs last month
        </p>
      )}
    </div>
  );
}
