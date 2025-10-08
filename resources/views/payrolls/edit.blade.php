<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier Fiche de Paie</h2>
            <a href="{{ route('payrolls.show', $payroll) }}" class="text-sm text-gray-600 hover:text-gray-900">← Retour</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('payrolls.update', $payroll) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700">Employé *</label>
                                <select name="employee_id" id="employee_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $payroll->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->employee_number }} - {{ $employee->full_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="pay_period_start" class="block text-sm font-medium text-gray-700">Début période *</label>
                                    <input type="date" name="pay_period_start" id="pay_period_start" required value="{{ old('pay_period_start', $payroll->pay_period_start->format('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                                <div>
                                    <label for="pay_period_end" class="block text-sm font-medium text-gray-700">Fin période *</label>
                                    <input type="date" name="pay_period_end" id="pay_period_end" required value="{{ old('pay_period_end', $payroll->pay_period_end->format('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700">Date de paiement *</label>
                                <input type="date" name="payment_date" id="payment_date" required value="{{ old('payment_date', $payroll->payment_date->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label for="gross_salary" class="block text-sm font-medium text-gray-700">Salaire brut (FCFA) *</label>
                                <input type="number" step="0.01" name="gross_salary" id="gross_salary" required value="{{ old('gross_salary', $payroll->gross_salary) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Retenues</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label for="social_contributions" class="block text-xs font-medium text-gray-600">Cotisations sociales (FCFA)</label>
                                        <input type="number" step="0.01" name="social_contributions" id="social_contributions" value="{{ old('social_contributions', $payroll->social_contributions) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                    </div>
                                    <div>
                                        <label for="taxes" class="block text-xs font-medium text-gray-600">Impôts (FCFA)</label>
                                        <input type="number" step="0.01" name="taxes" id="taxes" value="{{ old('taxes', $payroll->taxes) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                    </div>
                                    <div>
                                        <label for="other_deductions" class="block text-xs font-medium text-gray-600">Autres retenues (FCFA)</label>
                                        <input type="number" step="0.01" name="other_deductions" id="other_deductions" value="{{ old('other_deductions', $payroll->other_deductions) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea name="notes" id="notes" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('notes', $payroll->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('payrolls.show', $payroll) }}" class="text-sm text-gray-600 hover:text-gray-900">Annuler</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

