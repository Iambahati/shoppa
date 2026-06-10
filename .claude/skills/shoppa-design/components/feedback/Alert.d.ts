import * as React from 'react';

export interface AlertProps extends React.HTMLAttributes<HTMLDivElement> {
  /** Status family. @default 'info' */
  type?: 'info' | 'success' | 'warning' | 'error';
  /** Optional bold title above the body. */
  title?: React.ReactNode;
  children?: React.ReactNode;
}

/**
 * Inline status message with a leading icon — flash messages, form-level
 * feedback, escrow/verification notices.
 *
 * @startingPoint section="Feedback" subtitle="Info, success, warning & error alerts" viewport="700x240"
 */
export function Alert(props: AlertProps): JSX.Element;
