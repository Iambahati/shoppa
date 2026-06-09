@php
    $isStaff = auth()->user()->isStaff();
    $layout  = $isStaff ? 'layouts.dashboard' : 'layouts.app';
    $role    = $user->role?->name ?? 'User';
    $initials = collect(explode(' ', $user->name))->take(2)->map(fn($p) => strtoupper(substr($p, 0, 1)))->join('');
    $roleColor = match(true) {
        in_array($role, ['Super Admin', 'Admin'])    => ['from-red-500', 'to-orange-600', 'text-red-400', 'bg-red-500/15 ring-red-500/30'],
        $role === 'Verifier'                          => ['from-sky-500', 'to-emerald-600', 'text-sky-400', 'bg-sky-500/15 ring-sky-500/30'],
        in_array($role, ['Vendor Manager', 'Vendor']) => ['from-amber-500', 'to-orange-600', 'text-amber-400', 'bg-amber-500/15 ring-amber-500/30'],
        $role === 'Customer Service'                  => ['from-purple-500', 'to-violet-600', 'text-purple-400', 'bg-purple-500/15 ring-purple-500/30'],
        $role === 'Content Manager'                   => ['from-pink-500', 'to-rose-600', 'text-pink-400', 'bg-pink-500/15 ring-pink-500/30'],
        default                                       => ['from-sky-500', 'to-sky-700', 'text-sky-400', 'bg-sky-500/15 ring-sky-500/30'],
    };
@endphp

