<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public string $baseClasses;

    public function __construct(
        public string $variant = 'primary',  // primary | secondary | danger | ghost
        public string $size    = 'md',       // sm | md | lg
        public string $type    = 'button',
        public bool   $loading = false,
    ) {
        $this->baseClasses = $this->resolveClasses();
    }

    private function resolveClasses(): string
    {
        $size = match($this->size) {
            'sm'  => 'px-3 py-1.5 text-sm',
            'lg'  => 'px-6 py-3 text-base',
            default => 'px-4 py-2 text-sm',
        };

        $variant = match($this->variant) {
            'secondary' => 'bg-white text-stone-700 ring-1 ring-inset ring-stone-300 hover:bg-stone-50',
            'danger'    => 'bg-red-600 text-white hover:bg-red-500 focus-visible:outline-red-600',
            'ghost'     => 'text-stone-600 hover:bg-stone-100 hover:text-stone-900',
            default     => 'bg-emerald-600 text-white hover:bg-emerald-500 focus-visible:outline-emerald-600',
        };

        return implode(' ', [
            'inline-flex items-center justify-center gap-2 rounded-lg font-medium',
            'transition-colors duration-150',
            'focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2',
            'disabled:opacity-50 disabled:cursor-not-allowed',
            $size,
            $variant,
        ]);
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.button');
    }
}