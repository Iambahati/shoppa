<div {{ $attributes->merge(['class' => $classes]) }} role="alert">
    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $iconPath }}" />
    </svg>
    <div>{{ $slot }}</div>
</div>