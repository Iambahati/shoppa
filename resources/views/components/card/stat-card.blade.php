@php
    $iconBg = match($iconColor) {
        'blue'   => 'bg-sky-500/15 text-sky-400',
        'amber'  => 'bg-amber-500/15 text-amber-400',
        'red'    => 'bg-red-500/15 text-red-400',
        'purple' => 'bg-purple-500/15 text-purple-400',
        'blush'  => 'bg-pink-500/15 text-pink-400',
        default  => 'bg-emerald-500/15 text-emerald-400',
    };
    $trendColor = match($trendDir) {
        'down'    => 'text-red-400 bg-red-500/15',
        'neutral' => 'text-slate-400 bg-white/5',
        default   => 'text-emerald-400 bg-emerald-500/15',
    };
    $trendArrow = match($trendDir) {
        'down'    => '↓',
        'neutral' => '–',
        default   => '↑',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-slate-800 px-6 py-5 ring-1 ring-white/5']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0 flex-1">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $label }}</p>
            <p class="mt-2 text-4xl font-bold text-white tabular-nums leading-none">{{ $value }}</p>
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
