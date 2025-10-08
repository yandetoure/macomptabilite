<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nouvel Employé</h2>
            <a href="{{ route('employees.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Retour</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom *</label>
                                    <input type="text" name="first_name" id="first_name" required value="{{ old('first_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Nom *</label>
                                    <input type="text" name="last_name" id="last_name" required value="{{ old('last_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700">Poste *</label>
                                    <input type="text" name="position" id="position" required value="{{ old('position') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700">Département</label>
                                    <input type="text" name="department" id="department" value="{{ old('department') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="hire_date" class="block text-sm font-medium text-gray-700">Date d'embauche *</label>
                                    <input type="date" name="hire_date" id="hire_date" required value="{{ old('hire_date') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                                <div>
                                    <label for="base_salary" class="block text-sm font-medium text-gray-700">Salaire de base (FCFA) *</label>
                                    <input type="number" step="0.01" name="base_salary" id="base_salary" required value="{{ old('base_salary') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="social_security_number" class="block text-sm font-medium text-gray-700">Numéro de sécurité sociale</label>
                                <input type="text" name="social_security_number" id="social_security_number" value="{{ old('social_security_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                                <textarea name="address" id="address" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('address') }}</textarea>
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea name="notes" id="notes" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('employees.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Annuler</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Créer l'employé
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

