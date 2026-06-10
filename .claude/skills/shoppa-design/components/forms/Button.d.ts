import * as React from 'react';

export interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  /** Visual weight. @default 'primary' */
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger';
  /** @default 'md' */
  size?: 'sm' | 'md' | 'lg';
  /** Shows a spinner and disables the button. @default false */
  loading?: boolean;
  /** Icon node rendered before the label. */
  iconLeft?: React.ReactNode;
  /** Icon node rendered after the label. */
  iconRight?: React.ReactNode;
  /** Stretch to fill the container width. @default false */
  fullWidth?: boolean;
  children?: React.ReactNode;
}

/**
 * Primary call-to-action button. Emerald primary signals the trusted action;
 * secondary/ghost recede; danger is reserved for destructive flows.
 *
 * @startingPoint section="Forms" subtitle="Primary, secondary, ghost & danger buttons" viewport="700x200"
 */
export function Button(props: ButtonProps): JSX.Element;
