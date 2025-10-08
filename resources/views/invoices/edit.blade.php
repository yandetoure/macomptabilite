<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier Facture {{ $invoice->invoice_number }}
            </h2>
            <a href="{{ route('invoices.show', $invoice) }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('invoices.update', $invoice) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Type de facture *</label>
                                <select name="type" id="type" required onchange="togglePartySelect(this.value)"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="customer" {{ old('type', $invoice->type) == 'customer' ? 'selected' : '' }}>Facture Client</option>
                                    <option value="supplier" {{ old('type', $invoice->type) == 'supplier' ? 'selected' : '' }}>Facture Fournisseur</option>
                                </select>
                            </div>

                            <div id="customer_select" style="display: {{ old('type', $invoice->type) == 'customer' ? 'block' : 'none' }};">
                                <label for="customer_id" class="block text-sm font-medium text-gray-700">Client</label>
                                <select name="customer_id" id="customer_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Sélectionner un client...</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="supplier_select" style="display: {{ old('type', $invoice->type) == 'supplier' ? 'block' : 'none' }};">
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Fournisseur</label>
                                <select name="supplier_id" id="supplier_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Sélectionner un fournisseur...</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $invoice->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="invoice_date" class="block text-sm font-medium text-gray-700">Date de facture *</label>
                                    <input type="date" name="invoice_date" id="invoice_date" required value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>

                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="total_amount" class="block text-sm font-medium text-gray-700">Montant total (FCFA) *</label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount" required value="{{ old('total_amount', $invoice->total_amount) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description', $invoice->description) }}</textarea>
                            </div>

                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700">Remplacer le fichier (optionnel)</label>
                                <input type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @if($invoice->file_path)
                                <p class="mt-1 text-xs text-gray-500">Fichier actuel disponible</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePartySelect(type) {
            const customerSelect = document.getElementById('customer_select');
            const supplierSelect = document.getElementById('supplier_select');
            
            if (type === 'customer') {
                customerSelect.style.display = 'block';
                supplierSelect.style.display = 'none';
            } else if (type === 'supplier') {
                customerSelect.style.display = 'none';
                supplierSelect.style.display = 'block';
            }
        }
    </script>
</x-app-layout>

