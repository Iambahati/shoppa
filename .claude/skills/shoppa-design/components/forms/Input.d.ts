import * as React from 'react';

export interface InputProps extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'value'> {
  /** Field label shown above the control. */
  label?: string;
  /** Field name + id. */
  name?: string;
  /** Input type (ignored when `textarea`). @default 'text' */
  type?: string;
  /** Helper text shown under the label. */
  hint?: string;
  /** Error message; turns the ring red and shows an alert row. */
  error?: string;
  /** Marks the field required (adds a red asterisk). */
  required?: boolean;
  /** Render a multiline textarea instead of an input. @default false */
  textarea?: boolean;
  /** Node pinned to the right edge (e.g. a show/hide toggle). */
  rightSlot?: React.ReactNode;
  value?: string;
}

/**
 * Labelled text field. Borderless with an inset stone ring that turns
 * emerald on focus and red on error — the workhorse of every Shoppa form.
 *
 * @startingPoint section="Forms" subtitle="Labelled input with hint & error states" viewport="700x260"
 */
export function Input(props: InputProps): JSX.Element;
