<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Field extends Component
{
    public function __construct(
        public string  $name,
        public string  $label,
        public string  $type        = 'text',
        public ?string $placeholder = null,
        public bool    $required    = false,
        public ?string $hint        = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.form.field');
    }
}