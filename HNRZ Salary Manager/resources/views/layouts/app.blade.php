<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div class="min-h-screen bg-slate-50">
            @include('layouts.navigation')
            <main class="lg:pl-72">
                @isset($header)
                    <header class="border-b border-slate-200 bg-white">
                        <div class="app-page py-4 sm:py-5">{{ $header }}</div>
                    </header>
                @endisset
                {{ $slot }}
            </main>
            <x-toast-notifications />
            <x-confirm-modal />
        </div>
        @livewireScripts
    </body>
</html>
