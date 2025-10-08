<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Balance des Comptes
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Balance Générale - Tous les comptes</h3>
                        <p class="text-sm text-gray-600 mt-1">État des soldes de tous les comptes comptables</p>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du compte</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Débit</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Crédit</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Solde</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($accountsByType as $type => $typeAccounts)
                            <!-- En-tête de type -->
                            <tr class="bg-gray-100">
                                <td colspan="6" class="px-6 py-2">
                                    <span class="font-semibold text-gray-900 uppercase text-sm">
                                        @if($type === 'asset') 📊 Actif
                                        @elseif($type === 'liability') 📉 Passif
                                        @elseif($type === 'equity') 💼 Capitaux Propres
                                        @elseif($type === 'revenue') 💰 Produits
                                        @elseif($type === 'expense') 💸 Charges
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            
                            @foreach($typeAccounts as $account)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $account->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($account->type === 'asset') bg-blue-100 text-blue-800
                                        @elseif($account->type === 'liability') bg-red-100 text-red-800
                                        @elseif($account->type === 'equity') bg-purple-100 text-purple-800
                                        @elseif($account->type === 'revenue') bg-green-100 text-green-800
                                        @else bg-orange-100 text-orange-800
                                        @endif">
                                        {{ ucfirst($account->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm {{ $account->balance > 0 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                                    {{ $account->balance > 0 ? format_currency($account->balance) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm {{ $account->balance < 0 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                                    {{ $account->balance < 0 ? format_currency(abs($account->balance)) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $account->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ format_currency(abs($account->balance)) }}
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Sous-total par type -->
                            <tr class="bg-gray-50 font-semibold">
                                <td colspan="3" class="px-6 py-3 text-sm text-gray-900">
                                    Sous-total {{ ucfirst($type) }}
                                </td>
                                <td class="px-6 py-3 text-right text-sm text-gray-900">
                                    {{ format_currency($typeAccounts->filter(fn($a) => $a->balance > 0)->sum('balance')) }}
                                </td>
                                <td class="px-6 py-3 text-right text-sm text-gray-900">
                                    {{ format_currency($typeAccounts->filter(fn($a) => $a->balance < 0)->sum(fn($a) => abs($a->balance))) }}
                                </td>
                                <td class="px-6 py-3 text-right text-sm text-gray-900">
                                    {{ format_currency(abs($typeAccounts->sum('balance'))) }}
                                </td>
                            </tr>
                            @endforeach

                            <!-- TOTAL GÉNÉRAL -->
                            <tr class="bg-blue-50 font-bold border-t-2 border-blue-300">
                                <td colspan="3" class="px-6 py-4 text-base text-gray-900">TOTAL GÉNÉRAL</td>
                                <td class="px-6 py-4 text-right text-base text-blue-600">{{ format_currency($totalDebit) }}</td>
                                <td class="px-6 py-4 text-right text-base text-blue-600">{{ format_currency($totalCredit) }}</td>
                                <td class="px-6 py-4 text-right text-base text-blue-600">
                                    {{ format_currency($totalDebit + $totalCredit) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Équilibre -->
                    <div class="mt-6 p-4 rounded-lg {{ abs($totalDebit - $totalCredit) < 0.01 ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                        <div class="flex items-center justify-center">
                            @if(abs($totalDebit - $totalCredit) < 0.01)
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-green-800 font-semibold">Balance équilibrée (Débit = Crédit)</span>
                            @else
                            <svg class="w-6 h-6 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-yellow-800 font-semibold">Attention : Différence de {{ format_currency(abs($totalDebit - $totalCredit)) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

