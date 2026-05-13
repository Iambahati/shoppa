<x-layouts.dashboard>
    <x-slot:title>Add staff account</x-slot:title>

    <div class="max-w-2xl mx-auto">

        <a href="{{ route('admin.users.index') }}"
           class="mb-6 inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-900 transition-colors">
            <x-nav.icon name="chevron-r" class="h-4 w-4 rotate-180" />
            Back to users
        </a>

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-stone-900">Add staff account</h2>
            <p class="mt-1 text-sm text-stone-500">
                Staff accounts are for internal team members. Buyers self-register.
            </p>
        </div>

        <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <x-form.field
                    name="name"
                    label="Full name"
                    placeholder="e.g. Amina Osei"
                    :required="true"
                />

                <x-form.field
                    name="email"
                    label="Email address"
                    type="email"
                    placeholder="amina@shoppa.co.ke"
                    :required="true"
                />

                <x-form.field
                    name="phone"
                    label="Phone number"
                    type="tel"
                    placeholder="+254 7XX XXX XXX"
                />

                <div class="space-y-1">
                    <label for="role" class="block text-sm font-medium text-stone-700">
                        Role <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="block w-full rounded-lg border-0 py-2 pl-3 pr-8 text-stone-900 ring-1 ring-inset ring-stone-300 text-sm focus:ring-2 focus:ring-inset focus:ring-emerald-600 @error('role') ring-red-400 @enderror"
                    >
                        <option value="" disabled selected>Select a role…</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-stone-700">
                        Temporary password <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="block w-full rounded-lg border-0 py-2 px-3 text-stone-900 ring-1 ring-inset ring-stone-300 text-sm focus:ring-2 focus:ring-inset focus:ring-emerald-600 @error('password') ring-red-400 @enderror"
                    />
                    @error('password')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-sm font-medium text-stone-700">
                        Confirm password <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="block w-full rounded-lg border-0 py-2 px-3 text-stone-900 ring-1 ring-inset ring-stone-300 text-sm focus:ring-2 focus:ring-inset focus:ring-emerald-600"
                    />
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.users.index') }}">
                        <x-ui.button variant="ghost" type="button">Cancel</x-ui.button>
                    </a>
                    <x-ui.button type="submit">
                        Create account
                    </x-ui.button>
                </div>

            </form>
        </div>

    </div>

</x-layouts.dashboard>