<header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-x-4 border-b border-stone-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">

    {{-- Mobile menu trigger --}}
    <button type="button" class="-m-2.5 p-2.5 text-stone-700 lg:hidden" @click="sidebarOpen = true" aria-label="Open sidebar">
        <x-nav-icon name="bars" class="h-5 w-5" />
    </button>
    <div class="h-6 w-px bg-stone-200 lg:hidden" aria-hidden="true"></div>

    {{-- Page title --}}
    <div class="flex flex-1 items-center">
        @isset($title)
            <h1 class="text-sm font-semibold text-stone-900">{{ $title }}</h1>
        @endisset
    </div>

    {{-- Right cluster --}}
    <div class="flex items-center gap-x-4">

        @auth
            {{-- Notifications --}}
            <a href="#" class="relative -m-2.5 p-2.5 text-stone-400 hover:text-stone-600 transition-colors" aria-label="Notifications">
                <x-nav-icon name="bell" class="h-5 w-5" />
                <span class="absolute top-2 right-2 h-1.5 w-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
            </a>

            {{-- Role badge --}}
            @php $roleName = auth()->user()->roleName(); @endphp
            @if($roleName)
                <x-ui-badge :color="$staff ? 'purple' : 'emerald'" size="sm">
                    {{ $roleName->label() }}
                </x-ui-badge>
            @endif

            {{-- User dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button
                    type="button"
                    @click="open = !open"
                    class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-stone-700 hover:bg-stone-100 transition-colors"
                    :aria-expanded="open"
                >
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-600 text-xs font-semibold text-white uppercase">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </span>
                    <span class="hidden sm:block font-medium">{{ auth()->user()->name }}</span>
                    <x-nav-icon name="chevron-d" class="h-4 w-4 text-stone-400" />
                </button>

                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 top-full mt-1 z-50 w-48 origin-top-right rounded-xl bg-white py-1 shadow-lg ring-1 ring-stone-950/5"
                    role="menu"
                >
                    <a href="{{ route('shared.profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-stone-700 hover:bg-stone-50" role="menuitem">
                        <x-nav-icon name="user" class="h-4 w-4 text-stone-400" />
                        Profile
                    </a>
                    <div class="my-1 border-t border-stone-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-stone-700 hover:bg-stone-50" role="menuitem">
                            <x-nav-icon name="x" class="h-4 w-4 text-stone-400" />
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        @endauth

    </div>
</header>
