<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Paiement {{ $payment->payment_number }}
            </h2>
            <a href="{{ route('payments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $payment->payment_number }}</h3>

                <dl class="grid grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Montant</dt>
                        <dd class="mt-1 text-2xl font-bold text-green-600">{{ format_currency($payment->amount) }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de paiement</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $payment->payment_date->format('d/m/Y') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Méthode de paiement</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                @if($payment->payment_method === 'cash') Espèces
                                @elseif($payment->payment_method === 'bank') Banque
                                @elseif($payment->payment_method === 'check') Chèque
                                @elseif($payment->payment_method === 'transfer') Virement
                                @else Autre
                                @endif
                            </span>
                        </dd>
                    </div>

                    @if($payment->invoice)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Facture liée</dt>
                        <dd class="mt-1">
                            <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $payment->invoice->invoice_number }}
                            </a>
                        </dd>
                    </div>
                    @endif

                    @if($payment->reference)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Référence</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $payment->reference }}</dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Créé par</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $payment->creator->name }}</dd>
                    </div>
                </dl>

                @if($payment->notes)
                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Notes</dt>
                    <dd class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $payment->notes }}</dd>
                </div>
                @endif

                <!-- Écritures comptables -->
                @if($payment->journalEntries->count() > 0)
                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Écritures comptables</h4>
                    @foreach($payment->journalEntries as $entry)
                    <div class="bg-gray-50 rounded-lg p-4 mb-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-gray-900">{{ $entry->entry_number }}</span>
                            <span class="text-sm text-gray-500">{{ $entry->entry_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="space-y-1">
                            @foreach($entry->lines as $line)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">{{ $line->account->code }} - {{ $line->account->name }}</span>
                                <span>
                                    @if($line->debit > 0)
                                    <span class="text-gray-900">Débit: {{ format_currency($line->debit) }}</span>
                                    @else
                                    <span class="text-gray-600">Crédit: {{ format_currency($line->credit) }}</span>
                                    @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

