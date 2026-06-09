@php
    $user     = auth()->user();
    $roleName = $user?->roleName();
@endphp

<div class="flex h-full flex-col">

    {{-- ── Brand lockup ──────────────────────────────────────────────────── --}}
    <div class="flex h-[58px] shrink-0 items-center gap-3 px-5 border-b border-white/[0.06]">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-sky-500/10 ring-1 ring-sky-500/20">
            <x-nav-icon name="shield" class="h-3.5 w-3.5 text-sky-400" />
        </div>
        <div class="leading-tight">
            <p class="text-[13px] font-bold tracking-[0.07em] text-white">SHOPPA</p>
            <p class="text-[9px] font-semibold tracking-[0.16em] text-sky-600 uppercase">Verified Market</p>
        </div>
    </div>

    {{-- ── Role context label ─────────────────────────────────────────────── --}}
    @if($roleName)
        <div class="px-5 pt-6 pb-2 shrink-0">
            <p class="text-[10px] font-semibold tracking-[0.18em] uppercase text-slate-600 select-none">
                {{ $roleName->label() }}
            </p>
        </div>
    @else
        <div class="pt-5 shrink-0"></div>
    @endif

    {{-- ── Navigation ─────────────────────────────────────────────────────── --}}
    <nav class="flex-1 overflow-y-auto px-3 pb-3" aria-label="Sidebar navigation">
        <ul role="list" class="space-y-px">
            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['active']); @endphp
                <li>
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] transition-all duration-100 select-none',
                            'font-semibold text-white'   => $active,
                            'font-normal text-slate-400 hover:text-slate-200 hover:bg-white/[0.03]' => !$active,
                        ])
                        @if($active) aria-current="page" @endif
                    >
                        {{-- Precision active bar --}}
                        @if($active)
                            <span class="absolute left-0 inset-y-[7px] w-[2px] rounded-r-full bg-sky-400" aria-hidden="true"></span>
                        @endif

                        <x-nav-icon
                            :name="$item['icon']"
                            @class([
                                'h-3.5 w-3.5 shrink-0 transition-colors',
                                'text-sky-400'  => $active,
                                'text-slate-600 group-hover:text-slate-400' => !$active,
                            ])
                        />
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- ── Build marker ────────────────────────────────────────────────────── --}}
    <div class="shrink-0 px-5 pb-5 pt-3 border-t border-white/[0.04]">
        <p class="text-[10px] font-medium tracking-wider text-slate-700 select-none">S1 · Beta</p>
    </div>

</div>
