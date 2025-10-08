<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $card->name }}
            </h2>
            <a href="{{ route('cards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour aux cards
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            @if($card->icon)
                            <div class="text-4xl">{{ $card->icon }}</div>
                            @endif
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $card->name }}</h3>
                                <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $card->type)) }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm" style="background-color: {{ $card->color }}20; color: {{ $card->color }}">
                            {{ $card->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    @if($card->description)
                    <p class="text-gray-700 mb-4">{{ $card->description }}</p>
                    @endif

                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-sm text-gray-600 mb-1">Compte au Débit</div>
                            <div class="font-semibold text-gray-900">
                                {{ $card->debitAccount->code }} - {{ $card->debitAccount->name }}
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-sm text-gray-600 mb-1">Compte au Crédit</div>
                            <div class="font-semibold text-gray-900">
                                {{ $card->creditAccount->code }} - {{ $card->creditAccount->name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de transaction -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Créer une transaction</h4>
                    
                    @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('cards.transaction', $card) }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
                            <input type="number" step="0.01" name="amount" id="amount" required
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        </div>

                        <div>
                            <label for="reference" class="block text-sm font-medium text-gray-700">Référence (optionnel)</label>
                            <input type="text" name="reference" id="reference"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                Enregistrer la transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

