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
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans antialiased bg-[#0D0D0D] text-white" style="background-image: radial-gradient(circle at 10% 10%, rgba(245, 166, 35, 0.05), transparent 40%);">
    
    <nav class="fixed top-0 z-50 w-full bg-[#121212] border-b border-[#262626]">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-400 rounded-lg sm:hidden hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amazon-orange">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex ml-2 md:mr-24">
                        <img src="{{ asset('kotise.png') }}" class="h-10 w-auto mr-3 drop-shadow-[0_0_10px_rgba(243,168,71,0.6)]" alt="Kotise Logo" fetchpriority="high" loading="eager" />
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ml-3">
                        <div>
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-amazon-orange/50" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <div class="w-10 h-10 rounded-full bg-amazon-orange flex items-center justify-center text-[#131921] font-bold border-2 border-white/20">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-[#0e1319] divide-y divide-white/10 rounded-xl shadow-2xl border border-white/5" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm font-bold text-white" role="none">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-xs font-medium text-gray-400 truncate" role="none">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-white/5 hover:text-white" role="menuitem">Profil</a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-white/5 hover:text-white" role="menuitem">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-[#0e1319] border-r border-white/5 sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-[#0e1319]">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-4 rounded-2xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-amazon-orange/10 border border-amazon-orange/30 text-amazon-orange' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="ml-3 font-semibold">Tableau de bord</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('groups.index') }}" class="flex items-center p-4 rounded-2xl transition-all duration-200 {{ request()->routeIs('groups.*') ? 'bg-amazon-orange/10 border border-amazon-orange/30 text-amazon-orange' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="ml-3 font-semibold">Mes Tontines</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('contributions.index') }}" wire:navigate class="flex items-center p-4 rounded-2xl transition-all duration-200 {{ request()->routeIs('contributions.*') ? 'bg-amazon-orange/10 border border-amazon-orange/30 text-amazon-orange' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h3m-3 0V8m-3 9H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2h-3"></path></svg>
                        <span class="ml-3 font-semibold">Transactions</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64 bg-[#181818] min-h-screen">
        <div class="p-4 mt-14 max-w-7xl mx-auto">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="flex p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="sr-only">Success</span>
                    <div>
                        <span class="font-medium">Succès !</span> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="flex p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Error</span>
                    <div>
                        <span class="font-medium">Erreur !</span> {{ session('error') }}
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="flex p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span class="font-medium">Information :</span> {{ session('info') }}
                    </div>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>

    <script>
        window.addEventListener('close-modal', event => {
            const modalId = event.detail[0] || event.detail;
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                // Flowbite way to hide modal
                const modal = new Modal(modalElement);
                modal.hide();
                
                // Backup for flowbite data attributes
                document.querySelector(`[data-modal-hide="${modalId}"]`)?.click();
            }
        });
    </script>
</body>
</html>
