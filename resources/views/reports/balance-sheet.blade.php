<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bilan Comptable
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- ACTIF -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">ACTIF</h3>
                        
                        <div class="space-y-2">
                            @foreach($assets as $account)
                            <div class="flex justify-between items-center py-2 hover:bg-gray-50 px-2 rounded">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $account->code }}</span>
                                    <span class="text-sm text-gray-600 ml-2">{{ $account->name }}</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ format_currency($account->balance) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-4 border-t-2 border-gray-300">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">TOTAL ACTIF</span>
                                <span class="text-xl font-bold text-blue-600">{{ format_currency($totalAssets) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PASSIF + CAPITAUX PROPRES -->
                <div class="space-y-6">
                    <!-- PASSIF -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">PASSIF</h3>
                            
                            <div class="space-y-2">
                                @foreach($liabilities as $account)
                                <div class="flex justify-between items-center py-2 hover:bg-gray-50 px-2 rounded">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">{{ $account->code }}</span>
                                        <span class="text-sm text-gray-600 ml-2">{{ $account->name }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ format_currency($account->balance) }}</span>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-900">Total Passif</span>
                                    <span class="text-base font-bold text-gray-900">{{ format_currency($totalLiabilities) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CAPITAUX PROPRES -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">CAPITAUX PROPRES</h3>
                            
                            <div class="space-y-2">
                                @foreach($equity as $account)
                                <div class="flex justify-between items-center py-2 hover:bg-gray-50 px-2 rounded">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">{{ $account->code }}</span>
                                        <span class="text-sm text-gray-600 ml-2">{{ $account->name }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ format_currency($account->balance) }}</span>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-900">Total Capitaux propres</span>
                                    <span class="text-base font-bold text-gray-900">{{ format_currency($totalEquity) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TOTAL PASSIF + CAPITAUX -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">TOTAL PASSIF + CAPITAUX</span>
                            <span class="text-xl font-bold text-blue-600">{{ format_currency($totalLiabilities + $totalEquity) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Équilibre du bilan -->
            <div class="mt-6 p-4 rounded-lg {{ abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01 ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                <div class="flex items-center justify-center">
                    @if(abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01)
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-800 font-semibold">Bilan équilibré</span>
                    @else
                    <svg class="w-6 h-6 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-800 font-semibold">Bilan non équilibré (différence: {{ format_currency(abs($totalAssets - ($totalLiabilities + $totalEquity))) }})</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