<x-dynamic-component :component="$layout">
    <x-slot:title>Profile settings</x-slot:title>

    {{-- ── HERO BANNER ──────────────────────────────────────────────────── --}}
    <div class="relative mb-8 overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">

        {{-- Decorative dot-grid background --}}
        <div class="absolute inset-0 opacity-40" aria-hidden="true">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="profile-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="1.5" cy="1.5" r="1" fill="#94a3b8" opacity="0.18"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#profile-dots)"/>
            </svg>
        </div>

        {{-- Gradient accent --}}
        <div class="absolute inset-y-0 left-0 w-1/3 bg-gradient-to-r from-sky-500/10 to-transparent" aria-hidden="true"></div>
        <div class="absolute -bottom-8 -right-8 h-32 w-32 rounded-full bg-sky-500/5 blur-2xl" aria-hidden="true"></div>

        <div class="relative flex flex-col items-start gap-5 px-8 py-8 sm:flex-row sm:items-center">

            {{-- Avatar --}}
            <div class="relative shrink-0">
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl
                            bg-gradient-to-br {{ $roleColor[0] }} {{ $roleColor[1] }}
                            text-2xl font-bold tracking-wide text-white
                            shadow-xl ring-4 ring-white/10">
                    {{ $initials }}
                </div>
                {{-- Online indicator --}}
                <span class="absolute -bottom-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full
                             bg-emerald-500 ring-2 ring-slate-800">
                    <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                </span>
            </div>

            {{-- Identity --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold text-white truncate">{{ $user->name }}</h1>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                                 ring-1 {{ $roleColor[3] }} {{ $roleColor[2] }}">
                        {{ $role }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-slate-400 truncate">{{ $user->email }}</p>
                <p class="mt-2 text-xs text-slate-500">
                    Member since {{ $user->created_at->format('F Y') }}
                    @if($user->email_verified_at)
                        &middot;
                        <span class="text-emerald-400">
                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-400 align-middle mr-0.5"></span>
                            Email verified
                        </span>
                    @endif
                </p>
            </div>

            {{-- SHOPPA mark --}}
            <div class="hidden shrink-0 sm:block" aria-hidden="true">
                <div class="flex flex-col items-end gap-0.5 opacity-30">
                    <div class="flex items-center gap-1.5">
                        <svg class="h-5 w-5 text-sky-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 1a.75.75 0 01.688.452l2.5 5.5 6 .875a.75.75 0 01.415 1.279l-4.338 4.227 1.024 5.968a.75.75 0 01-1.088.79L10 17.25l-5.201 2.841a.75.75 0 01-1.088-.79l1.024-5.968L.465 9.106a.75.75 0 01.415-1.279l6-.875 2.5-5.5A.75.75 0 0110 1z"/>
                        </svg>
                        <span class="text-xs font-bold tracking-[0.2em] text-slate-300 uppercase">Shoppa</span>
                    </div>
                    <span class="text-[9px] font-medium tracking-wider text-slate-500 uppercase">Trust-as-a-Service</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FLASH MESSAGES ───────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <svg class="h-4 w-4 shrink-0 text-emerald-400" viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm3.354-9.646a.5.5 0 0 0-.708-.708L7 8.293 5.354 6.646a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l4-4z"/>
            </svg>
            <p class="text-sm text-emerald-300">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3">
            <svg class="h-4 w-4 shrink-0 text-red-400" viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm-.354-9.354a.5.5 0 0 1 .708 0L8 6.293l-.354-.647a.5.5 0 1 1 .708-.708l.354.647.354-.647a.5.5 0 0 1 .708.708L9.207 8l.354.647a.5.5 0 0 1-.708.708L8 8.707l-.354.647a.5.5 0 0 1-.708-.708L7.293 8l-.647-.354a.5.5 0 0 1 .708-.708l.647.354z"/>
            </svg>
            <p class="text-sm text-red-300">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ── TWO-COLUMN LAYOUT ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ── EDIT PROFILE FORM ─────────────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
                <div class="border-b border-white/5 px-6 py-4">
                    <h2 class="text-sm font-semibold text-white">Personal information</h2>
                    <p class="mt-0.5 text-xs text-slate-400">Update your name and contact details</p>
                </div>
                <form method="POST" action="{{ route('shared.profile.update') }}" class="px-6 py-6 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label for="name" class="mb-1.5 block text-xs font-medium text-slate-300">Full name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            autocomplete="name"
                            class="block w-full rounded-xl border-0 bg-white/[0.04] px-4 py-2.5 text-sm text-white
                                   ring-1 ring-inset ring-white/[0.08] placeholder:text-slate-500
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 focus:outline-none
                                   transition-shadow"
                            placeholder="Your full name"
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email (read-only) --}}
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-slate-300">Email address</label>
                        <div class="flex items-center gap-2 rounded-xl bg-white/[0.02] px-4 py-2.5 ring-1 ring-inset ring-white/[0.05]">
                            <span class="flex-1 text-sm text-slate-400">{{ $user->email }}</span>
                            @if($user->email_verified_at)
                                <span class="flex items-center gap-1 text-[11px] font-medium text-emerald-400">
                                    <svg class="h-3 w-3" viewBox="0 0 12 12" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 11A5 5 0 1 0 6 1a5 5 0 0 0 0 10zm2.354-6.146a.5.5 0 0 0-.708-.708L5.5 6.293 4.354 5.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l2.5-2.5z"/>
                                    </svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">Email cannot be changed here. Contact support if needed.</p>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="mb-1.5 block text-xs font-medium text-slate-300">
                            Phone number
                            <span class="ml-1 text-slate-500 font-normal">(optional)</span>
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            autocomplete="tel"
                            class="block w-full rounded-xl border-0 bg-white/[0.04] px-4 py-2.5 text-sm text-white
                                   ring-1 ring-inset ring-white/[0.08] placeholder:text-slate-500
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 focus:outline-none
                                   transition-shadow"
                            placeholder="+254 7XX XXX XXX"
                        >
                        @error('phone')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end border-t border-white/5 pt-5">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold
                                   text-white shadow-sm shadow-sky-500/20 transition-all
                                   hover:bg-sky-400 hover:shadow-sky-400/30 hover:shadow-md
                                   focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-slate-800"
                        >
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── ACCOUNT SUMMARY ──────────────────────────────────────── --}}
        <div class="space-y-4">

            {{-- Account card --}}
            <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
                <div class="border-b border-white/5 px-5 py-4">
                    <h2 class="text-sm font-semibold text-white">Account</h2>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <div>
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">Role</p>
                        <p class="mt-1 text-sm font-medium {{ $roleColor[2] }}">{{ $role }}</p>
                    </div>
                    <div class="border-t border-white/5 pt-3">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">Member since</p>
                        <p class="mt-1 text-sm text-slate-300">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="border-t border-white/5 pt-3">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">Email status</p>
                        <div class="mt-1 flex items-center gap-1.5">
                            @if($user->email_verified_at)
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                <p class="text-sm text-emerald-400">Verified</p>
                            @else
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                <p class="text-sm text-amber-400">Unverified</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trust badge card --}}
            <div class="overflow-hidden rounded-2xl border border-emerald-500/20 bg-emerald-500/5 ring-1 ring-emerald-500/10 px-5 py-4">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15">
                        <svg class="h-4 w-4 text-emerald-400" viewBox="0 0 16 16" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 .5a.5.5 0 0 1 .447.276l1.5 3A.5.5 0 0 1 9.5 4h-3a.5.5 0 0 1-.447-.724l1.5-3A.5.5 0 0 1 8 .5zm5.248 3.44a.5.5 0 0 1 .004.709l-3.5 3.5a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L9.5 7.086l3.04-3.04a.5.5 0 0 1 .708-.105z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-emerald-300">Shoppa Verified Account</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-emerald-500">Your identity is on the Shoppa trust network.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── CHANGE PASSWORD ──────────────────────────────────────────────── --}}
    <div class="mt-6 overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="border-b border-white/5 px-6 py-4">
            <h2 class="text-sm font-semibold text-white">Security</h2>
            <p class="mt-0.5 text-xs text-slate-400">Update your password to keep your account secure</p>
        </div>
        <form method="POST" action="{{ route('shared.profile.password') }}" class="px-6 py-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                <div>
                    <label for="current_password" class="mb-1.5 block text-xs font-medium text-slate-300">Current password</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        class="block w-full rounded-xl border-0 bg-white/[0.04] px-4 py-2.5 text-sm text-white
                               ring-1 ring-inset ring-white/[0.08] placeholder:text-slate-500
                               focus:ring-2 focus:ring-inset focus:ring-sky-500 focus:outline-none"
                        placeholder="••••••••"
                    >
                    @error('current_password')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-xs font-medium text-slate-300">New password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        class="block w-full rounded-xl border-0 bg-white/[0.04] px-4 py-2.5 text-sm text-white
                               ring-1 ring-inset ring-white/[0.08] placeholder:text-slate-500
                               focus:ring-2 focus:ring-inset focus:ring-sky-500 focus:outline-none"
                        placeholder="Min. 8 characters"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-xs font-medium text-slate-300">Confirm new password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        class="block w-full rounded-xl border-0 bg-white/[0.04] px-4 py-2.5 text-sm text-white
                               ring-1 ring-inset ring-white/[0.08] placeholder:text-slate-500
                               focus:ring-2 focus:ring-inset focus:ring-sky-500 focus:outline-none"
                        placeholder="••••••••"
                    >
                </div>

            </div>

            <div class="mt-5 flex items-center justify-between border-t border-white/5 pt-5">
                <p class="text-xs text-slate-500">Use a minimum of 8 characters, including letters and numbers.</p>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-white/[0.06] px-5 py-2.5 text-sm font-semibold
                           text-slate-300 ring-1 ring-white/[0.08] transition-all
                           hover:bg-white/[0.10] hover:text-white
                           focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-slate-800"
                >
                    Update password
                </button>
            </div>
        </form>
    </div>

</x-dynamic-component>
