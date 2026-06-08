@php
    $iconBg = match($iconColor) {
        'blue'   => 'bg-sky-50 text-sky-600',
        'amber'  => 'bg-amber-50 text-amber-600',
        'red'    => 'bg-red-50 text-red-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'blush'  => 'bg-pink-50 text-pink-500',
        default  => 'bg-emerald-50 text-emerald-600',
    };
    $trendColor = match($trendDir) {
        'down'    => 'text-red-500 bg-red-50',
        'neutral' => 'text-slate-400 bg-slate-50',
        default   => 'text-emerald-600 bg-emerald-50',
    };
    $trendArrow = match($trendDir) {
        'down'    => '↓',
        'neutral' => '–',
        default   => '↑',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white px-6 py-5 ring-1 ring-slate-900/5 shadow-sm']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0 flex-1">
            <p class="text-xs font-500 uppercase tracking-wide text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-4xl font-bold text-slate-900 tabular-nums leading-none">{{ $value }}</p>
            @if($trend)
                <span class="mt-2 inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium {{ $trendColor }}">
                    {{ $trendArrow }} {{ $trend }}
                </span>
            @endif
        </div>
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $iconBg }}">
            <x-nav-icon :name="$icon" class="h-5 w-5" />
        </span>
    </div>
</div>
