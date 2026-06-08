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

<body class="h-full bg-white font-sans antialiased">

    @include('partials.flash-message')

    <div class="min-h-full flex flex-col lg:flex-row">
        {{-- Left: Brand illustration & messaging --}}
        <div class="relative hidden lg:flex lg:w-1/2 xl:w-[55%] items-center justify-center overflow-hidden bg-gradient-to-br from-sky-50 via-white to-pink-50">
            {{-- Decorative soft shapes --}}
            <div class="absolute -top-[15%] -left-[10%] w-[70%] h-[70%] rounded-full bg-sky-100/50 blur-3xl" aria-hidden="true"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[60%] h-[60%] rounded-full bg-pink-100/50 blur-3xl" aria-hidden="true"></div>
            <div class="absolute top-[15%] right-[10%] w-40 h-40 rounded-full bg-sky-200/40 blur-2xl" aria-hidden="true"></div>
            <div class="absolute bottom-[25%] left-[8%] w-24 h-24 rounded-full bg-pink-200/40 blur-2xl" aria-hidden="true"></div>

            {{-- Content --}}
            <div class="relative z-10 max-w-md px-12 text-center">
                {{-- Friendly illustration --}}
                <div class="mb-8 flex justify-center">
                    <svg viewBox="0 0 240 200" class="w-56 h-auto" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        {{-- Background circle --}}
                        <circle cx="120" cy="100" r="90" fill="#e0f2fe" />
                        <circle cx="120" cy="100" r="65" fill="#bae6fd" opacity="0.5" />

                        {{-- Shopping bag body --}}
                        <rect x="85" y="95" width="70" height="55" rx="8" fill="#38bdf8" opacity="0.9" />
                        <rect x="85" y="95" width="70" height="55" rx="8" stroke="#0ea5e9" stroke-width="2" opacity="0.3" />

                        {{-- Bag handles --}}
                        <path d="M100 95V80a20 20 0 0 1 40 0v15" stroke="#0ea5e9" stroke-width="3" stroke-linecap="round" fill="none" />

                        {{-- Decorative blush dots --}}
                        <circle cx="55" cy="70" r="14" fill="#fbcfe8" />
                        <circle cx="190" cy="55" r="10" fill="#bae6fd" />
                        <circle cx="195" cy="145" r="12" fill="#fbcfe8" opacity="0.7" />
                        <circle cx="50" cy="150" r="8" fill="#bae6fd" opacity="0.6" />

                        {{-- Heart on bag --}}
                        <path d="M120 128c-6-6-16-6-22 0s-6 16 0 22l22 22 22-22c6-6 6-16 0-22s-16-6-22 0z" fill="#f472b6" opacity="0.85" />
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-slate-800 mb-3">Welcome to Shoppa</h2>
                <p class="text-lg text-slate-600 mb-2">Join thriving vendors & shoppers every day.</p>
                <p class="text-slate-500">Track orders, manage vendors, and find deals fast.</p>
            </div>
        </div>

        {{-- Right: Auth form --}}
        <div class="flex w-full lg:w-1/2 xl:w-[45%] flex-col justify-center items-center px-6 py-10 sm:px-12 lg:px-16 xl:px-20 bg-stone-50/60">
            <div class="w-full max-w-sm">
                {{-- Mobile brand strip --}}
                <div class="lg:hidden mb-8 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 group">
                        <span class="text-2xl font-bold tracking-tight text-slate-900 group-hover:text-sky-600 transition-colors">
                            Shoppa
                        </span>
                        <x-trust-verified-pill size="sm" />
                    </a>
                </div>

                {{-- Form card --}}
                <div class="bg-white rounded-2xl p-7 sm:p-8 shadow-sm ring-1 ring-stone-950/5">
                    @isset($heading)
                    <h1 class="text-2xl font-bold text-slate-900">{{ $heading }}</h1>
                    @endisset
                    @isset($subheading)
                    <p class="mt-1.5 text-sm text-slate-500 mb-6">{{ $subheading }}</p>
                    @endisset
                    @if(!isset($heading) && !isset($subheading))
                    <div class="mb-2"></div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

</body>

</html>