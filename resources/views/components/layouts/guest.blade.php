<!DOCTYPE html>
{{-- DS guest shell: stone-50 canvas, centered max-w-md card, brand lock-up above card --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('shoppa.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    {{-- Inter — DS primary typeface --}}
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-stone-50 font-sans antialiased">

    @include('partials.flash-message')

    <div class="min-h-full flex flex-col items-center justify-center px-6 py-12 sm:py-16">

        {{-- Brand lock-up: wordmark + verified pill --}}
        <div class="mb-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 no-underline">
                <span class="text-2xl font-semibold tracking-tight text-stone-900">Shoppa</span>
                <x-trust-verified-pill size="sm" />
            </a>
            @isset($heading)
            <h1 class="mt-6 text-2xl font-semibold text-stone-900 tracking-tight">{{ $heading }}</h1>
            @endisset
            @isset($subheading)
            <p class="mt-1.5 text-sm text-stone-500">{{ $subheading }}</p>
            @endisset
        </div>

        {{-- DS card: white, rounded-xl, shadow-sm, ring-1 ring-stone-950/5, py-8 px-6 --}}
        <div class="w-full max-w-md rounded-xl bg-white py-8 px-6 shadow-sm ring-1 ring-stone-950/5">
            {{ $slot }}
        </div>

    </div>

</body>

</html>
