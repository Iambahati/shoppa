<?php

namespace App\View\Components\Trust;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VerifiedPill extends Component
{
    public string $classes;

    public function __construct(public string $size = 'md')
    {
        $this->classes = match($size) {
            'sm'    => 'inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-900/40 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-400/30',
            'lg'    => 'inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/40 px-3 py-1 text-sm font-medium text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-400/30',
            default => 'inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-900/40 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-400/30',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.trust.verified-pill');
    }
}
