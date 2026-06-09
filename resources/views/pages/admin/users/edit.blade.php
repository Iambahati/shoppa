<x-layouts.dashboard>
    <x-slot:title>Edit {{ $user->name }}</x-slot:title>

    <div class="mx-auto max-w-xl">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-400 hover:text-slate-200 transition-colors flex items-center gap-1 w-fit">
                <x-nav-icon name="chevron-r" class="h-4 w-4 rotate-180" /> Back to users
            </a>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-white">Edit {{ $user->name }}</h2>
        </div>

        <div class="rounded-2xl bg-white ring-1 ring-slate-950/5 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
                @csrf @method('PUT')

                <x-form-field name="name" label="Full name" type="text" :required="true" />
                <x-form-field name="phone" label="Phone" type="tel" />

                <div class="space-y-1">
                    <label for="role" class="block text-sm font-medium text-slate-700">
                        Role <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select id="role" name="role" required
                        class="block w-full rounded-lg border-0 py-2 px-3 text-slate-900 ring-1 ring-inset ring-slate-300 text-sm focus:ring-2 focus:ring-inset focus:ring-sky-500">
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" @selected(old('role', $user->role?->name) === $role->name)>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2 flex items-center justify-between gap-4">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-400 hover:text-slate-200 transition-colors">Cancel</a>
                    <x-ui-button type="submit">Save changes</x-ui-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
