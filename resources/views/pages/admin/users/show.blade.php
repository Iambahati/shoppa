<x-layouts.dashboard>
    <x-slot:title>{{ $user->name }}</x-slot:title>

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-stone-500 hover:text-stone-700 transition-colors flex items-center gap-1 w-fit">
            <x-nav-icon name="chevron-r" class="h-4 w-4 rotate-180" /> Back to users
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: identity --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="card p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xl font-semibold text-white uppercase">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-stone-900">{{ $user->name }}</h2>
                            <p class="text-sm text-stone-500">{{ $user->email }}</p>
                            @if($user->phone)
                            <p class="text-sm text-stone-400">{{ $user->phone }}</p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('admin.users.edit', $user) }}">
                        <x-ui-button variant="secondary" size="sm">Edit</x-ui-button>
                    </a>
                </div>
            </div>

            {{-- Activity log placeholder --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-4 border-b border-stone-100">
                    <h3 class="text-sm font-semibold text-stone-900">Recent activity</h3>
                </div>
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-stone-400">Activity log wired in Sprint 2.</p>
                </div>
            </div>

        </div>

        {{-- Right: meta --}}
        <div class="space-y-4">

            <div class="card p-5">
                <h4 class="text-sm font-semibold text-stone-900 mb-4">Account details</h4>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-stone-400 uppercase tracking-wide">Role</dt>
                        @php
                        $roleColor = match($user->role?->name) {
                            'Super Admin'      => 'red',
                            'Admin'            => 'purple',
                            'Verifier'         => 'emerald',
                            'Vendor Manager'   => 'amber',
                            'Customer Service' => 'blue',
                            'Content Manager'  => 'blue',
                            'Vendor'           => 'amber',
                            default            => 'stone',
                        };
                        @endphp
                        <dd class="mt-1">
                            <x-ui-badge :color="$roleColor">{{ $user->role?->name ?? 'No role' }}</x-ui-badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-stone-400 uppercase tracking-wide">Email verified</dt>
                        <dd class="mt-1 text-sm {{ $user->email_verified_at ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not verified' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-stone-400 uppercase tracking-wide">Member since</dt>
                        <dd class="mt-1 text-sm text-stone-600">{{ $user->created_at->format('d M Y') }}</dd>
                    </div>
                    @if($user->deleted_at)
                    <div>
                        <dt class="text-xs text-stone-400 uppercase tracking-wide">Deleted</dt>
                        <dd class="mt-1 text-sm text-red-600">{{ $user->deleted_at->format('d M Y') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            @if(! $user->trashed() && $user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                onsubmit="return confirm('Remove {{ $user->name }}? This cannot be undone.')">
                @csrf @method('DELETE')
                <x-ui-button type="submit" variant="danger" class="w-full justify-center">
                    Remove account
                </x-ui-button>
            </form>
            @endif

        </div>

    </div>
</x-layouts.dashboard>
