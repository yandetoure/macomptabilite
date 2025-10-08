<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouvelle Facture
            </h2>
            <a href="{{ route('invoices.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type de facture *</label>
                                    <select name="type" id="type" required onchange="togglePartySelect(this.value)"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                        <option value="">Sélectionner...</option>
                                        <option value="customer" {{ old('type') == 'customer' ? 'selected' : '' }}>Facture Client</option>
                                        <option value="supplier" {{ old('type') == 'supplier' ? 'selected' : '' }}>Facture Fournisseur</option>
                                    </select>
                                </div>

                                <div class="flex items-center pt-6">
                                    <input type="checkbox" name="is_credit_note" id="is_credit_note" value="1" {{ old('is_credit_note') ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="is_credit_note" class="ml-2 block text-sm text-gray-700">
                                        <span class="font-semibold text-red-600">Facture d'avoir</span>
                                        <span class="text-xs text-gray-500 block">(Annulation/Remboursement)</span>
                                    </label>
                                </div>
                            </div>

                            <div id="customer_select" style="display: none;">
                                <label for="customer_id" class="block text-sm font-medium text-gray-700">Client</label>
                                <select name="customer_id" id="customer_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Sélectionner un client...</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="supplier_select" style="display: none;">
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Fournisseur</label>
                                <select name="supplier_id" id="supplier_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Sélectionner un fournisseur...</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="invoice_date" class="block text-sm font-medium text-gray-700">Date de facture *</label>
                                    <input type="date" name="invoice_date" id="invoice_date" required value="{{ old('invoice_date', date('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>

                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="total_amount" class="block text-sm font-medium text-gray-700">Montant total (FCFA) *</label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount" required value="{{ old('total_amount') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700">Fichier (PDF, Image)</label>
                                <input type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">PDF, JPG, PNG (max 10 Mo)</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('invoices.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Créer la facture
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
            } else {
                customerSelect.style.display = 'none';
                supplierSelect.style.display = 'none';
            }
        }
        
        // Si old('type') existe
        @if(old('type'))
        togglePartySelect('{{ old('type') }}');
        @endif
    </script>
</x-app-layout>

