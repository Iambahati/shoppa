@props([
    'type'    => 'button',
    'loading' => false,
    'variant' => 'primary',
    'size'    => 'md',
])
@php
$sizeClass = match($size) {
    'sm'    => 'px-3 py-1.5 text-sm',
    'lg'    => 'px-6 py-3 text-base',
    default => 'px-4 py-2 text-sm',
};
$variantClass = match($variant) {
    'secondary' => 'bg-white text-stone-700 ring-1 ring-inset ring-stone-300 hover:bg-stone-50',
    'danger'    => 'bg-red-600 text-white hover:bg-red-500 focus-visible:outline-red-600',
    'ghost'     => 'text-stone-600 hover:bg-stone-100 hover:text-stone-900',
    // DS primary: emerald-600, lighten to emerald-500 on hover
    default     => 'bg-emerald-600 text-white hover:bg-emerald-500 focus-visible:outline-emerald-600',
};
$base = implode(' ', [
    'inline-flex items-center justify-center gap-2 rounded-lg font-medium',
    'transition-colors duration-150',
    'focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2',
    'disabled:opacity-50 disabled:cursor-not-allowed',
    $sizeClass,
    $variantClass,
]);
// When used as a class-based component, $baseClasses is already set by the PHP class.
// When used as an anonymous component, we compute it here.
$resolvedClasses = isset($baseClasses) ? $baseClasses : $base;
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $resolvedClasses]) }}
    @if($loading) disabled aria-busy="true" @endif
>
    @if($loading)
        <svg class="animate-spin -ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    @endif
    {{ $slot }}
</button>
