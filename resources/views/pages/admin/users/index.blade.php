<x-layouts.dashboard>
    <x-slot:title>Users</x-slot:title>

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-stone-900">Users</h2>
            <p class="mt-1 text-sm text-stone-500">
                {{ $users->total() }} total accounts
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}">
            <x-ui.button size="sm">
                <x-nav.icon name="user" class="h-4 w-4" />
                Add staff account
            </x-ui.button>
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.users.index') }}"
          class="mb-5 flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or email…"
                class="block w-full rounded-lg border-0 py-2 px-3 text-stone-900 ring-1 ring-inset ring-stone-300 text-sm focus:ring-2 focus:ring-emerald-600"
            />
        </div>
        <select
            name="role"
            class="rounded-lg border-0 py-2 pl-3 pr-8 text-stone-900 ring-1 ring-inset ring-stone-300 text-sm focus:ring-2 focus:ring-emerald-600"
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

    {{-- Table --}}
    <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-stone-200">
            <thead>
                <tr class="bg-stone-50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wide">Name</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wide">Email</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wide">Role</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wide">Joined</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($users as $user)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold uppercase">
                                    {{ substr($user->name, 0, 2) }}
                                </span>
                                <span class="text-sm font-medium text-stone-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-stone-600">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            @if($user->role)
                                <x-ui.badge :color="$user->isStaff() ? 'purple' : 'stone'">
                                    {{ $user->role->name }}
                                </x-ui.badge>
                            @else
                                <span class="text-xs text-stone-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm text-stone-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm text-stone-400">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="mt-5">
            {{ $users->links() }}
        </div>
    @endif

</x-layouts.dashboard>