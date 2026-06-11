<x-layouts.dashboard>
    <x-slot:title>Add staff member</x-slot:title>

    <div class="mx-auto max-w-xl">

        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-stone-500 hover:text-stone-700 transition-colors flex items-center gap-1">
                <x-nav.icon name="chevron-r" class="h-4 w-4 rotate-180" />
                Back to users
            </a>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-stone-900">Add staff member</h2>
            <p class="mt-1 text-sm text-stone-500">
                Staff accounts are created by admins. The user's email is marked verified immediately.
            </p>
        </div>

        <div class="card p-6">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <x-form.field name="name"  label="Full name"  type="text"     :required="true" autocomplete="off" />
                <x-form.field name="email" label="Email"      type="email"    :required="true" autocomplete="off" />
                <x-form.field name="phone" label="Phone"      type="tel"      hint="Optional but recommended." />
                <x-form.field name="password" label="Temporary password" type="password" :required="true"
                    hint="Minimum 8 characters with letters and numbers. Staff should change this on first login." />

                {{-- Role selector --}}
                <div class="space-y-1">
                    <label for="role" class="block text-sm font-medium text-stone-700">
                        Role <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="form-input @error('role') ring-red-400 @enderror"
                    >
                        <option value="" disabled selected>Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-stone-400">
                        Buyers register themselves at /register. This form is for internal staff only.
                    </p>
                </div>

                <div class="pt-2 flex items-center justify-between gap-4">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-stone-500 hover:text-stone-700 transition-colors">
                        Cancel
                    </a>
                    <x-ui.button type="submit">
                        Create account
                    </x-ui.button>
                </div>

            </form>
        </div>

    </div>

</x-layouts.dashboard>
