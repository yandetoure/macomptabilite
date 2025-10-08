<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouvelle Écriture Comptable
            </h2>
            <a href="{{ route('journal-entries.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('journal-entries.store') }}" method="POST">
                        @csrf

                        <div class="space-y-4 mb-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="entry_date" class="block text-sm font-medium text-gray-700">Date *</label>
                                    <input type="date" name="entry_date" id="entry_date" required value="{{ old('entry_date', date('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>

                                <div>
                                    <label for="reference" class="block text-sm font-medium text-gray-700">Référence</label>
                                    <input type="text" name="reference" id="reference" value="{{ old('reference') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <!-- Lignes d'écriture -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Lignes d'écriture</h4>
                            <div id="entry-lines" class="space-y-3">
                                <!-- Ligne 1 -->
                                <div class="grid grid-cols-12 gap-3 entry-line border border-gray-200 p-3 rounded">
                                    <div class="col-span-5">
                                        <select name="lines[0][account_id]" required class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                            <option value="">Sélectionner un compte...</option>
                                            @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-3">
                                        <input type="number" step="0.01" name="lines[0][debit]" placeholder="Débit" required
                                            class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                    </div>
                                    <div class="col-span-3">
                                        <input type="number" step="0.01" name="lines[0][credit]" placeholder="Crédit" required
                                            class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                    </div>
                                    <div class="col-span-1 flex items-center">
                                        <button type="button" onclick="this.closest('.entry-line').remove(); updateLineNumbers();" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Ligne 2 -->
                                <div class="grid grid-cols-12 gap-3 entry-line border border-gray-200 p-3 rounded">
                                    <div class="col-span-5">
                                        <select name="lines[1][account_id]" required class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                            <option value="">Sélectionner un compte...</option>
                                            @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-3">
                                        <input type="number" step="0.01" name="lines[1][debit]" placeholder="Débit" required
                                            class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                    </div>
                                    <div class="col-span-3">
                                        <input type="number" step="0.01" name="lines[1][credit]" placeholder="Crédit" required
                                            class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                    </div>
                                    <div class="col-span-1 flex items-center">
                                        <button type="button" onclick="this.closest('.entry-line').remove(); updateLineNumbers();" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" onclick="addLine()" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                + Ajouter une ligne
                            </button>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('journal-entries.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Créer l'écriture
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lineCount = 2;

        function addLine() {
            const container = document.getElementById('entry-lines');
            const newLine = `
                <div class="grid grid-cols-12 gap-3 entry-line border border-gray-200 p-3 rounded">
                    <div class="col-span-5">
                        <select name="lines[${lineCount}][account_id]" required class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                            <option value="">Sélectionner un compte...</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-3">
                        <input type="number" step="0.01" name="lines[${lineCount}][debit]" placeholder="Débit" required
                            class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                    </div>
                    <div class="col-span-3">
                        <input type="number" step="0.01" name="lines[${lineCount}][credit]" placeholder="Crédit" required
                            class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                    </div>
                    <div class="col-span-1 flex items-center">
                        <button type="button" onclick="this.closest('.entry-line').remove(); updateLineNumbers();" class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newLine);
            lineCount++;
        }

        function updateLineNumbers() {
            const lines = document.querySelectorAll('.entry-line');
            lines.forEach((line, index) => {
                const inputs = line.querySelectorAll('select, input[type="number"]');
                inputs.forEach(input => {
                    const name = input.name.replace(/lines\[\d+\]/, `lines[${index}]`);
                    input.name = name;
                });
            });
            lineCount = lines.length;
        }
    </script>
</x-app-layout>

