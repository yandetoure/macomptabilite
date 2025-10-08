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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $entry->entry_number }}</h3>
                        <p class="text-sm text-gray-500">{{ $entry->entry_date ? $entry->entry_date->format('d/m/Y') : 'Date non définie' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $entry->status === 'posted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $entry->status === 'posted' ? 'Comptabilisé' : 'Brouillon' }}
                    </span>
                </div>

                @if($entry->reference)
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Référence :</span>
                    <span class="text-sm font-medium text-gray-900">{{ $entry->reference }}</span>
                </div>
                @endif

                @if($entry->description)
                <div class="mb-6">
                    <span class="text-sm text-gray-500">Description :</span>
                    <p class="text-sm text-gray-900 mt-1">{{ $entry->description }}</p>
                </div>
                @endif

                <!-- Lignes d'écriture -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Lignes d'écriture</h4>
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
                                @foreach($entry->lines as $line)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $line->account->code }}</div>
                                        <div class="text-xs text-gray-500">{{ $line->account->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm {{ $line->debit > 0 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                                        {{ $line->debit > 0 ? format_currency($line->debit) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm {{ $line->credit > 0 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                                        {{ $line->credit > 0 ? format_currency($line->credit) : '-' }}
                                    </td>
                                </tr>
                                @endforeach
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

                <!-- Liens -->
                <div class="space-y-2">
                    @if($entry->invoice)
                    <div>
                        <span class="text-sm text-gray-500">Facture liée :</span>
                        <a href="{{ route('invoices.show', $entry->invoice) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            {{ $entry->invoice->invoice_number }}
                        </a>
                    </div>
                    @endif

                    @if($entry->payment)
                    <div>
                        <span class="text-sm text-gray-500">Paiement lié :</span>
                        <a href="{{ route('payments.show', $entry->payment) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            {{ $entry->payment->payment_number }}
                        </a>
                    </div>
                    @endif

                    @if($entry->payroll)
                    <div>
                        <span class="text-sm text-gray-500">Fiche de paie liée :</span>
                        <a href="{{ route('payrolls.show', $entry->payroll) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            {{ $entry->payroll->payroll_number }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

