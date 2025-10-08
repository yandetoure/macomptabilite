<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $employee->full_name }}</h2>
            <div class="flex gap-3">
                <a href="{{ route('payrolls.create', ['employee_id' => $employee->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    + Nouvelle fiche de paie
                </a>
                <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Modifier</a>
                <a href="{{ route('employees.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">← Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Matricule</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $employee->employee_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Poste</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->position }}</dd>
                            </div>
                            @if($employee->department)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Département</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->department }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date d'embauche</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->hire_date->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Salaire de base</dt>
                                <dd class="mt-1 text-sm font-bold text-green-600">{{ format_currency($employee->base_salary) }}</dd>
                            </div>
                            @if($employee->email)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->email }}</dd>
                            </div>
                            @endif
                            @if($employee->phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->phone }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $employee->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Historique des paies</h3>
                        @if($employee->payrolls->count() > 0)
                        <div class="space-y-3">
                            @foreach($employee->payrolls as $payroll)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $payroll->payroll_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $payroll->pay_period_start->format('d/m/Y') }} - {{ $payroll->pay_period_end->format('d/m/Y') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">Net: {{ format_currency($payroll->net_salary) }}</div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 text-center py-8">Aucune fiche de paie</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

