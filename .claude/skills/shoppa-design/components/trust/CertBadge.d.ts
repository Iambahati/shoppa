import * as React from 'react';

export interface CertBadgeProps extends React.HTMLAttributes<HTMLDivElement> {
  /** Position in the verification state machine. @default 'unverified' */
  status?: 'verified' | 'in_review' | 'pending' | 'rejected' | 'unverified';
  /** Trust Certificate UUID — first 8 chars shown in mono when verified. */
  certId?: string | null;
  /** ISO date the certificate was issued. */
  issuedAt?: string | null;
}

/**
 * Verification status of a device listing. When verified, surfaces the
 * Trust Certificate id + issue date in mono — the publicly-auditable proof
 * at the core of Shoppa.
 *
 * @startingPoint section="Trust" subtitle="Device verification status + cert id" viewport="700x160"
 */
export function CertBadge(props: CertBadgeProps): JSX.Element;
