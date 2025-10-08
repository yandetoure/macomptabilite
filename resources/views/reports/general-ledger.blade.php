<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📚 Grand Livre
            </h2>
            <form method="GET" class="flex gap-2">
                <label class="flex items-center text-sm">
                    <input type="checkbox" name="show_reconciled" value="1" {{ request('show_reconciled') ? 'checked' : '' }} 
                        onchange="this.form.submit()"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                    Afficher les réconciliées
                </label>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse($accounts as $account)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-6">
                <!-- En-tête du compte -->
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                {{ $account->code }} - {{ $account->name }}
                            </h3>
                            <p class="text-sm text-indigo-100">
                                @if($account->type === 'asset') Actif
                                @elseif($account->type === 'liability') Passif
                                @elseif($account->type === 'equity') Capitaux propres
                                @elseif($account->type === 'revenue') Produit
                                @elseif($account->type === 'expense') Charge
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-indigo-100">Mouvements</p>
                            <p class="text-2xl font-bold text-white">{{ $account->movements->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tableau des mouvements -->
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Écriture</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Débit</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Crédit</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Solde</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($account->movements as $movement)
                            <tr class="hover:bg-gray-50 {{ $movement->journalEntry->is_reconciled ? 'bg-blue-50' : '' }}">
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $movement->journalEntry->entry_date ? $movement->journalEntry->entry_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('journal-entries.show', $movement->journalEntry) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ $movement->journalEntry->entry_number }}
                                    </a>
                                    @if($movement->journalEntry->is_reconciled)
                                    <span class="ml-1 text-xs px-1 py-0.5 bg-blue-200 text-blue-800 rounded">🔒</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ Str::limit($movement->journalEntry->description ?? $movement->description ?? '-', 50) }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm {{ $movement->debit > 0 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                                    {{ $movement->debit > 0 ? format_currency($movement->debit) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm {{ $movement->credit > 0 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                                    {{ $movement->credit > 0 ? format_currency($movement->credit) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-semibold {{ $movement->running_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    @if($movement->running_balance < 0)
                                        - {{ format_currency(abs($movement->running_balance)) }}
                                    @else
                                        {{ format_currency($movement->running_balance) }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Totaux -->
                            <tr class="bg-indigo-50 font-bold">
                                <td colspan="3" class="px-4 py-3 text-sm text-gray-900">Total {{ $account->code }}</td>
                                <td class="px-4 py-3 text-right text-sm text-indigo-700">
                                    {{ format_currency($account->movements->sum('debit')) }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-indigo-700">
                                    {{ format_currency($account->movements->sum('credit')) }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-indigo-900">
                                    @php
                                        $soldeTotal = $account->movements->last()->running_balance ?? 0;
                                    @endphp
                                    @if($soldeTotal < 0)
                                        - {{ format_currency(abs($soldeTotal)) }}
                                    @else
                                        {{ format_currency($soldeTotal) }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500">Aucun mouvement comptable</p>
                <p class="text-sm text-gray-400 mt-2">
                    @if(!$showReconciled)
                    Cochez "Afficher les réconciliées" pour voir toutes les écritures
                    @endif
                </p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

