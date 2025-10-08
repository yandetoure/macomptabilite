<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::withCount('payrolls')
            ->orderBy('employee_number')
            ->paginate(20);

        return view('employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('employees.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'hire_date' => 'required|date',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'base_salary' => 'required|numeric|min:0',
            'address' => 'nullable|string',
            'social_security_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        // Générer le matricule
        $lastEmployee = Employee::orderBy('id', 'desc')->first();
        $nextNumber = $lastEmployee ? ((int) substr($lastEmployee->employee_number, -4)) + 1 : 1;
        $validated['employee_number'] = 'EMP-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        $validated['is_active'] = true;

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employé créé avec succès');
    }

    public function show(Employee $employee): View
    {
        $employee->load(['payrolls' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'hire_date' => 'required|date',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'base_salary' => 'required|numeric|min:0',
            'address' => 'nullable|string',
            'social_security_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employé mis à jour avec succès');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employé supprimé avec succès');
    }
}
