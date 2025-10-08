<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau des Flux de Trésorerie
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Résumé de trésorerie -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="text-xs text-blue-600 uppercase font-semibold">Trésorerie Actuelle</div>
                    <div class="text-2xl font-bold text-blue-900 mt-1">{{ format_currency($currentCash) }}</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="text-xs text-green-600 uppercase font-semibold">Entrées ce mois</div>
                    <div class="text-2xl font-bold text-green-900 mt-1">{{ format_currency($totalInflows) }}</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <div class="text-xs text-red-600 uppercase font-semibold">Sorties ce mois</div>
                    <div class="text-2xl font-bold text-red-900 mt-1">{{ format_currency($totalOutflows) }}</div>
                </div>
                <div class="rounded-lg p-4 border-2 {{ $netCashFlow >= 0 ? 'bg-green-100 border-green-400' : 'bg-red-100 border-red-400' }}">
                    <div class="text-xs uppercase font-semibold {{ $netCashFlow >= 0 ? 'text-green-700' : 'text-red-700' }}">
                        Flux Net
                    </div>
                    <div class="text-2xl font-bold mt-1 {{ $netCashFlow >= 0 ? 'text-green-900' : 'text-red-900' }}">
                        {{ format_currency(abs($netCashFlow)) }}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Mouvements de trésorerie du mois</h3>
                        <p class="text-sm text-gray-600 mt-1">Comptes : Banque (512) et Caisse (531)</p>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Compte</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Entrée</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Sortie</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- ENTRÉES -->
                            @if($cashInflows->count() > 0)
                            <tr class="bg-green-50">
                                <td colspan="6" class="px-6 py-2">
                                    <span class="font-bold text-green-900 text-sm">💰 ENTRÉES DE TRÉSORERIE</span>
                                </td>
                            </tr>
                            @foreach($cashInflows as $line)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $line->journalEntry->entry_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $line->account->code }} - {{ $line->account->name }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $line->journalEntry->reference ?? '-' }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ Str::limit($line->journalEntry->description ?? '', 40) }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-semibold text-green-600">{{ format_currency($line->debit) }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-right text-sm text-gray-400">-</td>
                            </tr>
                            @endforeach
                            @endif

                            <!-- SORTIES -->
                            @if($cashOutflows->count() > 0)
                            <tr class="bg-red-50">
                                <td colspan="6" class="px-6 py-2">
                                    <span class="font-bold text-red-900 text-sm">💸 SORTIES DE TRÉSORERIE</span>
                                </td>
                            </tr>
                            @foreach($cashOutflows as $line)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $line->journalEntry->entry_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $line->account->code }} - {{ $line->account->name }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $line->journalEntry->reference ?? '-' }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ Str::limit($line->journalEntry->description ?? '', 40) }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-right text-sm text-gray-400">-</td>
                                <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-semibold text-red-600">{{ format_currency($line->credit) }}</td>
                            </tr>
                            @endforeach
                            @endif

                            @if($cashInflows->count() == 0 && $cashOutflows->count() == 0)
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Aucun mouvement ce mois</td>
                            </tr>
                            @endif

                            <!-- TOTAUX -->
                            <tr class="bg-gray-200 font-bold">
                                <td colspan="4" class="px-6 py-3 text-sm text-gray-900">TOTAUX</td>
                                <td class="px-6 py-3 text-right text-base text-green-900">{{ format_currency($totalInflows) }}</td>
                                <td class="px-6 py-3 text-right text-base text-red-900">{{ format_currency($totalOutflows) }}</td>
                            </tr>

                            <!-- SOLDE -->
                            <tr class="bg-gray-300 font-bold border-t-2 border-gray-500">
                                <td colspan="4" class="px-6 py-4 text-base text-gray-900">FLUX NET (Entrées - Sorties)</td>
                                <td colspan="2" class="px-6 py-4 text-right text-lg {{ $netCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $netCashFlow >= 0 ? '+' : '-' }} {{ format_currency(abs($netCashFlow)) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

