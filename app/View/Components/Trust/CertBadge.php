<?php

namespace App\View\Components\Trust;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CertBadge extends Component
{
    public string $label;
    public string $colorClasses;
    public string $iconPath;

    public function __construct(
        public string  $status,
        public ?string $certId   = null,
        public ?string $issuedAt = null,
    ) {
        [$this->label, $this->colorClasses, $this->iconPath] = match($status) {
            'verified'  => [
                'Shoppa Certified',
                'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 ring-emerald-600/20 dark:ring-emerald-400/30',
                'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
            ],
            'pending'   => [
                'Awaiting Verification',
                'bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 ring-amber-600/20 dark:ring-amber-400/30',
                'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            'in_review' => [
                'Under Review',
                'bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 ring-blue-600/20 dark:ring-blue-400/30',
                'M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3',
            ],
            'rejected'  => [
                'Verification Failed',
                'bg-red-50 dark:bg-red-900/40 text-red-700 dark:text-red-400 ring-red-600/20 dark:ring-red-400/30',
                'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
            ],
            default     => [
                'Not Verified',
                'bg-stone-100 dark:bg-stone-800 text-stone-500 dark:text-stone-400 ring-stone-200 dark:ring-stone-700',
                'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z',
            ],
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.trust.cert-badge');
    }
}
