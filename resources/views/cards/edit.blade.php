<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier Card
            </h2>
            <a href="{{ route('cards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('cards.update', $card) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom de la card *</label>
                                <input type="text" name="name" id="name" required value="{{ old('name', $card->name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                                    <select name="type" id="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                        <option value="custom" {{ old('type', $card->type) == 'custom' ? 'selected' : '' }}>Custom</option>
                                        <option value="invoice_customer" {{ old('type', $card->type) == 'invoice_customer' ? 'selected' : '' }}>Facture Client</option>
                                        <option value="invoice_supplier" {{ old('type', $card->type) == 'invoice_supplier' ? 'selected' : '' }}>Facture Fournisseur</option>
                                        <option value="cash" {{ old('type', $card->type) == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank" {{ old('type', $card->type) == 'bank' ? 'selected' : '' }}>Banque</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700">Couleur *</label>
                                    <input type="color" name="color" id="color" value="{{ old('color', $card->color) }}"
                                        class="mt-1 block w-full h-10 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700">Icône (emoji)</label>
                                <input type="text" name="icon" id="icon" value="{{ old('icon', $card->icon) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label for="debit_account_id" class="block text-sm font-medium text-gray-700">Compte au Débit *</label>
                                <select name="debit_account_id" id="debit_account_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('debit_account_id', $card->debit_account_id) == $account->id ? 'selected' : '' }}>
                                        {{ $account->code }} - {{ $account->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="credit_account_id" class="block text-sm font-medium text-gray-700">Compte au Crédit *</label>
                                <select name="credit_account_id" id="credit_account_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('credit_account_id', $card->credit_account_id) == $account->id ? 'selected' : '' }}>
                                        {{ $account->code }} - {{ $account->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description', $card->description) }}</textarea>
                            </div>

                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700">Ordre d'affichage</label>
                                <input type="number" name="order" id="order" value="{{ old('order', $card->order) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $card->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('cards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
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
</x-app-layout>

