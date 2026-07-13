<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HNRZ') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|inter:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-[#241a52] bg-[#f8f7fd]">
        <div class="min-h-screen bg-[#f8f7fd]">
            @include('layouts.navigation')
            
            <main class="lg:pl-72">
                @isset($header)
                    <header class="border-b border-[#ece7fb] bg-white">
                        <div class="app-page py-4 sm:py-5 font-display">{{ $header }}</div>
                    </header>
                @endisset
                
                <div class="animate-fade-in">
                    {{ $slot }}
                </div>
            </main>
            
            <x-toast-notifications />
            <x-confirm-modal />
        </div>
        @livewireScripts
    </body>
</html>
