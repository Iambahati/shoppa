<x-layouts.dashboard>
    <x-slot:title>Content Manager</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, d F Y') }} &mdash; Content Manager overview</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600 transition-colors shadow-sm">
            <x-nav-icon name="package" class="h-4 w-4" />
            Add product
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Total products"  :value="(string) $stats['total_products']"  icon="package" icon-color="blue" />
        <x-card-stat-card label="Pending review"  :value="(string) $stats['pending_review']"  icon="shield"  icon-color="amber" />
        <x-card-stat-card label="Published today" :value="(string) $stats['published_today']" icon="layers"  icon-color="emerald" />
        <x-card-stat-card label="Categories"      :value="(string) $stats['categories']"      icon="box"     icon-color="purple" />
    </div>

    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Recently submitted products</h3>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium transition-colors">View all</a>
        </div>

        @if($recentProducts->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="package" class="mx-auto h-8 w-8 text-slate-300" />
                <p class="mt-3 text-sm text-slate-500">No products submitted yet.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($recentProducts as $product)
                    <li class="px-6 py-3.5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-slate-400">Submitted {{ $product->created_at->diffForHumans() }}</p>
                        </div>
                        <x-trust-cert-badge :status="$product->verification_status ?? 'pending'" />
                        <a href="{{ route('admin.products.show', $product) }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium">Review →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
