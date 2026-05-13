{{--
  dashboard.blade.php
  Thin wrapper — identical to app.blade.php today.
  Kept separate so staff layouts can diverge later
  (e.g. wider max-width, different sidebar palette).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('shoppa.name') }} Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">

    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-stone-950/40 lg:hidden"
    ></div>

    <x-nav.sidebar :staff="true" />

    <div class="lg:pl-64 flex flex-col min-h-screen">

        <x-nav.topbar :staff="true">
            <x-slot:title>{{ $title ?? 'Dashboard' }}</x-slot:title>
        </x-nav.topbar>

        @include('partials.flash-message')

        <main class="flex-1 py-8">
            <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

    </div>

</body>
</html>