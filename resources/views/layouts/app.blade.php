<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Compta') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="fixed left-0 top-0 h-screen w-64 bg-white border-r border-gray-200 flex flex-col z-30">
                <!-- Logo -->
                <div class="h-16 flex items-center justify-center border-b border-gray-200 flex-shrink-0">
                    <h1 class="text-2xl font-bold text-blue-600">💼 Compta</h1>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">📊</span>
                        Tableau de bord
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tiers</p>
                    </div>

                    <a href="{{ route('customers.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('customers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">👥</span>
                        Clients
                    </a>

                    <a href="{{ route('suppliers.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('suppliers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">🏭</span>
                        Fournisseurs
                    </a>

                    <a href="{{ route('employees.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('employees.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">👤</span>
                        Employés
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Facturation</p>
                    </div>

                    <a href="{{ route('invoices.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('invoices.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">📄</span>
                        Factures
                    </a>

                    <a href="{{ route('payments.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('payments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">💰</span>
                        Paiements
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Ressources Humaines</p>
                    </div>

                    <a href="{{ route('payrolls.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('payrolls.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">💵</span>
                        Fiches de paie
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Comptabilité</p>
                    </div>

                    <a href="{{ route('accounts.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('accounts.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">📑</span>
                        Plan comptable
                    </a>

            <a href="{{ route('journal-entries.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('journal-entries.index') || request()->routeIs('journal-entries.create') || request()->routeIs('journal-entries.show') || request()->routeIs('journal-entries.edit') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <span class="mr-3">📝</span>
                Écritures
            </a>

            <a href="{{ route('journal-entries.journal') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('journal-entries.journal') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <span class="mr-3">📖</span>
                Journal
            </a>

                    <a href="{{ route('cards.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('cards.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">🎴</span>
                        Cards
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Rapports</p>
                    </div>

                    <a href="{{ route('reports.trial-balance') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('reports.trial-balance') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">⚖️</span>
                        Balance
                    </a>

                    <a href="{{ route('reports.balance-sheet') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('reports.balance-sheet') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">📈</span>
                        Bilan
                    </a>

                    <a href="{{ route('reports.financial-statement') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('reports.financial-statement') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">📊</span>
                        Compte de résultat
                    </a>

                    <a href="{{ route('reports.cash-flow') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('reports.cash-flow') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="mr-3">💧</span>
                        Flux de trésorerie
                    </a>
                </nav>

                <!-- User Menu -->
                <div class="border-t border-gray-200 p-4 flex-shrink-0">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-gray-600" title="Déconnexion">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col ml-64">
                <!-- Top Bar -->
                <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-20">
            @isset($header)
                        {{ $header }}
                    @else
                        <h2 class="text-xl font-semibold text-gray-800">Tableau de bord</h2>
                    @endisset
                </header>

            <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
            </div>
        </div>
    </body>
</html>
