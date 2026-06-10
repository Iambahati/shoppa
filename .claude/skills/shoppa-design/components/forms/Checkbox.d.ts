import * as React from 'react';

export interface CheckboxProps extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'type'> {
  /** Inline label rendered to the right of the box. */
  label?: React.ReactNode;
  /** Field name + id (also scopes the checked styling). */
  name?: string;
  disabled?: boolean;
}

/**
 * Square checkbox with an inline label — emerald when checked.
 * Use for "Keep me signed in", role filters and consent rows.
 */
export function Checkbox(props: CheckboxProps): JSX.Element;
