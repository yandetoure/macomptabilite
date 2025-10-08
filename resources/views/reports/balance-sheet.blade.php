<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bilan Comptable
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Bilan Comptable</h3>
                        <p class="text-sm text-gray-600 mt-1">État de la situation financière : Actif, Passif et Capitaux propres</p>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du compte</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- ACTIF -->
                            <tr class="bg-blue-50">
                                <td colspan="4" class="px-6 py-3">
                                    <span class="font-bold text-blue-900 uppercase text-sm">📊 ACTIF</span>
                                </td>
                            </tr>
                            
                            @foreach($assets as $account)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $account->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        Actif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-blue-600">
                                    {{ format_currency($account->balance) }}
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Sous-total Actif -->
                            <tr class="bg-blue-100 font-bold">
                                <td colspan="3" class="px-6 py-3 text-sm text-blue-900">TOTAL ACTIF</td>
                                <td class="px-6 py-3 text-right text-base text-blue-900">{{ format_currency($totalAssets) }}</td>
                            </tr>

                            <!-- PASSIF -->
                            <tr class="bg-red-50">
                                <td colspan="4" class="px-6 py-3">
                                    <span class="font-bold text-red-900 uppercase text-sm">📉 PASSIF</span>
                                </td>
                            </tr>
                            
                            @foreach($liabilities as $account)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $account->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        Passif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-red-600">
                                    {{ format_currency($account->balance) }}
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Sous-total Passif -->
                            <tr class="bg-red-100 font-semibold">
                                <td colspan="3" class="px-6 py-3 text-sm text-red-900">Sous-total Passif</td>
                                <td class="px-6 py-3 text-right text-sm text-red-900">{{ format_currency($totalLiabilities) }}</td>
                            </tr>

                            <!-- CAPITAUX PROPRES -->
                            <tr class="bg-purple-50">
                                <td colspan="4" class="px-6 py-3">
                                    <span class="font-bold text-purple-900 uppercase text-sm">💼 CAPITAUX PROPRES</span>
                                </td>
                            </tr>
                            
                            @foreach($equity as $account)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $account->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        Capitaux propres
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-purple-600">
                                    {{ format_currency($account->balance) }}
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Sous-total Capitaux propres -->
                            <tr class="bg-purple-100 font-semibold">
                                <td colspan="3" class="px-6 py-3 text-sm text-purple-900">Sous-total Capitaux propres</td>
                                <td class="px-6 py-3 text-right text-sm text-purple-900">{{ format_currency($totalEquity) }}</td>
                            </tr>

                            <!-- TOTAL GÉNÉRAL PASSIF + CAPITAUX -->
                            <tr class="bg-gray-200 font-bold border-t-2 border-gray-400">
                                <td colspan="3" class="px-6 py-4 text-base text-gray-900">TOTAL PASSIF + CAPITAUX PROPRES</td>
                                <td class="px-6 py-4 text-right text-lg text-gray-900">{{ format_currency($totalLiabilities + $totalEquity) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Équilibre du bilan -->
                    <div class="mt-6 p-4 rounded-lg {{ abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01 ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-center justify-center">
                            @if(abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01)
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-green-800 font-semibold">✓ Bilan équilibré - Actif ({{ format_currency($totalAssets) }}) = Passif + Capitaux ({{ format_currency($totalLiabilities + $totalEquity) }})</span>
                            @else
                            <svg class="w-6 h-6 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-red-800 font-semibold">⚠️ Bilan non équilibré - Différence : {{ format_currency(abs($totalAssets - ($totalLiabilities + $totalEquity))) }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div class="mt-6 grid grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="text-xs text-blue-600 uppercase font-semibold">Total Actif</div>
                            <div class="text-xl font-bold text-blue-900 mt-1">{{ format_currency($totalAssets) }}</div>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="text-xs text-red-600 uppercase font-semibold">Total Passif</div>
                            <div class="text-xl font-bold text-red-900 mt-1">{{ format_currency($totalLiabilities) }}</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                            <div class="text-xs text-purple-600 uppercase font-semibold">Capitaux Propres</div>
                            <div class="text-xl font-bold text-purple-900 mt-1">{{ format_currency($totalEquity) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

