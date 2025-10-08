<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouveau Compte
            </h2>
            <a href="{{ route('accounts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('accounts.store') }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700">Code comptable *</label>
                                    <input type="text" name="code" id="code" required value="{{ old('code') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    @error('code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                                    <select name="type" id="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                        <option value="">Sélectionner...</option>
                                        <option value="asset">Actif</option>
                                        <option value="liability">Passif</option>
                                        <option value="equity">Capitaux propres</option>
                                        <option value="revenue">Produits</option>
                                        <option value="expense">Charges</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom du compte *</label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label for="parent_id" class="block text-sm font-medium text-gray-700">Compte parent (optionnel)</label>
                                <select name="parent_id" id="parent_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Aucun</option>
                                    @foreach($parentAccounts as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->code }} - {{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('accounts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Créer le compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

