<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fiches de Paie</h2>
            <a href="{{ route('payrolls.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Nouvelle Fiche de Paie
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Numéro</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Employé</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Brut</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Net</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($payrolls as $payroll)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payroll->payroll_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payroll->employee->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payroll->pay_period_start->format('m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($payroll->gross_salary) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ format_currency($payroll->net_salary) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('payrolls.show', $payroll) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                    <a href="{{ route('payrolls.edit', $payroll) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Aucune fiche de paie trouvée</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">{{ $payrolls->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

