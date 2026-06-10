import * as React from 'react';

export interface AvatarProps extends React.HTMLAttributes<HTMLSpanElement> {
  /** Full name; first two letters become the initials. */
  name?: string;
  /** @default 'md' */
  size?: 'sm' | 'md' | 'lg';
  /** Solid emerald (current user) or soft tint (rows). @default 'solid' */
  tone?: 'solid' | 'soft';
  /** Optional image URL; falls back to initials. */
  src?: string | null;
}

/**
 * Circular initials avatar. Solid emerald marks the signed-in user;
 * the soft tint suits table and list rows.
 */
export function Avatar(props: AvatarProps): JSX.Element;
