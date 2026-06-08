<x-layouts.dashboard>
    <x-slot:title>Users</x-slot:title>

    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-stone-900">Users</h2>
            <p class="mt-1 text-sm text-stone-500">All platform users and staff accounts.</p>
        </div>
        <a href="{{ route('admin.users.create') }}">
            <x-ui.button size="sm">
                <x-nav.icon name="user" class="h-4 w-4" />
                Add staff member
            </x-ui.button>
        </a>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap gap-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 w-full sm:w-auto">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search name or email..."
                class="rounded-lg border-0 py-2 px-3 text-sm text-stone-900 ring-1 ring-inset ring-stone-300 placeholder:text-stone-400 focus:ring-2 focus:ring-emerald-600 w-full sm:w-64"
            />
            <select
                name="role"
                class="rounded-lg border-0 py-2 px-3 text-sm text-stone-900 ring-1 ring-inset ring-stone-300 focus:ring-2 focus:ring-emerald-600"
            >
                <option value="">All roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            <x-ui.button type="submit" variant="secondary" size="sm">Filter</x-ui.button>
            @if(request()->hasAny(['search','role']))
                <a href="{{ route('admin.users.index') }}">
                    <x-ui.button variant="ghost" size="sm">Clear</x-ui.button>
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-100">
                <thead class="bg-stone-50">
                    <tr>
                        @foreach(['Name', 'Email', 'Phone', 'Role', 'Joined', ''] as $h)
                            <th class="px-5 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wide">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-semibold text-emerald-700 uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </span>
                                    <span class="text-sm font-medium text-stone-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-stone-500">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-sm text-stone-400">{{ $user->phone ?? '—' }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $roleColor = match($user->role?->name) {
                                        'Super Admin'     => 'red',
                                        'Admin'           => 'purple',
                                        'Verifier'        => 'emerald',
                                        'Vendor Manager'  => 'amber',
                                        'Customer Service'=> 'blue',
                                        'Content Manager' => 'blue',
                                        'Vendor'          => 'amber',
                                        default           => 'stone',
                                    };
                                @endphp
                                <x-ui.badge :color="$roleColor">{{ $user->role?->name ?? 'No role' }}</x-ui.badge>
                            </td>
                            <td class="px-5 py-4 text-sm text-stone-400">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                        Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                              onsubmit="return confirm('Remove {{ $user->name }}? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                                                Remove
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-stone-400">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-stone-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</x-layouts.dashboard>
