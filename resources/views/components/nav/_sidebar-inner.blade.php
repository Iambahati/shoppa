@php
    $user     = auth()->user();
    $roleName = $user?->roleName();
@endphp

<div class="flex h-full flex-col">

    {{-- DS brand lock-up: wordmark + inline verified pill (dark sidebar variant) --}}
    <div class="flex h-16 shrink-0 items-center gap-2.5 px-5 border-b border-stone-800">
        <span class="text-[18px] font-semibold tracking-tight text-white leading-none">Shoppa</span>
        {{-- Dark sidebar variant: emerald-400 text on emerald-500/18 bg --}}
        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/18 px-2 py-0.5 text-[11px] font-medium text-emerald-400 ring-1 ring-inset ring-emerald-500/30">
            <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.307 4.491 4.491 0 01-1.307-3.497A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.483 4.79-1.88-1.88a.75.75 0 00-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
            verified
        </span>
    </div>

    {{-- Role context label --}}
    @if($roleName)
        <div class="px-5 pt-6 pb-2 shrink-0">
            <p class="text-[10px] font-semibold tracking-[0.18em] uppercase text-stone-500 select-none">
                {{ $roleName->label() }}
            </p>
        </div>
    @else
        <div class="pt-5 shrink-0"></div>
    @endif

    {{-- DS navigation: rounded-lg px-3 py-2, idle=stone-400, active/hover=stone-800 bg + white text --}}
    <nav class="flex-1 overflow-y-auto px-3 pb-3" aria-label="Sidebar navigation">
        <ul role="list" class="space-y-px">
            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['active']); @endphp
                <li>
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors duration-100 select-none',
                            'bg-stone-800 text-white'                          => $active,
                            'text-stone-400 hover:bg-stone-800 hover:text-white' => !$active,
                        ])
                        @if($active) aria-current="page" @endif
                    >
                        <x-nav-icon
                            :name="$item['icon']"
                            @class([
                                'h-4 w-4 shrink-0 transition-colors',
                                'text-white'                                   => $active,
                                'text-stone-500 group-hover:text-stone-300'    => !$active,
                            ])
                        />
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>


</div>
