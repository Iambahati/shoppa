<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('shoppa.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">

    {{-- Mobile sidebar backdrop --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-stone-950/40 lg:hidden"
    ></div>

    {{-- Sidebar --}}
    <x-nav.sidebar />

    {{-- Main column --}}
    <div class="lg:pl-64 flex flex-col min-h-screen">

        {{-- Top bar --}}
        <x-nav.topbar>
            <x-slot:title>{{ $title ?? '' }}</x-slot:title>
        </x-nav.topbar>

        {{-- Flash --}}
        @include('partials.flash-message')

        {{-- Page content --}}
        <main class="flex-1 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

    </div>

</body>
</html>