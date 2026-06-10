{{-- DS topbar: sticky h-16 bg-white border-b border-stone-200 shadow-sm --}}
<header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-x-4 border-b border-stone-200 bg-white shadow-sm px-4 sm:gap-x-6 sm:px-6 lg:px-8">

    {{-- Mobile menu trigger --}}
    <button
        type="button"
        class="-m-2 p-2 text-stone-500 transition-colors hover:text-stone-700 lg:hidden"
        @click="sidebarOpen = true"
        aria-label="Open sidebar"
    >
        <x-nav-icon name="bars" class="h-5 w-5" />
    </button>
    <div class="mx-1 h-5 w-px bg-stone-200 lg:hidden" aria-hidden="true"></div>

    {{-- Page title --}}
    <div class="flex flex-1 items-center min-w-0">
        @isset($title)
            <h1 class="truncate text-sm font-medium text-stone-900 tracking-tight">{{ $title }}</h1>
        @endisset
    </div>

    {{-- Right cluster --}}
    <div class="flex items-center gap-1">
        @auth

            {{-- ── Notifications ─────────────────────────────────────────── --}}
            <div class="relative" x-data="{ open: false }">

                {{-- Bell trigger --}}
                <button
                    type="button"
                    @click="open = !open"
                    @keydown.escape.window="open = false"
                    class="relative rounded-lg p-2.5 text-stone-400 transition-colors hover:text-stone-600 hover:bg-stone-100"
                    aria-label="Notifications"
                    :aria-expanded="open"
                >
                    <x-nav-icon name="bell" class="h-4 w-4" />

                    {{-- Unread dot — emerald-500 per DS --}}
                    @if($unreadCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 flex h-[18px] min-w-[18px] items-center justify-center rounded-full
                                     bg-emerald-500 px-1 text-[9px] font-bold leading-none text-white ring-2 ring-white">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </button>

                {{-- Notification panel — white card per DS --}}
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
                           rounded-2xl bg-white ring-1 ring-stone-950/5
                           shadow-lg overflow-hidden"
                    role="dialog"
                    aria-label="Notifications panel"
                    style="display: none;"
                >

                    {{-- Panel header --}}
                    <div class="flex items-center justify-between border-b border-stone-100 px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-semibold text-stone-900">Notifications</h2>
                            @if($unreadCount > 0)
                                <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                    {{ $unreadCount }} new
                                </span>
                            @endif
                        </div>
                        @if($unreadCount > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="text-[11px] font-medium text-stone-400 transition-colors hover:text-emerald-600"
                                >
                                    Mark all read
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Notification list --}}
                    @if($notifications->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-stone-50 ring-1 ring-stone-200 mb-3">
                                <x-nav-icon name="bell" class="h-5 w-5 text-stone-300" />
                            </div>
                            <p class="text-sm font-medium text-stone-500">All caught up</p>
                            <p class="mt-1 text-xs text-stone-400">No new notifications</p>
                        </div>
                    @else
                        <div class="max-h-[420px] overflow-y-auto divide-y divide-stone-50">
                            @foreach($notifications as $notification)
                                @php
                                    $d = $notification->data;
                                    $priority = $d['priority'] ?? 'info';
                                    [$dotColor, $borderColor, $iconBg, $iconText] = match($priority) {
                                        'critical' => ['bg-red-500',     'border-l-red-400',     'bg-red-50',     'text-red-600'],
                                        'warning'  => ['bg-amber-500',   'border-l-amber-400',   'bg-amber-50',   'text-amber-600'],
                                        'success'  => ['bg-emerald-500', 'border-l-emerald-400', 'bg-emerald-50', 'text-emerald-600'],
                                        default    => ['bg-blue-500',    'border-l-blue-400',    'bg-blue-50',    'text-blue-600'],
                                    };
                                @endphp
                                <a
                                    href="{{ route('notifications.open', $notification->id) }}"
                                    class="flex items-start gap-3.5 border-l-2 {{ $borderColor }} px-4 py-3.5 transition-colors hover:bg-stone-50"
                                >
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $iconBg }}">
                                        <x-nav-icon :name="$d['icon'] ?? 'bell'" class="h-3.5 w-3.5 {{ $iconText }}" />
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium leading-snug text-stone-900 truncate">
                                            {{ $d['title'] }}
                                        </p>
                                        <p class="mt-0.5 text-xs leading-relaxed text-stone-500 line-clamp-2">
                                            {{ $d['message'] }}
                                        </p>
                                        <p class="mt-1.5 text-[10px] font-medium text-stone-400">
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
            <div class="mx-2 h-5 w-px bg-stone-200" aria-hidden="true"></div>

            {{-- ── User menu ─────────────────────────────────────────────── --}}
            <div class="relative" x-data="{ open: false }">
                <button
                    type="button"
                    @click="open = !open"
                    @keydown.escape.window="open = false"
                    class="flex items-center gap-2 rounded-lg pl-1 pr-2.5 py-1.5 transition-all hover:bg-stone-100"
                    :aria-expanded="open"
                >
                    {{-- DS avatar: emerald-600 circle with 2-char initials --}}
                    <span class="flex h-7 w-7 items-center justify-center rounded-full
                                 bg-emerald-600
                                 text-[11px] font-semibold tracking-wide text-white uppercase">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </span>
                    <span class="hidden text-sm font-medium text-stone-700 sm:block leading-none">
                        {{ explode(' ', auth()->user()->name)[0] }}
                    </span>
                    <x-nav-icon
                        name="chevron-d"
                        class="h-3.5 w-3.5 text-stone-400 transition-transform duration-150"
                        ::class="open ? 'rotate-180' : ''"
                    />
                </button>

                {{-- User dropdown — white card per DS --}}
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
                           rounded-xl bg-white ring-1 ring-stone-950/5 shadow-lg"
                    role="menu"
                    style="display: none;"
                >
                    <div class="px-4 py-3.5 border-b border-stone-100">
                        <p class="text-sm font-semibold text-stone-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-stone-400 truncate mt-0.5">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="py-1.5">
                        <a
                            href="{{ route('shared.profile') }}"
                            class="flex items-center gap-2.5 px-4 py-2 text-sm text-stone-600
                                   transition-colors hover:text-stone-900 hover:bg-stone-50"
                            role="menuitem"
                        >
                            <x-nav-icon name="user" class="h-3.5 w-3.5 shrink-0 text-stone-400" />
                            Profile settings
                        </a>
                    </div>

                    <div class="py-1.5 border-t border-stone-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="flex w-full items-center gap-2.5 px-4 py-2 text-sm
                                       text-red-600 transition-colors hover:text-red-700 hover:bg-red-50"
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
