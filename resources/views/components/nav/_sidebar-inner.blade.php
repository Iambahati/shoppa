@php
    $user     = auth()->user();
    $roleName = $user?->roleName();
@endphp

<div class="flex h-full flex-col gap-y-5 overflow-y-auto pb-4">

    {{-- Brand --}}
    <div class="flex h-16 shrink-0 items-center gap-2.5 px-5 border-b border-slate-800">
        <span class="text-xl font-bold text-white tracking-tight">Shoppa</span>
        <span class="rounded-full bg-sky-500/20 px-2 py-0.5 text-xs font-medium text-sky-400 ring-1 ring-sky-500/30">
            verified
        </span>
    </div>

    {{-- Navigation --}}
    <nav class="flex flex-1 flex-col px-3" aria-label="Sidebar navigation">
        <ul role="list" class="space-y-0.5">
            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['active']); @endphp
                <li>
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-100',
                            'border-l-2 border-sky-500 bg-white/10 text-white pl-[10px]'                => $active,
                            'border-l-2 border-transparent text-slate-400 hover:bg-white/5 hover:text-slate-200' => !$active,
                        ])
                        @if($active) aria-current="page" @endif
                    >
                        <x-nav-icon :name="$item['icon']" class="h-4 w-4 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- User footer --}}
    @auth
        <div class="mx-3 mt-auto border-t border-slate-800 pt-4">
            <div class="flex items-center gap-3 px-2">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-sky-600 text-xs font-semibold text-white uppercase">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-white">{{ $user->name }}</p>
                    <p class="truncate text-xs text-slate-400">{{ $roleName?->label() ?? 'Guest' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3 px-2">
                @csrf
                <button type="submit" class="text-xs text-slate-500 hover:text-slate-300 transition-colors">
                    Sign out
                </button>
            </form>
        </div>
    @endauth

</div>
