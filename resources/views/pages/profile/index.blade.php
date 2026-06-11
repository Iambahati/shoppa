@php
    $isStaff        = auth()->user()->isStaff();
    $layout         = $isStaff ? 'layouts.dashboard' : 'layouts.app';
    $role           = $user->role?->name ?? 'User';
    $initials       = collect(explode(' ', $user->name))
                        ->take(2)
                        ->map(fn($p) => strtoupper(substr($p, 0, 1)))
                        ->join('');
    $roleBadgeColor = match(true) {
        in_array($role, ['Super Admin', 'Admin'])      => 'red',
        $role === 'Verifier'                            => 'emerald',
        in_array($role, ['Vendor Manager', 'Vendor'])  => 'amber',
        in_array($role, ['Customer Service',
                          'Content Manager'])          => 'purple',
        default                                         => 'stone',
    };

    // Placeholder sessions — replace with real session data in Sprint 2+
    $sessions = [
        ['device' => 'Chrome on macOS',  'ip' => '41.90.64.12',   'location' => 'Nairobi, KE',  'last' => '2 minutes ago',  'current' => true],
        ['device' => 'Safari on iPhone', 'ip' => '41.90.64.15',   'location' => 'Nairobi, KE',  'last' => '3 hours ago',    'current' => false],
        ['device' => 'Firefox on Linux', 'ip' => '105.163.4.201', 'location' => 'Kisumu, KE',   'last' => '2 days ago',     'current' => false],
    ];
@endphp

