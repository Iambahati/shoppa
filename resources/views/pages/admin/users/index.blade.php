<x-layouts.dashboard>
    <x-slot:title>Users</x-slot:title>

    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-stone-900">Users</h2>
            <p class="mt-1 text-sm text-stone-500">
                {{ $users->total() }} {{ Str::plural('user', $users->total()) }} on the platform
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}">
            <x-ui.button size="sm">
                <x-nav.icon name="user" class="h-4 w-4" />
                Add staff member
            </x-ui.button>
        </a>
    </div>

    {{-- Search & filter bar --}}
    <div class="mb-4 card p-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-2">

            {{-- Search input --}}
            <div class="relative flex-1 min-w-48">
                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="h-3.5 w-3.5 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or email…"
                    class="block w-full rounded-lg border-0 py-2 pl-9 pr-3 text-sm text-stone-900 ring-1 ring-inset ring-stone-300 placeholder:text-stone-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600"
                />
            </div>

            {{-- Role filter --}}
            <select
                name="role"
                class="rounded-lg border-0 py-2 px-3 text-sm text-stone-900 ring-1 ring-inset ring-stone-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 bg-white"
            >
                <option value="">All roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            <x-ui.button type="submit" variant="secondary" size="sm">Filter</x-ui.button>

            @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('admin.users.index') }}"
                   class="text-xs font-medium text-stone-400 hover:text-stone-600 transition-colors">
                    Clear filters
                </a>
            @endif

            {{-- Active filter pills --}}
            @if(request('search'))
                <span class="inline-flex items-center gap-1.5 rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-700">
                    "{{ request('search') }}"
                    <a href="{{ route('admin.users.index', array_filter(['role' => request('role')])) }}" class="text-stone-400 hover:text-stone-700">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                </span>
            @endif
            @if(request('role'))
                <span class="inline-flex items-center gap-1.5 rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-700">
                    {{ request('role') }}
                    <a href="{{ route('admin.users.index', array_filter(['search' => request('search')])) }}" class="text-stone-400 hover:text-stone-700">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                </span>
            @endif

        </form>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-100">
                <thead class="bg-stone-50">
                    <tr>
                        @foreach(['Name', 'Email', 'Phone', 'Role', 'Joined', ''] as $h)
                            <th class="table-th">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="table-td">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xs font-semibold text-white uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </span>
                                    <span class="text-sm font-medium text-stone-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="table-td text-stone-500">{{ $user->email }}</td>
                            <td class="table-td text-stone-400">{{ $user->phone ?? '—' }}</td>
                            <td class="table-td">
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
                            <td class="table-td text-stone-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="table-td text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                        Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                              onsubmit="return confirm('Remove {{ $user->name }}? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium transition-colors">
                                                Remove
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <svg class="mx-auto h-10 w-10 text-stone-200" fill="none" viewBox="0 0 40 40" aria-hidden="true">
                                    <circle cx="20" cy="20" r="18" stroke="currentColor" stroke-width="1.5" stroke-dasharray="4 3"/>
                                    <circle cx="20" cy="15" r="5" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M8 34c0-6.627 5.373-12 12-12s12 5.373 12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                <p class="mt-3 text-sm font-medium text-stone-500">No users match your filters</p>
                                @if(request()->hasAny(['search', 'role']))
                                    <a href="{{ route('admin.users.index') }}" class="mt-2 inline-block text-xs text-emerald-600 hover:text-emerald-700">
                                        Clear filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="border-t border-stone-100 px-5 py-3.5">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</x-layouts.dashboard>
