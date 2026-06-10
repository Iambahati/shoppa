<x-layouts.dashboard>
    <x-slot:title>Vendor Manager</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <p class="section-label mb-1.5">{{ now()->format('l, d F Y') }}</p>
            <h1 class="text-xl font-semibold text-stone-900 tracking-tight">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h1>
            <p class="mt-0.5 text-[13px] text-stone-500">Vendor Manager overview</p>
        </div>
        <a href="{{ route('admin.vendors.index') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-emerald-500">
            <x-nav-icon name="store" class="h-4 w-4" />
            Review applications
        </a>
    </div>

    {{-- ── KPI TILES ────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Pending applications"
            :value="(string) $stats['pending_applications']"
            icon="store"
            icon-color="amber"
            :glow-first="true"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Active vendors"
            :value="(string) $stats['active_vendors']"
            icon="users"
            icon-color="emerald"
            :sparkline="implode(',', $chartData)"
            style="animation-delay: 100ms"
        />
        <x-card-stat-card
            label="Suspended vendors"
            :value="(string) $stats['suspended_vendors']"
            icon="flag"
            icon-color="red"
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Approvals this week"
            :value="(string) $stats['approvals_this_week']"
            icon="package"
            icon-color="blue"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── VENDOR ECOSYSTEM HEALTH ──────────────────────────────────────── --}}
    <div class="mb-8 card px-6 py-5">
        <div class="mb-5 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-stone-900">Vendor ecosystem health</h3>
            <span class="text-xs text-stone-400">{{ array_sum($vendorBreakdown) }} total applications</span>
        </div>
        @php
            $breakMax  = max(array_values($vendorBreakdown)) ?: 1;
            $healthRows = [
                ['label' => 'Active vendors', 'key' => 'active',    'color' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
                ['label' => 'Pending review', 'key' => 'pending',   'color' => 'bg-amber-400',   'text' => 'text-amber-700'],
                ['label' => 'Suspended',      'key' => 'suspended', 'color' => 'bg-red-400',     'text' => 'text-red-700'],
                ['label' => 'Rejected',       'key' => 'rejected',  'color' => 'bg-stone-300',   'text' => 'text-stone-500'],
            ];
        @endphp
        <div class="space-y-4">
            @foreach($healthRows as $row)
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="{{ $row['text'] }} font-medium">{{ $row['label'] }}</span>
                        <span class="tabular-nums text-stone-600">{{ $vendorBreakdown[$row['key']] }}</span>
                    </div>
                    <div class="h-[3px] rounded-full bg-stone-100">
                        <div class="h-full rounded-full {{ $row['color'] }} transition-all duration-700"
                             style="width: {{ round(($vendorBreakdown[$row['key']] / $breakMax) * 100) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── PENDING APPLICATIONS ─────────────────────────────────────────── --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-stone-100 px-6 py-4">
            <h3 class="text-sm font-semibold text-stone-900">Pending applications</h3>
            <a href="{{ route('admin.vendors.index') }}" class="text-xs font-medium text-emerald-600 transition-colors hover:text-emerald-700">View all</a>
        </div>

        @if($pendingVendors->isEmpty())
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <svg class="h-16 w-16 text-stone-200" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <rect x="8" y="20" width="48" height="36" rx="6" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M20 20 V14 C20 10.686 22.686 8 26 8 H38 C41.314 8 44 10.686 44 14 V20" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M26 36 L30 40 L38 32" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="mt-4 text-sm font-semibold text-stone-600">No pending applications</p>
                <p class="mt-1 text-xs text-stone-400">New vendor applications will appear here for review.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-stone-100">
                @foreach($pendingVendors as $vendor)
                    @php $hasDocs = \Illuminate\Support\Facades\Schema::hasTable('media') ? $vendor->hasMedia('kyc_documents') : false; @endphp
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-stone-50">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-sm font-bold text-amber-700 ring-1 ring-amber-200">
                            {{ substr($vendor->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-stone-900">{{ $vendor->name }}</p>
                            <p class="text-xs text-stone-400">{{ $vendor->user?->name ?? 'Unknown' }} &bull; {{ $vendor->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <span class="h-2 w-2 rounded-full {{ $hasDocs ? 'bg-emerald-500' : 'bg-amber-400' }}"
                                  title="{{ $hasDocs ? 'Documents complete' : 'Missing documents' }}"></span>
                            <x-ui-badge color="amber" size="xs">Pending</x-ui-badge>
                        </div>
                        <a href="{{ route('admin.vendors.index') }}" class="shrink-0 text-xs font-medium text-emerald-600 transition-colors hover:text-emerald-700">Review →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
