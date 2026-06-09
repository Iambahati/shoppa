@php
    $accentBg = match($iconColor) {
        'blue'   => 'bg-sky-400',
        'amber'  => 'bg-amber-400',
        'red'    => 'bg-red-400',
        'purple' => 'bg-violet-400',
        'blush'  => 'bg-pink-400',
        default  => 'bg-emerald-400',
    };
    $sparkColor = match($iconColor) {
        'blue'   => 'text-sky-400',
        'amber'  => 'text-amber-400',
        'red'    => 'text-red-400',
        'purple' => 'text-violet-400',
        'blush'  => 'text-pink-400',
        default  => 'text-emerald-400',
    };
    $trendColor = match($trendDir) {
        'down'    => 'text-red-400',
        'neutral' => 'text-slate-500',
        default   => 'text-emerald-400',
    };
    $trendPrefix = match($trendDir) {
        'down'    => '↓',
        'neutral' => '—',
        default   => '↑',
    };

    // Sparkline — normalised to 20px height, 100px wide coordinate space
    $sparkPoints = null;
    if ($sparkline) {
        $vals  = array_map('floatval', explode(',', $sparkline));
        $min   = min($vals);
        $max   = max($vals);
        $range = max($max - $min, 1);
        $count = count($vals) - 1;
        $pts   = [];
        foreach ($vals as $i => $v) {
            $x = $count > 0 ? round(($i / $count) * 100, 2) : 50;
            $y = round(20 - (($v - $min) / $range) * 17, 2);
            $pts[] = "$x,$y";
        }
        $sparkPoints = implode(' ', $pts);
    }
@endphp

<div {{ $attributes->merge(['class' => 'stat-card-animate relative overflow-hidden rounded-xl bg-slate-800/60 ring-1 ring-white/[0.06]']) }}>

    {{-- Left accent bar: the only use of accent colour in this card --}}
    <div class="absolute left-0 top-4 bottom-4 w-[2px] rounded-r-full {{ $accentBg }}" aria-hidden="true"></div>

    <div class="px-5 pt-5 pb-4">

        {{-- Label --}}
        <p class="text-[10px] font-bold tracking-[0.18em] uppercase text-slate-500 select-none pl-1">{{ $label }}</p>

        {{-- Value + optional sparkline side by side --}}
        <div class="mt-2.5 flex items-end justify-between gap-2 pl-1">
            <p class="text-[2.75rem] font-bold leading-none text-white tabular-nums tracking-tight">{{ $value }}</p>

            @if($sparkPoints)
                <svg class="mb-0.5 h-[22px] w-20 shrink-0 overflow-visible opacity-50 {{ $sparkColor }}"
                     viewBox="0 0 100 20" preserveAspectRatio="none" aria-hidden="true">
                    <polyline
                        points="{{ $sparkPoints }}"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            @endif
        </div>

        {{-- Trend --}}
        @if($trend)
            <p class="mt-2 pl-1 text-[11px] leading-none {{ $trendColor }}">{{ $trendPrefix }} {{ $trend }}</p>
        @endif

    </div>
</div>
