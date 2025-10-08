<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Factures
            </h2>
            <a href="{{ route('invoices.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Nouvelle Facture
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <!-- Filtres -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                <form method="GET" class="flex gap-4">
                    <select name="type" class="rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="all">Tous les types</option>
                        <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>Factures clients</option>
                        <option value="supplier" {{ request('type') == 'supplier' ? 'selected' : '' }}>Factures fournisseurs</option>
                    </select>
                    
                    <select name="status" class="rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="all">Tous les statuts</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partiel</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payé</option>
                    </select>
                    
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Filtrer
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client/Fournisseur</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $invoice->invoice_number }}
                                        @if($invoice->is_credit_note)
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">AVOIR</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $invoice->type == 'customer' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $invoice->type == 'customer' ? 'Client' : 'Fournisseur' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->party_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ format_currency($invoice->total_amount) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($invoice->status === 'paid') bg-green-100 text-green-800
                                        @elseif($invoice->status === 'partial') bg-yellow-100 text-yellow-800
                                        @elseif($invoice->status === 'draft') bg-gray-100 text-gray-800
                                        @else bg-orange-100 text-orange-800
                                        @endif">
                                        @if($invoice->status === 'draft') Brouillon
                                        @elseif($invoice->status === 'pending') En attente
                                        @elseif($invoice->status === 'partial') Partiel
                                        @elseif($invoice->status === 'paid') Payé
                                        @else {{ $invoice->status }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Aucune facture trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

