<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('shoppa.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=nunito:400,500,600,700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Nunito', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    @vite(['resources/js/app.js'])
</head>

<body class="h-full bg-slate-900 font-sans antialiased">

    @include('partials.flash-message')

    <div class="min-h-full flex flex-col lg:flex-row">

        {{-- Left: Dark brand panel --}}
        <div class="relative hidden lg:flex lg:w-[45%] xl:w-[40%] flex-col justify-between p-12 overflow-hidden bg-slate-900 border-r border-white/5">
            {{-- Glow blobs --}}
            <div class="pointer-events-none absolute -top-32 -left-32 h-80 w-80 rounded-full bg-sky-500/10 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-24 h-64 w-64 rounded-full bg-sky-500/8 blur-3xl" aria-hidden="true"></div>

            {{-- Logo --}}
            <div class="relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                    <span class="text-xl font-bold tracking-tight text-white group-hover:text-sky-300 transition-colors">Shoppa</span>
                    <span class="inline-flex items-center rounded-full bg-sky-500/20 px-2 py-0.5 text-xs font-semibold text-sky-400 ring-1 ring-sky-500/30">
                        Verified
                    </span>
                </a>
            </div>

            {{-- Centre copy --}}
            <div class="relative z-10 space-y-8">
                <div>
                    <h2 class="text-4xl font-bold text-white leading-tight">
                        Electronics you can<br>
                        <span class="text-sky-400">actually trust.</span>
                    </h2>
                    <p class="mt-4 max-w-xs text-base leading-relaxed text-slate-400">
                        Every device on Shoppa is physically inspected before it goes live. No fakes. No surprises.
                    </p>
                </div>

                <ul class="space-y-4">
                    @foreach([
                        'Physical inspection before every listing',
                        'Escrow payment — released only after you confirm',
                        'QR Trust Certificate, lookupable by IMEI',
                    ] as $point)
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-sky-500/20">
                            <svg class="h-3 w-3 text-sky-400" fill="none" viewBox="0 0 12 12" aria-hidden="true">
                                <path d="M2 6l3 3 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="text-sm text-slate-300">{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>

                {{-- Stat strip --}}
                <div class="grid grid-cols-3 gap-4 border-t border-white/5 pt-8">
                    @foreach([['700+', 'Devices verified'], ['98%', 'Buyer satisfaction'], ['0', 'Fakes shipped']] as [$n, $l])
                    <div>
                        <p class="text-2xl font-bold text-white tabular-nums">{{ $n }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $l }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="relative z-10">
                <p class="text-xs text-slate-600">Built for Kenya. Trusted across Africa.</p>
            </div>
        </div>

        {{-- Right: Form panel --}}
        <div class="flex flex-1 flex-col items-center justify-center bg-slate-50 px-6 py-10 sm:px-12">
            <div class="w-full max-w-sm">

                {{-- Mobile brand strip --}}
                <div class="mb-8 text-center lg:hidden">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 group">
                        <span class="text-2xl font-bold tracking-tight text-slate-900 group-hover:text-sky-600 transition-colors">
                            Shoppa
                        </span>
                        <x-trust-verified-pill size="sm" />
                    </a>
                </div>

                {{-- Form card --}}
                <div class="rounded-2xl bg-white px-7 py-8 shadow-sm ring-1 ring-slate-900/5 sm:px-8">
                    @isset($heading)
                    <h1 class="text-2xl font-bold text-slate-900">{{ $heading }}</h1>
                    @endisset
                    @isset($subheading)
                    <p class="mb-6 mt-1.5 text-sm text-slate-500">{{ $subheading }}</p>
                    @endisset
                    @if(!isset($heading) && !isset($subheading))
                    <div class="mb-2"></div>
                    @endif

                    {{ $slot }}
                </div>

                {{-- Back link for mobile --}}
                <p class="mt-6 text-center text-xs text-slate-400 lg:hidden">
                    <a href="{{ route('home') }}" class="hover:text-slate-600 transition-colors">← Back to home</a>
                </p>
            </div>
        </div>

    </div>

</body>

</html>
