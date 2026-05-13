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
        public ?string $trend       = null,   // e.g. '+12%'
        public string  $trendDir    = 'up',   // up | down | neutral
        public string  $iconColor   = 'emerald',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.card.stat-card');
    }
}