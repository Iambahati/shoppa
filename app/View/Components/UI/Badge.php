<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    public string $classes;

    public function __construct(
        public string $color = 'stone',  // stone|emerald|amber|red|blue|purple
        public string $size  = 'sm',
    ) {
        $colorMap = [
            'stone'   => 'bg-stone-100 dark:bg-stone-800 text-stone-700 dark:text-stone-300 ring-stone-200 dark:ring-stone-700',
            'emerald' => 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 ring-emerald-200 dark:ring-emerald-700',
            'amber'   => 'bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 ring-amber-200 dark:ring-amber-700',
            'red'     => 'bg-red-50 dark:bg-red-900/40 text-red-700 dark:text-red-400 ring-red-200 dark:ring-red-700',
            'blue'    => 'bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 ring-blue-200 dark:ring-blue-700',
            'purple'  => 'bg-purple-50 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 ring-purple-200 dark:ring-purple-700',
        ];

        $sizeMap = [
            'xs' => 'px-1.5 py-0.5 text-xs',
            'sm' => 'px-2 py-1 text-xs',
            'md' => 'px-2.5 py-1 text-sm',
        ];

        $this->classes = implode(' ', [
            'inline-flex items-center rounded-md font-medium ring-1 ring-inset',
            $colorMap[$color] ?? $colorMap['stone'],
            $sizeMap[$size]   ?? $sizeMap['sm'],
        ]);
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.badge');
    }
}
