<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $classes;
    public string $iconPath;

    public function __construct(
        public string $type = 'info',   // info | success | warning | error
    ) {
        $map = [
            'info'    => ['bg-blue-50 border-blue-200 text-blue-800',   'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            'success' => ['bg-emerald-50 border-emerald-200 text-emerald-800', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            'warning' => ['bg-amber-50 border-amber-200 text-amber-800', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
            'error'   => ['bg-red-50 border-red-200 text-red-800',       'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];

        [$this->classes, $this->iconPath] = $map[$type] ?? $map['info'];
        $this->classes = 'flex items-start gap-3 rounded-lg border p-4 text-sm ' . $this->classes;
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.alert');
    }
}