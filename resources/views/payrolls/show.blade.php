<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fiche de Paie {{ $payroll->payroll_number }}</h2>
            <div class="flex gap-3">
                @if($payroll->status == 'draft')
                <form action="{{ route('payrolls.validate', $payroll) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Valider & Générer écriture
                    </button>
                </form>
                @endif
                <a href="{{ route('payrolls.edit', $payroll) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Modifier</a>
                <a href="{{ route('payrolls.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">← Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $payroll->payroll_number }}</h3>
                        <p class="text-sm text-gray-500">{{ $payroll->employee->full_name }} - {{ $payroll->employee->position }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($payroll->status === 'paid') bg-green-100 text-green-800
                        @elseif($payroll->status === 'validated') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        @if($payroll->status === 'draft') Brouillon
                        @elseif($payroll->status === 'validated') Validé
                        @elseif($payroll->status === 'paid') Payé
                        @else {{ $payroll->status }}
                        @endif
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Période</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $payroll->pay_period_start->format('d/m/Y') }} au {{ $payroll->pay_period_end->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de paiement</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $payroll->payment_date->format('d/m/Y') }}</dd>
                    </div>
                </dl>

                <!-- Détails du salaire -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Détails du salaire</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-700">Salaire brut</span>
                            <span class="text-sm font-semibold text-gray-900">{{ format_currency($payroll->gross_salary) }}</span>
                        </div>
                        @if($payroll->social_contributions > 0)
                        <div class="flex justify-between py-2 border-t border-gray-200">
                            <span class="text-sm text-gray-700">Cotisations sociales</span>
                            <span class="text-sm text-red-600">- {{ format_currency($payroll->social_contributions) }}</span>
                        </div>
                        @endif
                        @if($payroll->taxes > 0)
                        <div class="flex justify-between py-2 {{ $payroll->social_contributions > 0 ? '' : 'border-t border-gray-200' }}">
                            <span class="text-sm text-gray-700">Impôts</span>
                            <span class="text-sm text-red-600">- {{ format_currency($payroll->taxes) }}</span>
                        </div>
                        @endif
                        @if($payroll->other_deductions > 0)
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-700">Autres retenues</span>
                            <span class="text-sm text-red-600">- {{ format_currency($payroll->other_deductions) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between py-3 border-t-2 border-gray-300 mt-2">
                            <span class="text-base font-bold text-gray-900">Salaire net à payer</span>
                            <span class="text-lg font-bold text-green-600">{{ format_currency($payroll->net_salary) }}</span>
                        </div>
                    </div>
                </div>

                @if($payroll->notes)
                <div class="mb-6">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Notes</dt>
                    <dd class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $payroll->notes }}</dd>
                </div>
                @endif

                <!-- Écritures comptables -->
                @if($payroll->journalEntries->count() > 0)
                <div class="mt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Écritures comptables</h4>
                    @foreach($payroll->journalEntries as $entry)
                    <div class="bg-gray-50 rounded-lg p-4 mb-3 border border-gray-200">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-gray-900">{{ $entry->entry_number }}</span>
                            <span class="text-sm text-gray-500">{{ $entry->entry_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="space-y-1">
                            @foreach($entry->lines as $line)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">{{ $line->account->code }} - {{ $line->account->name }}</span>
                                <span>
                                    @if($line->debit > 0)
                                    <span class="text-gray-900">Débit: {{ format_currency($line->debit) }}</span>
                                    @else
                                    <span class="text-gray-600">Crédit: {{ format_currency($line->credit) }}</span>
                                    @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

