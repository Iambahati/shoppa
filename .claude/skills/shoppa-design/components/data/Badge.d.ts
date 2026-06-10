import * as React from 'react';

export interface BadgeProps extends React.HTMLAttributes<HTMLSpanElement> {
  /** Tint family. Purple = staff roles by convention. @default 'stone' */
  color?: 'stone' | 'emerald' | 'amber' | 'red' | 'blue' | 'purple';
  /** @default 'sm' */
  size?: 'xs' | 'sm' | 'md';
  children?: React.ReactNode;
}

/**
 * Compact status / category label with a soft tinted fill and inset ring.
 *
 * @startingPoint section="Data" subtitle="Status & role badges in six tints" viewport="700x150"
 */
export function Badge(props: BadgeProps): JSX.Element;
