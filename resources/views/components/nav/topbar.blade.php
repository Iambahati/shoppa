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

            {{-- ── Notifications ─────────────────────────────────────────── --}}
            <div class="relative" x-data="{ open: false }">

                {{-- Bell trigger --}}
                <button
                    type="button"
                    @click="open = !open"
                    @keydown.escape.window="open = false"
                    class="relative rounded-lg p-2.5 text-slate-500 transition-colors hover:text-slate-300 hover:bg-white/[0.05]"
                    aria-label="Notifications"
                    :aria-expanded="open"
                >
                    <x-nav-icon name="bell" class="h-4 w-4" />

                    {{-- Unread count badge --}}
                    @if($unreadCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 flex h-[18px] min-w-[18px] items-center justify-center rounded-full
                                     bg-sky-500 px-1 text-[9px] font-bold leading-none text-white ring-2 ring-slate-950">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </button>

                {{-- Notification panel --}}
                <div
                    x-show="open"
                    x-cloak
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                    class="absolute right-0 top-full z-50 mt-2 w-96 origin-top-right
                           rounded-2xl border border-white/[0.08] bg-slate-900
                           shadow-2xl shadow-black/60 overflow-hidden"
                    role="dialog"
                    aria-label="Notifications panel"
                    style="display: none;"
                >

                    {{-- Panel header --}}
                    <div class="flex items-center justify-between border-b border-white/[0.06] px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <h2 class="text-[13px] font-semibold text-white">Notifications</h2>
                            @if($unreadCount > 0)
                                <span class="rounded-full bg-sky-500/15 px-2 py-0.5 text-[10px] font-semibold text-sky-400 ring-1 ring-sky-500/20">
                                    {{ $unreadCount }} new
                                </span>
                            @endif
                        </div>
                        @if($unreadCount > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="text-[11px] font-medium text-slate-500 transition-colors hover:text-sky-400"
                                >
                                    Mark all read
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Notification list --}}
                    @if($notifications->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/[0.04] ring-1 ring-white/[0.06] mb-3">
                                <x-nav-icon name="bell" class="h-5 w-5 text-slate-600" />
                            </div>
                            <p class="text-[13px] font-medium text-slate-400">All caught up</p>
                            <p class="mt-1 text-[12px] text-slate-600">No new notifications</p>
                        </div>
                    @else
                        <div class="max-h-[420px] overflow-y-auto divide-y divide-white/[0.04]">
                            @foreach($notifications as $notification)
                                @php
                                    $d = $notification->data;
                                    $priority = $d['priority'] ?? 'info';
                                    [$dotColor, $borderColor, $iconColor, $bgHover] = match($priority) {
                                        'critical' => ['bg-red-500',     'border-l-red-500/70',    'text-red-400',     'hover:bg-red-500/[0.04]'],
                                        'warning'  => ['bg-amber-500',   'border-l-amber-500/70',  'text-amber-400',   'hover:bg-amber-500/[0.04]'],
                                        'success'  => ['bg-emerald-500', 'border-l-emerald-500/70','text-emerald-400', 'hover:bg-emerald-500/[0.04]'],
                                        default    => ['bg-sky-500',     'border-l-sky-500/70',    'text-sky-400',     'hover:bg-sky-500/[0.04]'],
                                    };
                                @endphp
                                <a
                                    href="{{ route('notifications.open', $notification->id) }}"
                                    class="flex items-start gap-3.5 border-l-2 {{ $borderColor }} px-4 py-3.5 transition-colors {{ $bgHover }}"
                                >
                                    {{-- Icon --}}
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl
                                                bg-white/[0.04] ring-1 ring-white/[0.06]">
                                        <x-nav-icon :name="$d['icon'] ?? 'bell'" class="h-3.5 w-3.5 {{ $iconColor }}" />
                                    </div>

                                    {{-- Content --}}
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[13px] font-medium leading-snug text-white truncate">
                                            {{ $d['title'] }}
                                        </p>
                                        <p class="mt-0.5 text-[11px] leading-relaxed text-slate-500 line-clamp-2">
                                            {{ $d['message'] }}
                                        </p>
                                        <p class="mt-1.5 text-[10px] font-medium text-slate-600">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    {{-- Unread dot --}}
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full {{ $dotColor }}" aria-label="Unread"></span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>

            {{-- Divider --}}
            <div class="mx-2 h-5 w-px bg-white/[0.08]" aria-hidden="true"></div>

            {{-- ── User menu ─────────────────────────────────────────────── --}}
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
                    <x-nav-icon
                        name="chevron-d"
                        class="h-3.5 w-3.5 text-slate-600 transition-transform duration-150"
                        ::class="open ? 'rotate-180' : ''"
                    />
                </button>

                {{-- User dropdown --}}
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
                    <div class="px-4 py-3.5 border-b border-white/[0.06]">
                        <p class="text-[13px] font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                    </div>

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
