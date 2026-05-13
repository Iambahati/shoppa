<div {{ $attributes->merge(['class' => 'inline-flex flex-col gap-1']) }}>

    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClasses }}">
        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
        </svg>
        {{ $label }}
    </span>

    @if($certId && $status === 'verified')
        <span class="text-xs text-stone-400 font-mono pl-1">
            # {{ Str::upper(substr($certId, 0, 8)) }}
            @if($issuedAt)
                &middot; {{ \Carbon\Carbon::parse($issuedAt)->format('d M Y') }}
            @endif
        </span>
    @endif

</div>