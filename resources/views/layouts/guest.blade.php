<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('shoppa.name') }} &mdash; {{ config('shoppa.tagline') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-stone-50 font-sans antialiased">

    {{-- Flash messages --}}
    @include('partials.flash-message')

    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">

        {{-- Brand mark --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 group">
                <span class="text-2xl font-semibold tracking-tight text-stone-900 group-hover:text-emerald-700 transition-colors">
                    Shoppa
                </span>
                <x-trust::verified-pill size="sm" />
            </a>
            @isset($heading)
                <h1 class="mt-6 text-center text-2xl font-semibold text-stone-900">
                    {{ $heading }}
                </h1>
            @endisset
            @isset($subheading)
                <p class="mt-1 text-center text-sm text-stone-500">
                    {{ $subheading }}
                </p>
            @endisset
        </div>

        {{-- Card --}}
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-sm ring-1 ring-stone-950/5 rounded-xl">
                {{ $slot }}
            </div>
        </div>

    </div>

</body>
</html>