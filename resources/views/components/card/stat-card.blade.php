@php
    // DS icon chip: tinted bg + matching icon text per iconColor
    [$chipBg, $chipText] = match($iconColor) {
        'blue'   => ['bg-blue-50',   'text-blue-600'],
        'amber'  => ['bg-amber-50',  'text-amber-600'],
        'red'    => ['bg-red-50',    'text-red-600'],
        'purple' => ['bg-purple-50', 'text-purple-600'],
        default  => ['bg-emerald-50','text-emerald-600'],
    };
    // DS trend: up=emerald, down=red, neutral=stone
    $trendColor = match($trendDir) {
        'down'    => 'text-red-600',
        'neutral' => 'text-stone-400',
        default   => 'text-emerald-600',
    };
    $trendPrefix = match($trendDir) {
        'down'    => '↓',
        'neutral' => '—',
        default   => '↑',
    };
@endphp

{{-- DS signature card: white + hairline ring-stone-950/5 + shadow-sm, p-5, rounded-xl --}}
<div {{ $attributes->merge(['class' => 'stat-card-animate card p-5']) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 flex-1">
            {{-- DS: label xs/medium stone-500 --}}
            <p class="text-xs font-medium text-stone-500 select-none">{{ $label }}</p>
            {{-- DS: value 2xl/semibold stone-900 tracking-tight --}}
            <p class="mt-2 text-2xl font-semibold text-stone-900 tracking-tight tabular-nums leading-none">{{ $value }}</p>
            @if($trend)
                <p class="mt-1.5 text-xs {{ $trendColor }}">{{ $trendPrefix }} {{ $trend }}</p>
            @endif
        </div>
        {{-- DS icon chip: h-9 w-9 rounded-lg, tinted bg --}}
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $chipBg }} {{ $chipText }}">
            <x-nav-icon :name="$icon" class="h-4 w-4" />
        </div>
    </div>
</div>
