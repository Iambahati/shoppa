@php
    $user     = auth()->user();
    $roleName = $user?->roleName();
@endphp

<div class="flex h-full flex-col gap-y-5 overflow-y-auto px-4 pb-4">

    {{-- Brand --}}
    <div class="flex h-16 shrink-0 items-center gap-2">
        <span class="text-xl font-semibold text-white tracking-tight">Shoppa</span>
        <span class="rounded-full bg-emerald-500/20 px-2 py-0.5 text-xs font-medium text-emerald-400 ring-1 ring-emerald-500/30">
            verified
        </span>
    </div>

    {{-- Nav items --}}
    <nav class="flex flex-1 flex-col" aria-label="Main navigation">
        <ul role="list" class="space-y-0.5">
            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['active']);
                @endphp
                <li>
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors duration-100',
                            'bg-stone-800 text-white'                             => $isActive,
                            'text-stone-400 hover:bg-stone-800 hover:text-white'  => ! $isActive,
                        ])
                        @if($isActive) aria-current="page" @endif
                    >
                        <x-nav.icon :name="$item['icon']" class="h-4 w-4 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- User footer --}}
    @auth
        <div class="mt-auto border-t border-stone-800 pt-4">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xs font-semibold text-white uppercase">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-white">{{ $user->name }}</p>
                    <p class="truncate text-xs text-stone-400">{{ $roleName?->label() ?? 'Guest' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full text-left text-xs text-stone-500 hover:text-stone-300 transition-colors">
                    Sign out
                </button>
            </form>
        </div>
    @endauth

</div>