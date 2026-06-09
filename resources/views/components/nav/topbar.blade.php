<header class="sticky top-0 z-20 flex h-14 shrink-0 items-center gap-x-4 border-b border-white/[0.06] bg-slate-950/80 backdrop-blur-md px-4 sm:gap-x-6 sm:px-6 lg:px-8">

    {{-- Mobile menu trigger --}}
    <button
        type="button"
        class="-m-2 p-2 text-slate-500 transition-colors hover:text-slate-300 lg:hidden"
        @click="sidebarOpen = true"
        aria-label="Open sidebar"
    >
        <x-nav-icon name="bars" class="h-5 w-5" />
    </button>
    <div class="mx-1 h-5 w-px bg-white/10 lg:hidden" aria-hidden="true"></div>

    {{-- Page title --}}
    <div class="flex flex-1 items-center min-w-0">
        @isset($title)
            <h1 class="truncate text-[13px] font-medium text-slate-400 tracking-wide">{{ $title }}</h1>
        @endisset
    </div>

    {{-- Right cluster --}}
    <div class="flex items-center gap-0.5">
        @auth

            {{-- Notifications --}}
            <a
                href="#"
                class="relative rounded-lg p-2.5 text-slate-500 transition-colors hover:text-slate-300 hover:bg-white/[0.05]"
                aria-label="Notifications"
            >
                <x-nav-icon name="bell" class="h-4 w-4" />
                <span class="absolute top-2 right-2 h-1.5 w-1.5 rounded-full bg-sky-400 ring-2 ring-slate-950" aria-hidden="true"></span>
            </a>

            {{-- Divider --}}
            <div class="mx-2 h-5 w-px bg-white/[0.08]" aria-hidden="true"></div>

            {{-- User menu --}}
            <div class="relative" x-data="{ open: false }">
                <button
                    type="button"
                    @click="open = !open"
                    @keydown.escape.window="open = false"
                    class="flex items-center gap-2 rounded-lg pl-1 pr-2.5 py-1.5 transition-all hover:bg-white/[0.05]"
                    :aria-expanded="open"
                >
                    <span class="flex h-7 w-7 items-center justify-center rounded-full
                                 bg-gradient-to-br from-sky-500 to-sky-700
                                 text-[11px] font-bold tracking-wide text-white uppercase
                                 shadow-sm shadow-sky-900/50">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </span>
                    <span class="hidden text-[13px] font-medium text-slate-300 sm:block leading-none">
                        {{ explode(' ', auth()->user()->name)[0] }}
                    </span>
                    <x-nav-icon name="chevron-d" class="h-3.5 w-3.5 text-slate-600 transition-transform duration-150" ::class="open ? 'rotate-180' : ''" />
                </button>

                {{-- Dropdown --}}
                <div
                    x-show="open"
                    x-cloak
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                    class="absolute right-0 top-full z-50 mt-2 w-56 origin-top-right
                           rounded-xl border border-white/[0.08] bg-slate-900
                           shadow-2xl shadow-black/50"
                    role="menu"
                    style="display: none;"
                >
                    {{-- User identity header --}}
                    <div class="px-4 py-3.5 border-b border-white/[0.06]">
                        <p class="text-[13px] font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                    </div>

                    {{-- Actions --}}
                    <div class="py-1.5">
                        <a
                            href="{{ route('shared.profile') }}"
                            class="flex items-center gap-2.5 px-4 py-2 text-[13px] text-slate-400
                                   transition-colors hover:text-white hover:bg-white/[0.05]"
                            role="menuitem"
                        >
                            <x-nav-icon name="user" class="h-3.5 w-3.5 shrink-0 text-slate-600" />
                            Profile settings
                        </a>
                    </div>

                    {{-- Sign out --}}
                    <div class="py-1.5 border-t border-white/[0.06]">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="flex w-full items-center gap-2.5 px-4 py-2 text-[13px]
                                       text-red-400 transition-colors hover:text-red-300 hover:bg-red-500/[0.06]"
                                role="menuitem"
                            >
                                <x-nav-icon name="x" class="h-3.5 w-3.5 shrink-0" />
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        @endauth
    </div>

</header>
