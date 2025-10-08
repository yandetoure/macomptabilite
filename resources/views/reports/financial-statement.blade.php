<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            État Financier (Compte de Résultat)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Compte de Résultat</h3>
                        <p class="text-sm text-gray-600 mt-1">État des produits et charges - Résultat de l'exercice</p>
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
                            <!-- PRODUITS -->
                            <tr class="bg-green-50">
                                <td colspan="4" class="px-6 py-3">
                                    <span class="font-bold text-green-900 uppercase text-sm">💰 PRODUITS (REVENUS)</span>
                                </td>
                            </tr>
                            
                            @foreach($revenues as $account)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $account->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        Produit
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-green-600">
                                    {{ format_currency($account->balance) }}
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Total Produits -->
                            <tr class="bg-green-100 font-bold">
                                <td colspan="3" class="px-6 py-3 text-sm text-green-900">TOTAL PRODUITS</td>
                                <td class="px-6 py-3 text-right text-base text-green-900">{{ format_currency($totalRevenues) }}</td>
                            </tr>

                            <!-- CHARGES -->
                            <tr class="bg-orange-50">
                                <td colspan="4" class="px-6 py-3">
                                    <span class="font-bold text-orange-900 uppercase text-sm">💸 CHARGES (DÉPENSES)</span>
                                </td>
                            </tr>
                            
                            @foreach($expenses as $account)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $account->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">
                                        Charge
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-orange-600">
                                    {{ format_currency($account->balance) }}
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Total Charges -->
                            <tr class="bg-orange-100 font-bold">
                                <td colspan="3" class="px-6 py-3 text-sm text-orange-900">TOTAL CHARGES</td>
                                <td class="px-6 py-3 text-right text-base text-orange-900">{{ format_currency($totalExpenses) }}</td>
                            </tr>

                            <!-- RÉSULTAT NET -->
                            <tr class="bg-gray-200 font-bold border-t-4 border-gray-400">
                                <td colspan="3" class="px-6 py-4 text-base text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <span>RÉSULTAT NET</span>
                                        <span class="text-xs font-normal {{ $result >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                            ({{ $result >= 0 ? 'Bénéfice' : 'Perte' }})
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-xl {{ $result >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ format_currency(abs($result)) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Résumé visuel -->
                    <div class="mt-6 grid grid-cols-3 gap-4">
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="text-xs text-green-600 uppercase font-semibold">Total Produits</div>
                            <div class="text-xl font-bold text-green-900 mt-1">{{ format_currency($totalRevenues) }}</div>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                            <div class="text-xs text-orange-600 uppercase font-semibold">Total Charges</div>
                            <div class="text-xl font-bold text-orange-900 mt-1">{{ format_currency($totalExpenses) }}</div>
                        </div>
                        <div class="rounded-lg p-4 border-2 {{ $result >= 0 ? 'bg-green-100 border-green-400' : 'bg-red-100 border-red-400' }}">
                            <div class="text-xs uppercase font-semibold {{ $result >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                {{ $result >= 0 ? '✓ Bénéfice' : '✗ Perte' }}
                            </div>
                            <div class="text-2xl font-bold mt-1 {{ $result >= 0 ? 'text-green-900' : 'text-red-900' }}">
                                {{ format_currency(abs($result)) }}
                            </div>
                        </div>
                    </div>

                    <!-- Calcul -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="text-center text-sm text-gray-600">
                            <span class="font-semibold">Calcul :</span> 
                            Produits ({{ format_currency($totalRevenues) }}) - Charges ({{ format_currency($totalExpenses) }}) = 
                            <span class="font-bold {{ $result >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                {{ $result >= 0 ? '+' : '-' }} {{ format_currency(abs($result)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

