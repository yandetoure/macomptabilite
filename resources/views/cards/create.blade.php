<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouvelle Card Comptable
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
                    <form action="{{ route('cards.store') }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom de la card *</label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                                    <select name="type" id="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                        <option value="custom">Custom</option>
                                        <option value="invoice_customer">Facture Client</option>
                                        <option value="invoice_supplier">Facture Fournisseur</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank">Banque</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700">Couleur *</label>
                                    <input type="color" name="color" id="color" value="{{ old('color', '#3b82f6') }}"
                                        class="mt-1 block w-full h-10 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700">Icône (emoji)</label>
                                <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="💰"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                <p class="mt-1 text-xs text-gray-500">Exemples : 💰 🏦 💵 📄 🎴</p>
                            </div>

                            <div>
                                <label for="debit_account_id" class="block text-sm font-medium text-gray-700">Compte au Débit *</label>
                                <select name="debit_account_id" id="debit_account_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Sélectionner...</option>
                                    @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="credit_account_id" class="block text-sm font-medium text-gray-700">Compte au Crédit *</label>
                                <select name="credit_account_id" id="credit_account_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Sélectionner...</option>
                                    @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700">Ordre d'affichage</label>
                                <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                <p class="mt-1 text-xs text-gray-500">Plus le nombre est petit, plus la card apparaît en premier</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('cards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Créer la card
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

