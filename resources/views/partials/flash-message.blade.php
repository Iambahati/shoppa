@if(session()->hasAny(['success', 'error', 'warning', 'info']))
    @php
        $type    = collect(['success','error','warning','info'])->first(fn($t) => session()->has($t));
        $message = session($type);
    @endphp

    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 z-50 max-w-sm w-full"
        role="status"
        aria-live="polite"
    >
        <x-ui.alert :type="$type">
            <div class="flex items-start justify-between gap-3">
                <span>{{ $message }}</span>
                <button
                    type="button"
                    @click="show = false"
                    class="shrink-0 text-current opacity-60 hover:opacity-100 transition-opacity"
                    aria-label="Dismiss"
                >
                    <x-nav.icon name="x" class="h-4 w-4" />
                </button>
            </div>
        </x-ui.alert>
    </div>
@endif