<x-dynamic-component :component="$layout">
    <x-slot:title>Profile settings</x-slot:title>

    {{-- ── PAGE HEADING ─────────────────────────────────────────────────── --}}
    <div class="mb-6 flex items-center gap-4">
        {{-- Avatar --}}
        <div class="relative shrink-0">
            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-600 text-lg font-bold text-white select-none">
                {{ $initials }}
            </div>
            <span class="absolute -bottom-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-emerald-500 ring-2 ring-white">
                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
            </span>
        </div>

        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="page-heading truncate">{{ $user->name }}</h1>
                <x-ui-badge :color="$roleBadgeColor" size="xs">{{ $role }}</x-ui-badge>
            </div>
            <p class="mt-0.5 text-sm text-stone-500 truncate">{{ $user->email }}</p>
        </div>
    </div>

    {{-- ── FLASH MESSAGES ──────────────────────────────────────────────── --}}
    @if(session('success'))
        <x-ui-alert type="success" class="mb-5">{{ session('success') }}</x-ui-alert>
    @endif
    @if(session('error'))
        <x-ui-alert type="error" class="mb-5">{{ session('error') }}</x-ui-alert>
    @endif

    {{-- ── TWO-COLUMN: NAV CARD (left) + CONTENT PANELS (right) ─────────── --}}
    <div
        x-data="{ tab: '{{ session('_tab', 'account') }}' }"
        class="grid grid-cols-1 gap-6 lg:grid-cols-4"
    >

        {{-- ════════════════════════════════════════════════════════════════
             LEFT — Settings navigation card
        ═════════════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-1">
            <div class="card overflow-hidden">
                <div class="border-b border-stone-100 px-4 py-3">
                    <p class="section-label">Settings</p>
                </div>

                <nav class="p-2 space-y-0.5">

                    {{-- Account --}}
                    <button
                        @click="tab = 'account'"
                        :class="tab === 'account'
                            ? 'bg-emerald-50 text-emerald-700 font-medium'
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900'"
                        class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors duration-100"
                        type="button"
                    >
                        {{-- user-circle outline --}}
                        <svg class="h-4 w-4 shrink-0 opacity-60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Account
                    </button>

                    {{-- Security --}}
                    <button
                        @click="tab = 'security'"
                        :class="tab === 'security'
                            ? 'bg-emerald-50 text-emerald-700 font-medium'
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900'"
                        class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors duration-100"
                        type="button"
                    >
                        {{-- lock-closed outline --}}
                        <svg class="h-4 w-4 shrink-0 opacity-60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        Security
                    </button>

                    {{-- Notifications --}}
                    <button
                        @click="tab = 'notifications'"
                        :class="tab === 'notifications'
                            ? 'bg-emerald-50 text-emerald-700 font-medium'
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900'"
                        class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors duration-100"
                        type="button"
                    >
                        {{-- bell outline --}}
                        <svg class="h-4 w-4 shrink-0 opacity-60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        Notifications
                    </button>

                    {{-- Store (only for Vendor role) --}}
                    @if(in_array($role, ['Vendor', 'Super Admin', 'Admin']))
                    <button
                        @click="tab = 'store'"
                        :class="tab === 'store'
                            ? 'bg-emerald-50 text-emerald-700 font-medium'
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900'"
                        class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors duration-100"
                        type="button"
                    >
                        {{-- building-storefront outline --}}
                        <svg class="h-4 w-4 shrink-0 opacity-60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                        </svg>
                        Store
                    </button>
                    @endif

                </nav>

                {{-- Trust mark at base of card --}}
                <div class="border-t border-emerald-100 bg-emerald-50 px-4 py-3">
                    <div class="flex items-center gap-2">
                        <x-trust-verified-pill size="sm" />
                    </div>
                    <p class="mt-1.5 text-[11px] leading-relaxed text-emerald-700">
                        Every account on Shoppa is part of the trust network.
                    </p>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             RIGHT — Content panels (Alpine tab switching, no page reload)
        ═════════════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-3 space-y-5">

            {{-- ─────────────────────────────────────────────────────────
                 ACCOUNT TAB
            ──────────────────────────────────────────────────────────── --}}
            <div x-show="tab === 'account'" x-cloak class="space-y-5">

                {{-- Personal information --}}
                <div class="card overflow-hidden">
                    <div class="border-b border-stone-100 px-6 py-4">
                        <h2 class="text-sm font-semibold text-stone-900">Personal information</h2>
                        <p class="mt-0.5 text-xs text-stone-500">Update your name and contact details.</p>
                    </div>

                    <form method="POST" action="{{ route('shared.profile.update') }}" class="px-6 py-5 space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Full name --}}
                        <div>
                            <label for="name" class="mb-1.5 block text-xs font-medium text-stone-700">Full name</label>
                            <input
                                type="text" id="name" name="name"
                                value="{{ old('name', $user->name) }}"
                                required autocomplete="name"
                                class="form-input" placeholder="Your full name"
                            >
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email (read-only) with VerifiedPill --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-stone-700">Email address</label>
                            <div class="flex items-center gap-3 rounded-lg bg-stone-50 px-4 py-2.5 ring-1 ring-inset ring-stone-200">
                                <svg class="h-4 w-4 shrink-0 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                <span class="flex-1 text-sm text-stone-500 font-mono">{{ $user->email }}</span>
                                @if($user->email_verified_at)
                                    <x-trust-verified-pill size="sm">Verified</x-trust-verified-pill>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                        Unverified
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1.5 text-xs text-stone-400">Email cannot be changed here. Contact support if needed.</p>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="mb-1.5 block text-xs font-medium text-stone-700">
                                Phone number
                                <span class="ml-1 font-normal text-stone-400">(optional)</span>
                            </label>
                            <input
                                type="tel" id="phone" name="phone"
                                value="{{ old('phone', $user->phone ?? '') }}"
                                autocomplete="tel"
                                class="form-input" placeholder="+254 7XX XXX XXX"
                            >
                            @error('phone')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between border-t border-stone-100 pt-5">
                            <p class="text-xs text-stone-400">
                                Member since {{ $user->created_at->format('d M Y') }}
                            </p>
                            <x-ui-button type="submit">Save changes</x-ui-button>
                        </div>
                    </form>
                </div>

                {{-- Account summary (role, verified status) --}}
                <div class="card overflow-hidden">
                    <div class="border-b border-stone-100 px-6 py-4">
                        <h2 class="text-sm font-semibold text-stone-900">Account overview</h2>
                    </div>
                    <div class="grid grid-cols-1 divide-y divide-stone-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                        <div class="px-6 py-4">
                            <p class="section-label mb-1.5">Role</p>
                            <x-ui-badge :color="$roleBadgeColor">{{ $role }}</x-ui-badge>
                        </div>
                        <div class="px-6 py-4">
                            <p class="section-label mb-1.5">Email status</p>
                            <div class="flex items-center gap-1.5">
                                @if($user->email_verified_at)
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-sm text-emerald-600 font-medium">Verified</span>
                                @else
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                    <span class="text-sm text-amber-600 font-medium">Unverified</span>
                                @endif
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <p class="section-label mb-1.5">Joined</p>
                            <p class="text-sm text-stone-700">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ─────────────────────────────────────────────────────────
                 SECURITY TAB
            ──────────────────────────────────────────────────────────── --}}
            <div x-show="tab === 'security'" x-cloak class="space-y-5">

                {{-- Change password --}}
                <div class="card overflow-hidden">
                    <div class="border-b border-stone-100 px-6 py-4">
                        <h2 class="text-sm font-semibold text-stone-900">Change password</h2>
                        <p class="mt-0.5 text-xs text-stone-500">Use a minimum of 8 characters, including letters and numbers.</p>
                    </div>
                    <form method="POST" action="{{ route('shared.profile.password') }}" class="px-6 py-5 space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="mb-1.5 block text-xs font-medium text-stone-700">Current password</label>
                            <input type="password" id="current_password" name="current_password"
                                autocomplete="current-password" class="form-input" placeholder="••••••••">
                            @error('current_password')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="password" class="mb-1.5 block text-xs font-medium text-stone-700">New password</label>
                                <input type="password" id="password" name="password"
                                    autocomplete="new-password" class="form-input" placeholder="Min. 8 characters">
                                @error('password')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="mb-1.5 block text-xs font-medium text-stone-700">Confirm new password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    autocomplete="new-password" class="form-input" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="flex items-center justify-end border-t border-stone-100 pt-5">
                            <x-ui-button type="submit" variant="secondary">Update password</x-ui-button>
                        </div>
                    </form>
                </div>

                {{-- Active sessions --}}
                <div class="card overflow-hidden">
                    <div class="flex items-center justify-between border-b border-stone-100 px-6 py-4">
                        <div>
                            <h2 class="text-sm font-semibold text-stone-900">Active sessions</h2>
                            <p class="mt-0.5 text-xs text-stone-500">Devices currently signed in to your account.</p>
                        </div>
                        <form method="POST" action="#" {{-- route('shared.profile.sessions.destroy') in Sprint 2+ --}}>
                            @csrf
                            @method('DELETE')
                            <x-ui-button type="submit" variant="ghost" size="sm">Sign out all others</x-ui-button>
                        </form>
                    </div>

                    <ul class="divide-y divide-stone-100">
                        @foreach($sessions as $session)
                        <li class="flex items-start gap-4 px-6 py-4">
                            {{-- Device icon --}}
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-stone-100 text-stone-500">
                                @if(str_contains($session['device'], 'iPhone') || str_contains($session['device'], 'Android'))
                                    {{-- device-phone-mobile --}}
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3" />
                                    </svg>
                                @else
                                    {{-- computer-desktop --}}
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3" />
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="text-sm font-medium text-stone-900">{{ $session['device'] }}</p>
                                    @if($session['current'])
                                        <span class="inline-flex items-center rounded-md bg-emerald-50 px-1.5 py-0.5 text-[11px] font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            This device
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-0.5 font-mono text-xs text-stone-500">
                                    {{ $session['ip'] }} · {{ $session['location'] }}
                                </p>
                                <p class="mt-0.5 text-xs text-stone-400">Last active {{ $session['last'] }}</p>
                            </div>

                            @if(!$session['current'])
                                <form method="POST" action="#" {{-- route('shared.profile.sessions.destroy', $session['id']) --}} class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded-md px-2 py-1 text-xs font-medium text-stone-500 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                                        Sign out
                                    </button>
                                </form>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Danger zone --}}
                <div class="card overflow-hidden ring-red-200">
                    <div class="border-b border-red-100 bg-red-50 px-6 py-4">
                        <h2 class="text-sm font-semibold text-red-700">Danger zone</h2>
                        <p class="mt-0.5 text-xs text-red-600">These actions are permanent and cannot be undone.</p>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-stone-900">Sign out of all sessions</p>
                                <p class="mt-0.5 text-xs text-stone-500">Immediately ends every active session across all your devices.</p>
                            </div>
                            <form method="POST" action="#" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <x-ui-button type="submit" variant="secondary" size="sm">Sign out everywhere</x-ui-button>
                            </form>
                        </div>
                        <div class="border-t border-stone-100 pt-4 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-red-700">Delete account</p>
                                <p class="mt-0.5 text-xs text-stone-500">Permanently removes your account, listings, and data from Shoppa. This cannot be reversed.</p>
                            </div>
                            <x-ui-button variant="danger" size="sm" type="button"
                                x-data
                                @click="if(confirm('Are you sure? This cannot be undone.')) $el.closest('form').submit()">
                                Delete account
                            </x-ui-button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ─────────────────────────────────────────────────────────
                 NOTIFICATIONS TAB
            ──────────────────────────────────────────────────────────── --}}
            <div x-show="tab === 'notifications'" x-cloak>
                <div class="card overflow-hidden">
                    <div class="border-b border-stone-100 px-6 py-4">
                        <h2 class="text-sm font-semibold text-stone-900">Notification preferences</h2>
                        <p class="mt-0.5 text-xs text-stone-500">Choose what Shoppa can contact you about.</p>
                    </div>

                    <form method="POST" action="#" {{-- route('shared.profile.notifications') in Sprint 2+ --}}
                        x-data="{
                            email_orders:  true,
                            email_promos:  false,
                            email_security:true,
                            push_orders:   true,
                            push_messages: true,
                            push_promos:   false,
                        }"
                        class="divide-y divide-stone-100"
                    >
                        @csrf
                        @method('PUT')

                        {{-- Email notifications --}}
                        <div class="px-6 py-5">
                            <p class="section-label mb-4">Email</p>
                            <div class="space-y-4">

                                {{-- Toggle row helper (inline, repeated for clarity) --}}
                                <label class="flex items-start justify-between gap-4 cursor-pointer">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900">Order updates</p>
                                        <p class="mt-0.5 text-xs text-stone-500">Confirmation, shipment tracking and delivery notices.</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="email_orders = !email_orders"
                                        :aria-checked="email_orders"
                                        role="switch"
                                        :class="email_orders ? 'bg-emerald-600' : 'bg-stone-200'"
                                        class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                                    >
                                        <span
                                            :class="email_orders ? 'translate-x-4' : 'translate-x-0.5'"
                                            class="inline-block h-4 w-4 translate-y-0.5 rounded-full bg-white shadow-sm transition-transform duration-150"
                                        ></span>
                                    </button>
                                    <input type="hidden" name="email_orders" :value="email_orders ? '1' : '0'">
                                </label>

                                <label class="flex items-start justify-between gap-4 cursor-pointer">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900">Security alerts</p>
                                        <p class="mt-0.5 text-xs text-stone-500">New sign-ins, password changes and suspicious activity.</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="email_security = !email_security"
                                        :aria-checked="email_security"
                                        role="switch"
                                        :class="email_security ? 'bg-emerald-600' : 'bg-stone-200'"
                                        class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                                    >
                                        <span
                                            :class="email_security ? 'translate-x-4' : 'translate-x-0.5'"
                                            class="inline-block h-4 w-4 translate-y-0.5 rounded-full bg-white shadow-sm transition-transform duration-150"
                                        ></span>
                                    </button>
                                    <input type="hidden" name="email_security" :value="email_security ? '1' : '0'">
                                </label>

                                <label class="flex items-start justify-between gap-4 cursor-pointer">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900">Promotions & offers</p>
                                        <p class="mt-0.5 text-xs text-stone-500">Deals, new listings and Shoppa announcements.</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="email_promos = !email_promos"
                                        :aria-checked="email_promos"
                                        role="switch"
                                        :class="email_promos ? 'bg-emerald-600' : 'bg-stone-200'"
                                        class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                                    >
                                        <span
                                            :class="email_promos ? 'translate-x-4' : 'translate-x-0.5'"
                                            class="inline-block h-4 w-4 translate-y-0.5 rounded-full bg-white shadow-sm transition-transform duration-150"
                                        ></span>
                                    </button>
                                    <input type="hidden" name="email_promos" :value="email_promos ? '1' : '0'">
                                </label>

                            </div>
                        </div>

                        {{-- Push notifications --}}
                        <div class="px-6 py-5">
                            <p class="section-label mb-4">Push</p>
                            <div class="space-y-4">

                                <label class="flex items-start justify-between gap-4 cursor-pointer">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900">Order updates</p>
                                        <p class="mt-0.5 text-xs text-stone-500">Status changes on your active orders.</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="push_orders = !push_orders"
                                        :aria-checked="push_orders"
                                        role="switch"
                                        :class="push_orders ? 'bg-emerald-600' : 'bg-stone-200'"
                                        class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                                    >
                                        <span
                                            :class="push_orders ? 'translate-x-4' : 'translate-x-0.5'"
                                            class="inline-block h-4 w-4 translate-y-0.5 rounded-full bg-white shadow-sm transition-transform duration-150"
                                        ></span>
                                    </button>
                                    <input type="hidden" name="push_orders" :value="push_orders ? '1' : '0'">
                                </label>

                                <label class="flex items-start justify-between gap-4 cursor-pointer">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900">Messages</p>
                                        <p class="mt-0.5 text-xs text-stone-500">Direct messages from buyers or Shoppa support.</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="push_messages = !push_messages"
                                        :aria-checked="push_messages"
                                        role="switch"
                                        :class="push_messages ? 'bg-emerald-600' : 'bg-stone-200'"
                                        class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                                    >
                                        <span
                                            :class="push_messages ? 'translate-x-4' : 'translate-x-0.5'"
                                            class="inline-block h-4 w-4 translate-y-0.5 rounded-full bg-white shadow-sm transition-transform duration-150"
                                        ></span>
                                    </button>
                                    <input type="hidden" name="push_messages" :value="push_messages ? '1' : '0'">
                                </label>

                                <label class="flex items-start justify-between gap-4 cursor-pointer">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900">Promotions</p>
                                        <p class="mt-0.5 text-xs text-stone-500">Flash sales and curated device picks.</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="push_promos = !push_promos"
                                        :aria-checked="push_promos"
                                        role="switch"
                                        :class="push_promos ? 'bg-emerald-600' : 'bg-stone-200'"
                                        class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                                    >
                                        <span
                                            :class="push_promos ? 'translate-x-4' : 'translate-x-0.5'"
                                            class="inline-block h-4 w-4 translate-y-0.5 rounded-full bg-white shadow-sm transition-transform duration-150"
                                        ></span>
                                    </button>
                                    <input type="hidden" name="push_promos" :value="push_promos ? '1' : '0'">
                                </label>

                            </div>
                        </div>

                        <div class="flex items-center justify-end px-6 py-4">
                            <x-ui-button type="submit">Save preferences</x-ui-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ─────────────────────────────────────────────────────────
                 STORE TAB (Vendor only)
            ──────────────────────────────────────────────────────────── --}}
            <div x-show="tab === 'store'" x-cloak>
                <div class="card overflow-hidden">
                    <div class="border-b border-stone-100 px-6 py-4">
                        <h2 class="text-sm font-semibold text-stone-900">Store settings</h2>
                        <p class="mt-0.5 text-xs text-stone-500">Manage your seller profile and payout details.</p>
                    </div>
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-stone-100 text-stone-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-stone-700">Store settings coming in Sprint 2</p>
                        <p class="mt-1 text-xs text-stone-400">Your vendor profile, payout method and store bio will be configurable here.</p>
                    </div>
                </div>
            </div>

        </div>{{-- end right column --}}
    </div>{{-- end x-data grid --}}

</x-dynamic-component>
