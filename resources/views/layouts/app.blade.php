<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#ffffff">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <title>@yield('title', config('app.name', 'Tır Takip'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen">
        <div class="min-h-screen bg-gray-50 main-safe">
            <header class="bg-white border-b border-gray-200/80 shadow-sm sticky top-0 z-30">
                @include('layouts.navigation')
            </header>

            @if(isset($header) && trim($header))
            <div class="bg-white/80 backdrop-blur border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                    <div class="flex flex-wrap items-center gap-3">
                        {{ $header }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
