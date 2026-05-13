@php
    $iconBg = match($iconColor) {
        'blue'   => 'bg-blue-50 text-blue-600',
        'amber'  => 'bg-amber-50 text-amber-600',
        'red'    => 'bg-red-50 text-red-600',
        'purple' => 'bg-purple-50 text-purple-600',
        default  => 'bg-emerald-50 text-emerald-600',
    };
    $trendClasses = match($trendDir) {
        'down'    => 'text-red-600',
        'neutral' => 'text-stone-400',
        default   => 'text-emerald-600',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl bg-white p-5 ring-1 ring-stone-950/5 shadow-sm']) }}>
    <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-stone-500">{{ $label }}</p>
        <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $iconBg }}">
            <x-nav.icon :name="$icon" class="h-4 w-4" />
        </span>
    </div>
    <p class="mt-3 text-2xl font-semibold text-stone-900 tracking-tight">{{ $value }}</p>
    @if($trend)
        <p class="mt-1 text-xs {{ $trendClasses }} font-medium">
            {{ $trendDir === 'up' ? '↑' : ($trendDir === 'down' ? '↓' : '–') }}
            {{ $trend }} vs last month
        </p>
    @endif
</div>