import * as React from 'react';

export type IconName =
  | 'home' | 'search' | 'box' | 'layers' | 'users' | 'store' | 'package'
  | 'flag' | 'shield' | 'cpu' | 'message-sq' | 'bell' | 'user' | 'check'
  | 'x' | 'chevron-r' | 'chevron-d' | 'bars' | 'qr' | 'plus' | 'arrow-right'
  | 'check-badge' | 'shield-check' | 'default';

export interface IconProps extends React.SVGAttributes<SVGSVGElement> {
  /** Glyph name from the Heroicons-derived registry. */
  name?: IconName;
  /** Pixel size (width = height). @default 18 */
  size?: number;
  /** Use the filled trust glyphs (check-badge, shield-check). @default false */
  solid?: boolean;
  /** Outline stroke width. @default 1.5 */
  strokeWidth?: number;
}

/**
 * Heroicons-derived icon set, self-contained (no CDN). Outline at 1.5
 * stroke for UI; `solid` for the verified trust glyphs.
 */
export function Icon(props: IconProps): JSX.Element;
