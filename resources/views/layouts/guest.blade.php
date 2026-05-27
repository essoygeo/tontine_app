<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TontineApp') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased bg-black">
        <div class="min-h-screen flex flex-col items-center justify-center p-4 py-12">
            <!-- Background Decorative Elements -->
            <div class="fixed top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-amazon-orange/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-amazon-dark-blue/20 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full max-w-md">
                <div class="flex flex-col items-center text-center mb-6">
                    <a href="/" wire:navigate class="block transition transform hover:scale-105 duration-300">
                       <img src="{{ asset('kotise.png') }}" class="w-32 h-auto md:w-40 object-contain drop-shadow-[0_0_20px_rgba(243,168,71,0.4)]" alt="logo" fetchpriority="high" loading="eager">
                    </a>
                    <p class="text-amazon-orange font-bold tracking-[0.2em] uppercase text-[10px] mt-4 opacity-90">Gestion de tontines simplifiée & sécurisée</p>
                </div>

                <div class="bg-amazon-dark-blue/40 backdrop-blur-3xl shadow-2xl rounded-[2rem] p-6 md:p-8 border border-white/5">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
