<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Écriture {{ $entry->entry_number }}
            </h2>
            <a href="{{ route('journal-entries.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Carte d'en-tête -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $entry->entry_number }}</h3>
                        <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                @if($entry->entry_date)
                                    {{ $entry->entry_date->format('d/m/Y') }}
                                @else
                                    <span class="text-red-500">⚠️ Date non définie</span>
                                @endif
                            </div>
                            @if($entry->reference)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $entry->reference }}
                            </div>
                            @endif
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $entry->creator ? $entry->creator->name : 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $entry->status === 'posted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $entry->status === 'posted' ? '✅ Comptabilisé' : '📝 Brouillon' }}
                    </span>
                </div>

                @if($entry->description)
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-700">{{ $entry->description }}</p>
                </div>
                @endif
            </div>

            <!-- Carte des lignes d'écriture -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Détails de l'écriture
                </h4>

                <div class="mb-6">
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Compte</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Débit</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Crédit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($entry->lines as $line)
                                <tr>
                                    <td class="px-4 py-3">
                                        @if($line->account)
                                            <div class="text-sm font-medium text-gray-900">{{ $line->account->code }}</div>
                                            <div class="text-xs text-gray-500">{{ $line->account->name }}</div>
                                        @else
                                            <div class="text-sm font-medium text-red-600">Compte #{{ $line->account_id }} non trouvé</div>
                                        @endif
                                        @if($line->description)
                                        <div class="text-xs text-gray-400 mt-1">{{ $line->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm {{ $line->debit > 0 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                                        {{ $line->debit > 0 ? format_currency($line->debit) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm {{ $line->credit > 0 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                                        {{ $line->credit > 0 ? format_currency($line->credit) : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                        <div class="text-yellow-600 mb-2">⚠️ Aucune ligne d'écriture trouvée</div>
                                        <div class="text-sm">Cette écriture ne contient aucune ligne.</div>
                                    </td>
                                </tr>
                                @endforelse
                                <tr class="bg-gray-50 font-bold">
                                    <td class="px-4 py-3 text-sm text-gray-900">Total</td>
                                    <td class="px-4 py-3 text-right text-sm text-gray-900">{{ format_currency($entry->getTotalDebit()) }}</td>
                                    <td class="px-4 py-3 text-right text-sm text-gray-900">{{ format_currency($entry->getTotalCredit()) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if($entry->is_balanced)
                    <div class="mt-2 text-sm text-green-600 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Écriture équilibrée
                    </div>
                    @else
                    <div class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Écriture non équilibrée
                    </div>
                    @endif
                </div>

                <!-- Documents liés -->
                @if($entry->invoice || $entry->payment || $entry->payroll)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        Documents liés
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @if($entry->invoice)
                        <a href="{{ route('invoices.show', $entry->invoice) }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                📄
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Facture</p>
                                <p class="text-sm font-semibold text-blue-700">{{ $entry->invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-600">{{ format_currency($entry->invoice->total_amount) }}</p>
                            </div>
                        </a>
                        @endif

                        @if($entry->payment)
                        <a href="{{ route('payments.show', $entry->payment) }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                                💰
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Paiement</p>
                                <p class="text-sm font-semibold text-green-700">{{ $entry->payment->payment_number }}</p>
                                <p class="text-xs text-gray-600">{{ format_currency($entry->payment->amount) }}</p>
                            </div>
                        </a>
                        @endif

                        @if($entry->payroll)
                        <a href="{{ route('payrolls.show', $entry->payroll) }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white">
                                💵
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Fiche de paie</p>
                                <p class="text-sm font-semibold text-purple-700">{{ $entry->payroll->payroll_number }}</p>
                                <p class="text-xs text-gray-600">{{ $entry->payroll->employee->full_name }}</p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
