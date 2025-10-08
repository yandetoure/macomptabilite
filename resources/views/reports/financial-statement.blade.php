<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            État Financier (Compte de Résultat)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- PRODUITS -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-green-700 mb-4 pb-3 border-b border-gray-200">PRODUITS</h3>
                        
                        <div class="space-y-2">
                            @foreach($revenues as $account)
                            <div class="flex justify-between items-center py-2 hover:bg-gray-50 px-2 rounded">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $account->code }}</span>
                                    <span class="text-sm text-gray-600 ml-2">{{ $account->name }}</span>
                                </div>
                                <span class="text-sm font-semibold text-green-600">{{ format_currency($account->balance) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-4 border-t-2 border-green-300">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">TOTAL PRODUITS</span>
                                <span class="text-xl font-bold text-green-600">{{ format_currency($totalRevenues) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CHARGES -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-red-700 mb-4 pb-3 border-b border-gray-200">CHARGES</h3>
                        
                        <div class="space-y-2">
                            @foreach($expenses as $account)
                            <div class="flex justify-between items-center py-2 hover:bg-gray-50 px-2 rounded">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $account->code }}</span>
                                    <span class="text-sm text-gray-600 ml-2">{{ $account->name }}</span>
                                </div>
                                <span class="text-sm font-semibold text-red-600">{{ format_currency($account->balance) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-4 border-t-2 border-red-300">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">TOTAL CHARGES</span>
                                <span class="text-xl font-bold text-red-600">{{ format_currency($totalExpenses) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RÉSULTAT -->
            <div class="mt-6">
                <div class="bg-white border-2 {{ $result >= 0 ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50' }} rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">RÉSULTAT NET</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $result >= 0 ? 'Bénéfice' : 'Perte' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold {{ $result >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ format_currency(abs($result)) }}
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                Produits - Charges
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-sm text-gray-600">Total Produits</div>
                        <div class="text-lg font-bold text-green-600">{{ format_currency($totalRevenues) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Total Charges</div>
                        <div class="text-lg font-bold text-red-600">{{ format_currency($totalExpenses) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Résultat</div>
                        <div class="text-lg font-bold {{ $result >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ format_currency(abs($result)) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

