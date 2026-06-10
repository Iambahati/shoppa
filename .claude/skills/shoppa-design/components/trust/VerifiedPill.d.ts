import * as React from 'react';

export interface VerifiedPillProps extends React.HTMLAttributes<HTMLSpanElement> {
  /** @default 'md' */
  size?: 'sm' | 'md' | 'lg';
  /** Override the default "Shoppa Verified" label. */
  children?: React.ReactNode;
}

/**
 * The Shoppa trust mark — emerald capsule + filled check-badge reading
 * "Shoppa Verified". The product's signature brand signal; use it wherever
 * a device, vendor or surface needs to assert verification.
 *
 * @startingPoint section="Trust" subtitle="The Shoppa Verified trust mark" viewport="700x120"
 */
export function VerifiedPill(props: VerifiedPillProps): JSX.Element;
