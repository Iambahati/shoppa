<div class="space-y-1">
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
        {{ $label }}
        @if($required)<span class="text-red-500 ml-0.5" aria-hidden="true">*</span>@endif
    </label>

    @if($hint)
    <p class="text-xs text-slate-400">{{ $hint }}</p>
    @endif

    @if($type === 'textarea')
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="4"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-0 py-2 px-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-inset focus:ring-sky-500 ' . ($errors->has($name) ? 'ring-red-400' : '')]) }}>{{ old($name) }}</textarea>
    @else
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $type !== 'password' ? old($name) : '' }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-0 py-2 px-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-inset focus:ring-sky-500 ' . ($errors->has($name) ? 'ring-red-400' : '')]) }} />
    @endif

    @error($name)
    <p class="flex items-center gap-1 text-xs text-red-500" role="alert">
        <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
        </svg>
        {{ $message }}
    </p>
    @enderror
</div>
