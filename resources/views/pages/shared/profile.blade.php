@php
    $isStaff = auth()->user()->isStaff();
    $layout  = $isStaff ? 'layouts.dashboard' : 'layouts.app';
    $role    = $user->role?->name ?? 'User';
    $initials = collect(explode(' ', $user->name))->take(2)->map(fn($p) => strtoupper(substr($p, 0, 1)))->join('');
    $roleBadgeColor = match(true) {
        in_array($role, ['Super Admin', 'Admin'])     => 'red',
        $role === 'Verifier'                           => 'emerald',
        in_array($role, ['Vendor Manager', 'Vendor'])  => 'amber',
        $role === 'Customer Service'                   => 'purple',
        $role === 'Content Manager'                    => 'purple',
        default                                        => 'stone',
    };
@endphp

<x-dynamic-component :component="$layout">
    <x-slot:title>Profile settings</x-slot:title>

    {{-- ── PROFILE HEADER ──────────────────────────────────────────────── --}}
    <div class="mb-8 card p-6">
        <div class="flex flex-col items-start gap-5 sm:flex-row sm:items-center">

            {{-- Avatar --}}
            <div class="relative shrink-0">
                <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-emerald-600 text-xl font-bold text-white">
                    {{ $initials }}
                </div>
                <span class="absolute -bottom-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-emerald-500 ring-2 ring-white">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                </span>
            </div>

            {{-- Identity --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-xl font-semibold text-stone-900 truncate">{{ $user->name }}</h1>
                    <x-ui-badge :color="$roleBadgeColor">{{ $role }}</x-ui-badge>
                </div>
                <p class="mt-1 text-sm text-stone-500 truncate">{{ $user->email }}</p>
                <p class="mt-1.5 text-xs text-stone-400">
                    Member since {{ $user->created_at->format('F Y') }}
                    @if($user->email_verified_at)
                        &middot;
                        <span class="text-emerald-600 font-medium">Email verified</span>
                    @endif
                </p>
            </div>

        </div>
    </div>

    {{-- ── FLASH MESSAGES ───────────────────────────────────────────────── --}}
    @if(session('success'))
        <x-ui-alert type="success" class="mb-6">{{ session('success') }}</x-ui-alert>
    @endif
    @if(session('error'))
        <x-ui-alert type="error" class="mb-6">{{ session('error') }}</x-ui-alert>
    @endif

    {{-- ── TWO-COLUMN LAYOUT ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ── EDIT PROFILE FORM ─────────────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div class="card overflow-hidden">
                <div class="border-b border-stone-100 px-6 py-4">
                    <h2 class="text-sm font-semibold text-stone-900">Personal information</h2>
                    <p class="mt-0.5 text-xs text-stone-500">Update your name and contact details</p>
                </div>
                <form method="POST" action="{{ route('shared.profile.update') }}" class="px-6 py-6 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label for="name" class="mb-1.5 block text-xs font-medium text-stone-700">Full name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            autocomplete="name"
                            class="form-input"
                            placeholder="Your full name"
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email (read-only) --}}
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-stone-700">Email address</label>
                        <div class="flex items-center gap-2 rounded-lg bg-stone-50 px-4 py-2.5 ring-1 ring-inset ring-stone-200">
                            <span class="flex-1 text-sm text-stone-500">{{ $user->email }}</span>
                            @if($user->email_verified_at)
                                <span class="flex items-center gap-1 text-[11px] font-medium text-emerald-600">
                                    <svg class="h-3 w-3" viewBox="0 0 12 12" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 11A5 5 0 1 0 6 1a5 5 0 0 0 0 10zm2.354-6.146a.5.5 0 0 0-.708-.708L5.5 6.293 4.354 5.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l2.5-2.5z"/>
                                    </svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                        <p class="mt-1.5 text-xs text-stone-400">Email cannot be changed here. Contact support if needed.</p>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="mb-1.5 block text-xs font-medium text-stone-700">
                            Phone number
                            <span class="ml-1 text-stone-400 font-normal">(optional)</span>
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            autocomplete="tel"
                            class="form-input"
                            placeholder="+254 7XX XXX XXX"
                        >
                        @error('phone')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end border-t border-stone-100 pt-5">
                        <x-ui-button type="submit">Save changes</x-ui-button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── ACCOUNT SUMMARY ──────────────────────────────────────── --}}
        <div class="space-y-4">

            {{-- Account card --}}
            <div class="card overflow-hidden">
                <div class="border-b border-stone-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-stone-900">Account</h2>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <div>
                        <p class="text-[11px] font-medium uppercase tracking-wide text-stone-400">Role</p>
                        <div class="mt-1">
                            <x-ui-badge :color="$roleBadgeColor">{{ $role }}</x-ui-badge>
                        </div>
                    </div>
                    <div class="border-t border-stone-100 pt-3">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-stone-400">Member since</p>
                        <p class="mt-1 text-sm text-stone-600">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="border-t border-stone-100 pt-3">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-stone-400">Email status</p>
                        <div class="mt-1 flex items-center gap-1.5">
                            @if($user->email_verified_at)
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <p class="text-sm text-emerald-600">Verified</p>
                            @else
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                <p class="text-sm text-amber-600">Unverified</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trust badge card --}}
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4">
                <div class="flex items-start gap-3">
                    <x-trust-verified-pill size="sm" />
                    <div>
                        <p class="text-xs font-semibold text-emerald-900">Shoppa Verified Account</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-emerald-700">Your identity is on the Shoppa trust network.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── CHANGE PASSWORD ──────────────────────────────────────────────── --}}
    <div class="mt-6 card overflow-hidden">
        <div class="border-b border-stone-100 px-6 py-4">
            <h2 class="text-sm font-semibold text-stone-900">Security</h2>
            <p class="mt-0.5 text-xs text-stone-500">Update your password to keep your account secure</p>
        </div>
        <form method="POST" action="{{ route('shared.profile.password') }}" class="px-6 py-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                <div>
                    <label for="current_password" class="mb-1.5 block text-xs font-medium text-stone-700">Current password</label>
                    <input type="password" id="current_password" name="current_password"
                        autocomplete="current-password" class="form-input" placeholder="••••••••">
                    @error('current_password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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

            <div class="mt-5 flex items-center justify-between border-t border-stone-100 pt-5">
                <p class="text-xs text-stone-400">Use a minimum of 8 characters, including letters and numbers.</p>
                <x-ui-button type="submit" variant="secondary">Update password</x-ui-button>
            </div>
        </form>
    </div>

</x-dynamic-component>
