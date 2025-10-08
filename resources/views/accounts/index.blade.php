<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Plan Comptable
            </h2>
            <a href="{{ route('accounts.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Nouveau Compte
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @php
            $classNames = [
                '1' => 'Classe 1 - Comptes de capitaux',
                '2' => 'Classe 2 - Comptes d\'immobilisations',
                '3' => 'Classe 3 - Comptes de stocks',
                '4' => 'Classe 4 - Comptes de tiers',
                '5' => 'Classe 5 - Comptes financiers',
                '6' => 'Classe 6 - Comptes de charges',
                '7' => 'Classe 7 - Comptes de produits',
                '8' => 'Classe 8 - Comptes spéciaux',
            ];
            @endphp

            @forelse($accountsByClass as $class => $classAccounts)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-6">
                <!-- En-tête de classe -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">
                        {{ $classNames[$class] ?? 'Classe ' . $class }}
                    </h3>
                    <p class="text-sm text-blue-100">{{ $classAccounts->count() }} compte(s)</p>
                </div>

                <!-- Tableau des comptes de la classe -->
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solde</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($classAccounts as $account)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $account->code }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $account->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($account->type === 'asset') bg-blue-100 text-blue-800
                                        @elseif($account->type === 'liability') bg-red-100 text-red-800
                                        @elseif($account->type === 'equity') bg-purple-100 text-purple-800
                                        @elseif($account->type === 'revenue') bg-green-100 text-green-800
                                        @else bg-orange-100 text-orange-800
                                        @endif">
                                        @if($account->type === 'asset') Actif
                                        @elseif($account->type === 'liability') Passif
                                        @elseif($account->type === 'equity') Capitaux propres
                                        @elseif($account->type === 'revenue') Produit
                                        @elseif($account->type === 'expense') Charge
                                        @else {{ $account->type }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $account->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ format_currency($account->balance) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $account->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $account->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('accounts.edit', $account) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-center text-gray-500">
                    Aucun compte trouvé
                </div>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

