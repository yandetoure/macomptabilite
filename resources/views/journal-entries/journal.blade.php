<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📖 Journal Comptable
            </h2>
            <a href="{{ route('journal-entries.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour aux écritures
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cartes statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Écritures</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $entries->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Débits</p>
                            <p class="text-2xl font-semibold text-green-600">{{ format_currency($totalDebit) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Crédits</p>
                            <p class="text-2xl font-semibold text-red-600">{{ format_currency($totalCredit) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Journal -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Journal Général</h3>
                    
                    @forelse($entries as $entry)
                    <!-- En-tête d'écriture -->
                    <div class="mb-4 border-b border-gray-300 pb-2">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('journal-entries.show', $entry) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    {{ $entry->entry_number }}
                                </a>
                                <span class="text-sm text-gray-600">{{ $entry->entry_date ? $entry->entry_date->format('d/m/Y') : '-' }}</span>
                                @if($entry->reference)
                                <span class="text-sm text-gray-500">Réf: {{ $entry->reference }}</span>
                                @endif
                            </div>
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">Comptabilisé</span>
                        </div>
                        @if($entry->description)
                        <p class="text-sm text-gray-600 mt-1">{{ $entry->description }}</p>
                        @endif
                    </div>

                    <!-- Lignes d'écriture -->
                    <div class="mb-6 ml-4">
                        <table class="min-w-full">
                            <tbody>
                                @foreach($entry->lines as $line)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2 pr-4" style="width: 100px;">
                                        <span class="text-sm font-mono text-gray-600">{{ $line->account->code }}</span>
                                    </td>
                                    <td class="py-2 pr-4">
                                        <span class="text-sm text-gray-900">{{ $line->account->name }}</span>
                                        @if($line->description)
                                        <span class="text-xs text-gray-500 ml-2">({{ $line->description }})</span>
                                        @endif
                                    </td>
                                    <td class="py-2 text-right pr-4" style="width: 150px;">
                                        @if($line->debit > 0)
                                        <span class="text-sm font-semibold text-gray-900">{{ format_currency($line->debit) }}</span>
                                        @else
                                        <span class="text-sm text-gray-300">-</span>
                                        @endif
                                    </td>
                                    <td class="py-2 text-right" style="width: 150px;">
                                        @if($line->credit > 0)
                                        <span class="text-sm font-semibold text-gray-900">{{ format_currency($line->credit) }}</span>
                                        @else
                                        <span class="text-sm text-gray-300">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                <!-- Total de l'écriture -->
                                <tr class="bg-gray-50 font-semibold">
                                    <td colspan="2" class="py-2 px-2 text-right text-sm text-gray-600">Total:</td>
                                    <td class="py-2 text-right pr-4 text-sm">{{ format_currency($entry->getTotalDebit()) }}</td>
                                    <td class="py-2 text-right text-sm">{{ format_currency($entry->getTotalCredit()) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @empty
                    <div class="text-center py-12 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>Aucune écriture comptabilisée</p>
                    </div>
                    @endforelse

                    <!-- Total général -->
                    @if($entries->count() > 0)
                    <div class="mt-6 pt-4 border-t-2 border-gray-800">
                        <div class="flex justify-end">
                            <table class="w-auto">
                                <tr class="text-lg font-bold">
                                    <td class="py-2 px-4 text-right">Total Général:</td>
                                    <td class="py-2 px-4 text-right text-green-600" style="width: 150px;">{{ format_currency($totalDebit) }}</td>
                                    <td class="py-2 px-4 text-right text-red-600" style="width: 150px;">{{ format_currency($totalCredit) }}</td>
                                </tr>
                                @if($totalDebit == $totalCredit)
                                <tr>
                                    <td colspan="3" class="text-center text-sm text-green-600 pt-2">
                                        <svg class="inline-block w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Journal équilibré
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

