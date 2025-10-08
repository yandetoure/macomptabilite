<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Facture {{ $invoice->invoice_number }}
            </h2>
            <div class="flex gap-3">
                @if($invoice->status != 'paid' && $invoice->status != 'partial')
                <button onclick="document.getElementById('paymentModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Marquer comme payé
                </button>
                @endif
                
                @if($invoice->status == 'paid' || $invoice->status == 'partial')
                <button onclick="confirmCancelPayment()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    🔄 Annuler le paiement
                </button>
                @endif
                
                <a href="{{ route('invoices.edit', $invoice) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    Modifier
                </a>
                <a href="{{ route('invoices.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                    ← Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Détails facture -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $invoice->invoice_number }}
                                @if($invoice->is_credit_note)
                                <span class="ml-3 px-3 py-1 text-sm rounded-full bg-red-100 text-red-800 font-semibold">AVOIR</span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $invoice->is_credit_note ? 'Facture d\'avoir - ' : '' }}
                                {{ $invoice->type == 'customer' ? 'Client' : 'Fournisseur' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($invoice->status === 'paid') bg-green-100 text-green-800
                            @elseif($invoice->status === 'partial') bg-yellow-100 text-yellow-800
                            @else bg-orange-100 text-orange-800
                            @endif">
                            @if($invoice->status === 'draft') Brouillon
                            @elseif($invoice->status === 'pending') En attente
                            @elseif($invoice->status === 'partial') Partiel
                            @elseif($invoice->status === 'paid') Payé
                            @else {{ $invoice->status }}
                            @endif
                        </span>
                    </div>

                        <dl class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ $invoice->type == 'customer' ? 'Client' : 'Fournisseur' }}</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->party_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de facture</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $invoice->invoice_date->format('d/m/Y') }}</dd>
                            </div>
                            @if($invoice->due_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date d'échéance</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $invoice->due_date->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Montant total</dt>
                                <dd class="mt-1 text-lg font-bold text-gray-900">{{ format_currency($invoice->total_amount) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Montant payé</dt>
                                <dd class="mt-1 text-lg font-bold text-green-600">{{ format_currency($invoice->paid_amount) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Reste à payer</dt>
                                <dd class="mt-1 text-lg font-bold text-red-600">{{ format_currency($invoice->getRemainingAmount()) }}</dd>
                            </div>
                        </dl>

                        @if($invoice->description)
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Description</dt>
                            <dd class="text-sm text-gray-900">{{ $invoice->description }}</dd>
                        </div>
                        @endif

                        @if($invoice->file_path)
                        <div>
                            <a href="{{ route('invoices.download', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Télécharger la facture
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Paiements -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paiements</h3>
                        
                        @if($invoice->payments->count() > 0)
                        <div class="space-y-3">
                            @foreach($invoice->payments as $payment)
                            <div class="border-b border-gray-200 pb-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ format_currency($payment->amount) }}</div>
                                        <div class="text-xs text-gray-500">{{ $payment->payment_date->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($payment->payment_method) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Aucun paiement enregistré</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Paiement -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Enregistrer un paiement</h3>
            
            <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Montant (FCFA)</label>
                        <input type="number" step="0.01" name="amount" required value="{{ $invoice->getRemainingAmount() }}"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de paiement</label>
                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
                        <select name="payment_method" required
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            <option value="bank">Banque</option>
                            <option value="cash">Espèces</option>
                            <option value="check">Chèque</option>
                            <option value="transfer">Virement</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Référence</label>
                        <input type="text" name="reference"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" rows="2"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulaire caché pour annulation paiement -->
    <form id="cancelPaymentForm" action="{{ route('invoices.cancel-payment', $invoice) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        function confirmCancelPayment() {
            Swal.fire({
                title: '⚠️ Annuler le paiement ?',
                html: `
                    <div class="text-left">
                        <p class="mb-3 font-semibold text-gray-700">Cette action va :</p>
                        <ul class="list-disc pl-5 space-y-2 text-sm text-gray-600">
                            <li>Supprimer tous les paiements liés</li>
                            <li>Supprimer les écritures comptables</li>
                            <li>Inverser les soldes des comptes</li>
                            <li>Remettre la facture en "En attente"</li>
                        </ul>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, annuler le paiement',
                cancelButtonText: 'Non, conserver',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancelPaymentForm').submit();
                }
            });
        }
    </script>
</x-app-layout>

