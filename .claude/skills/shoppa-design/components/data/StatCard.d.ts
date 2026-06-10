import * as React from 'react';
import type { IconName } from '../navigation/Icon';

export interface StatCardProps extends React.HTMLAttributes<HTMLDivElement> {
  /** Metric label, e.g. "Active orders". */
  label: string;
  /** The headline value (already formatted). */
  value: string | number;
  /** Icon glyph name. @default 'package' */
  icon?: IconName;
  /** Tint of the icon chip. @default 'emerald' */
  iconColor?: 'emerald' | 'blue' | 'amber' | 'red' | 'purple';
  /** Optional trend label, e.g. "+12%". */
  trend?: string | null;
  /** Trend direction (sets colour + arrow). @default 'up' */
  trendDir?: 'up' | 'down' | 'neutral';
}

/**
 * KPI tile for dashboards: label, tinted icon chip, large value, optional trend.
 *
 * @startingPoint section="Data" subtitle="Dashboard KPI stat tile" viewport="700x150"
 */
export function StatCard(props: StatCardProps): JSX.Element;
