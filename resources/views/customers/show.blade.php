<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $customer->name }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    Modifier
                </a>
                <a href="{{ route('customers.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                    ← Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informations du client -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
                        
                        <dl class="space-y-3">
                            @if($customer->email)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customer->email }}</dd>
                            </div>
                            @endif

                            @if($customer->phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customer->phone }}</dd>
                            </div>
                            @endif

                            @if($customer->address)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customer->address }}</dd>
                            </div>
                            @endif

                            @if($customer->tax_id)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Numéro TVA/SIRET</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customer->tax_id }}</dd>
                            </div>
                            @endif

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $customer->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </dd>
                            </div>

                            @if($customer->notes)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customer->notes }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Factures -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Factures récentes</h3>
                        
                        @if($customer->invoices->count() > 0)
                        <div class="space-y-3">
                            @foreach($customer->invoices as $invoice)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $invoice->invoice_date->format('d/m/Y') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">{{ format_currency($invoice->total_amount) }}</div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($invoice->status === 'paid') bg-green-100 text-green-800
                                        @elseif($invoice->status === 'partial') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 text-center py-8">Aucune facture</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

