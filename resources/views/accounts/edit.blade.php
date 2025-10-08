<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier Compte {{ $account->code }}
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
                    <form action="{{ route('accounts.update', $account) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700">Code comptable *</label>
                                    <input type="text" name="code" id="code" required value="{{ old('code', $account->code) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                                    <select name="type" id="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                        <option value="asset" {{ old('type', $account->type) == 'asset' ? 'selected' : '' }}>Actif</option>
                                        <option value="liability" {{ old('type', $account->type) == 'liability' ? 'selected' : '' }}>Passif</option>
                                        <option value="equity" {{ old('type', $account->type) == 'equity' ? 'selected' : '' }}>Capitaux propres</option>
                                        <option value="revenue" {{ old('type', $account->type) == 'revenue' ? 'selected' : '' }}>Produits</option>
                                        <option value="expense" {{ old('type', $account->type) == 'expense' ? 'selected' : '' }}>Charges</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom du compte *</label>
                                <input type="text" name="name" id="name" required value="{{ old('name', $account->name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label for="parent_id" class="block text-sm font-medium text-gray-700">Compte parent</label>
                                <select name="parent_id" id="parent_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Aucun</option>
                                    @foreach($parentAccounts as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id', $account->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->code }} - {{ $parent->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description', $account->description) }}</textarea>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $account->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">Actif</label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('accounts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
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

