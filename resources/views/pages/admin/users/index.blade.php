<x-layouts.dashboard>
    <x-slot:title>Users</x-slot:title>

    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-white">Users</h2>
            <p class="mt-1 text-sm text-slate-400">All platform users and staff accounts.</p>
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
                class="rounded-lg border-0 py-2 px-3 text-sm bg-slate-700 text-white ring-1 ring-inset ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-sky-500 w-full sm:w-64"
            />
            <select
                name="role"
                class="rounded-lg border-0 py-2 px-3 text-sm bg-slate-700 text-white ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-sky-500"
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
    <div class="rounded-2xl bg-slate-800 ring-1 ring-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/5">
                <thead class="bg-slate-900">
                    <tr>
                        @foreach(['Name', 'Email', 'Phone', 'Role', 'Joined', ''] as $h)
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-sky-600 text-xs font-semibold text-white uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </span>
                                    <span class="text-sm font-medium text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-400">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-sm text-slate-500">{{ $user->phone ?? '—' }}</td>
                            <td class="px-5 py-4">
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
                                <x-ui.badge :color="$roleColor">{{ $user->role?->name ?? 'No role' }}</x-ui.badge>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-xs text-sky-400 hover:text-sky-300 font-medium transition-colors">
                                        Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                              onsubmit="return confirm('Remove {{ $user->name }}? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-medium transition-colors">
                                                Remove
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-white/5">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</x-layouts.dashboard>
