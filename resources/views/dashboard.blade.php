<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tableau de bord') }}
        </h2>
            
            <!-- Boutons d'action rapide -->
            <div class="flex gap-2">
                <a href="{{ route('customers.create') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Client
                </a>
                
                <a href="{{ route('suppliers.create') }}" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Fournisseur
                </a>

                <a href="{{ route('reports.trial-balance') }}" class="inline-flex items-center px-3 py-2 bg-teal-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                    Balance
                </a>

                <a href="{{ route('reports.balance-sheet') }}" class="inline-flex items-center px-3 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Bilan
                </a>

                <a href="{{ route('reports.financial-statement') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    État Financier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="text-sm text-gray-500">Clients actifs</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['total_customers'] }}</div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="text-sm text-gray-500">Fournisseurs actifs</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['total_suppliers'] }}</div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="text-sm text-gray-500">Factures clients en attente</div>
                    <div class="text-3xl font-bold text-green-600">{{ format_currency($stats['pending_customer_invoices']) }}</div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="text-sm text-gray-500">Factures fournisseurs en attente</div>
                    <div class="text-3xl font-bold text-red-600">{{ format_currency($stats['pending_supplier_invoices']) }}</div>
                </div>
            </div>

            <!-- Cards Comptables -->
            @if($accountingCards->count() > 0)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($accountingCards as $card)
                    <a href="{{ route('cards.show', $card) }}" 
                       class="block p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border-l-4"
                       style="background-color: {{ $card->color }}20; border-color: {{ $card->color }}">
                        <div class="flex items-center justify-between">
                            <div>
                                @if($card->icon)
                                <div class="text-2xl mb-2">{{ $card->icon }}</div>
                                @endif
                                <h4 class="font-semibold text-gray-900">{{ $card->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $card->type }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Dernières factures et paiements -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Dernières factures -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Dernières factures</h3>
                            <a href="{{ route('invoices.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Voir tout</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentInvoices as $invoice)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900">{{ $invoice->invoice_number }}</span>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($invoice->status === 'paid') bg-green-100 text-green-800
                                            @elseif($invoice->status === 'partial') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @if($invoice->status === 'draft') Brouillon
                                            @elseif($invoice->status === 'pending') En attente
                                            @elseif($invoice->status === 'partial') Partiel
                                            @elseif($invoice->status === 'paid') Payé
                                            @else {{ $invoice->status }}
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $invoice->party_name }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">{{ format_currency($invoice->total_amount) }}</div>
                                    <div class="text-xs text-gray-500">{{ $invoice->invoice_date->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-8">Aucune facture</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Derniers paiements -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Derniers paiements</h3>
                            <a href="{{ route('payments.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Voir tout</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentPayments as $payment)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $payment->payment_number }}</div>
                                    <p class="text-sm text-gray-600">
                                        @if($payment->payment_method === 'cash') Espèces
                                        @elseif($payment->payment_method === 'bank') Banque
                                        @elseif($payment->payment_method === 'check') Chèque
                                        @elseif($payment->payment_method === 'transfer') Virement
                                        @else Autre
                                        @endif
                                        @if($payment->invoice)
                                        - {{ $payment->invoice->invoice_number }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-green-600">{{ format_currency($payment->amount) }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->payment_date->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-8">Aucun paiement</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
