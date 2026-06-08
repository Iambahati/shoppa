<?php

namespace App\View\Components\Nav;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Topbar extends Component
{
    public function __construct(public bool $staff = false) {}

    public function render(): View|Closure|string
    {
        return view('components.nav.topbar');
    }
}
