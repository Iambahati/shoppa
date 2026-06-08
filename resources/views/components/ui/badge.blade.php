@props(['color' => 'stone', 'size' => 'sm'])
@php
$colorMap = [
    'stone'   => 'bg-stone-100 text-stone-700 ring-stone-200',
    'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'amber'   => 'bg-amber-50 text-amber-700 ring-amber-200',
    'red'     => 'bg-red-50 text-red-700 ring-red-200',
    'blue'    => 'bg-blue-50 text-blue-700 ring-blue-200',
    'purple'  => 'bg-purple-50 text-purple-700 ring-purple-200',
];
$sizeMap = [
    'xs' => 'px-1.5 py-0.5 text-xs',
    'sm' => 'px-2 py-1 text-xs',
    'md' => 'px-2.5 py-1 text-sm',
];
$resolvedClasses = isset($classes)
    ? $classes
    : implode(' ', [
        'inline-flex items-center rounded-md font-medium ring-1 ring-inset',
        $colorMap[$color] ?? $colorMap['stone'],
        $sizeMap[$size]   ?? $sizeMap['sm'],
    ]);
@endphp
<span {{ $attributes->merge(['class' => $resolvedClasses]) }}>{{ $slot }}</span>
