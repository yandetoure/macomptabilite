<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cards Comptables') }}
            </h2>
            <a href="{{ route('cards.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Nouvelle Card
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($cards as $card)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 border-l-4" style="border-left-color: {{ $card->color }}">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                @if($card->icon)
                                <div class="text-3xl">{{ $card->icon }}</div>
                                @endif
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $card->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $card->type)) }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs" style="background-color: {{ $card->color }}20; color: {{ $card->color }}">
                                {{ $card->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>

                        @if($card->description)
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($card->description, 80) }}</p>
                        @endif

                        <div class="space-y-2 mb-4">
                            <div class="text-xs">
                                <span class="text-gray-500">Débit:</span>
                                <span class="text-gray-900 font-medium">{{ $card->debitAccount->code }}</span>
                            </div>
                            <div class="text-xs">
                                <span class="text-gray-500">Crédit:</span>
                                <span class="text-gray-900 font-medium">{{ $card->creditAccount->code }}</span>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('cards.show', $card) }}" class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                Utiliser
                            </a>
                            <a href="{{ route('cards.edit', $card) }}" class="px-3 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300 transition">
                                Modifier
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Aucune card trouvée</p>
                    <a href="{{ route('cards.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                        Créer votre première card
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

