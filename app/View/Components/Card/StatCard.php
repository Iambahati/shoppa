<?php

namespace App\View\Components\Card;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatCard extends Component
{
    public function __construct(
        public string  $label,
        public string  $value,
        public string  $icon        = 'package',
        public ?string $trend       = null,
        public string  $trendDir    = 'up',
        public string  $iconColor   = 'emerald',
        public ?string $sparkline   = null,   // comma-separated numbers, e.g. "12,19,14,27,22,31,34"
        public bool    $glowFirst   = false,  // adds sky-500 glow shadow
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.card.stat-card');
    }
}
