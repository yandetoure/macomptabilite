<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollController extends Controller
{
    protected AccountingService $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    public function index(): View
    {
        $payrolls = Payroll::with(['employee', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('payrolls.index', compact('payrolls'));
    }

    public function create(): View
    {
        $employees = Employee::where('is_active', true)->orderBy('employee_number')->get();

        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after_or_equal:pay_period_start',
            'payment_date' => 'required|date',
            'gross_salary' => 'required|numeric|min:0',
            'social_contributions' => 'nullable|numeric|min:0',
            'taxes' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            // Générer le numéro de paie
            $year = Carbon::parse($validated['payment_date'])->year;
            $lastPayroll = Payroll::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            $nextNumber = $lastPayroll ? ((int) substr($lastPayroll->payroll_number, -4)) + 1 : 1;
            $validated['payroll_number'] = "PAY-{$year}-" . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

            // Calculer le salaire net
            $validated['social_contributions'] = $validated['social_contributions'] ?? 0;
            $validated['taxes'] = $validated['taxes'] ?? 0;
            $validated['other_deductions'] = $validated['other_deductions'] ?? 0;
            $validated['net_salary'] = $validated['gross_salary'] - $validated['social_contributions'] - $validated['taxes'] - $validated['other_deductions'];
            $validated['status'] = 'draft';
            $validated['created_by'] = auth()->id();

            $payroll = Payroll::create($validated);

            return redirect()->route('payrolls.show', $payroll)
                ->with('success', 'Fiche de paie créée avec succès');
        });
    }

    public function show(Payroll $payroll): View
    {
        $payroll->load(['employee', 'items', 'creator', 'journalEntries.lines.account']);

        return view('payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll): View
    {
        $employees = Employee::where('is_active', true)->orderBy('employee_number')->get();

        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after_or_equal:pay_period_start',
            'payment_date' => 'required|date',
            'gross_salary' => 'required|numeric|min:0',
            'social_contributions' => 'nullable|numeric|min:0',
            'taxes' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Recalculer le salaire net
        $validated['social_contributions'] = $validated['social_contributions'] ?? 0;
        $validated['taxes'] = $validated['taxes'] ?? 0;
        $validated['other_deductions'] = $validated['other_deductions'] ?? 0;
        $validated['net_salary'] = $validated['gross_salary'] - $validated['social_contributions'] - $validated['taxes'] - $validated['other_deductions'];

        $payroll->update($validated);

        return redirect()->route('payrolls.show', $payroll)
            ->with('success', 'Fiche de paie mise à jour avec succès');
    }

    public function destroy(Payroll $payroll): RedirectResponse
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Fiche de paie supprimée avec succès');
    }

    /**
     * Valider et générer l'écriture comptable
     */
    public function validate(Payroll $payroll): RedirectResponse
    {
        if ($payroll->status === 'validated' || $payroll->status === 'paid') {
            return redirect()->back()->with('error', 'Cette fiche de paie est déjà validée');
        }

        try {
            // Créer l'écriture comptable
            $this->createPayrollEntry($payroll);
            
            $payroll->update(['status' => 'validated']);

            return redirect()->back()->with('success', 'Fiche de paie validée et écriture comptable générée');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la validation : ' . $e->getMessage()]);
        }
    }

    /**
     * Créer l'écriture comptable pour la paie
     */
    protected function createPayrollEntry(Payroll $payroll): void
    {
        // Compte 641 - Salaires (Charges)
        // Compte 421 - Personnel - Rémunérations dues (Passif)
        // Compte 431 - Sécurité sociale (Passif)
        // Compte 447 - État - Impôts et taxes (Passif)

        $salaryAccount = \App\Models\Account::firstOrCreate(
            ['code' => '641'],
            ['name' => 'Rémunérations du personnel', 'type' => 'expense', 'is_active' => true, 'balance' => 0]
        );

        $personnelAccount = \App\Models\Account::firstOrCreate(
            ['code' => '421'],
            ['name' => 'Personnel - Rémunérations dues', 'type' => 'liability', 'is_active' => true, 'balance' => 0]
        );

        $socialAccount = \App\Models\Account::firstOrCreate(
            ['code' => '431'],
            ['name' => 'Sécurité sociale', 'type' => 'liability', 'is_active' => true, 'balance' => 0]
        );

        $taxAccount = \App\Models\Account::firstOrCreate(
            ['code' => '447'],
            ['name' => 'État - Impôts et taxes', 'type' => 'liability', 'is_active' => true, 'balance' => 0]
        );

        $lines = [
            // Débit : Charge salariale (salaire brut)
            [
                'account_id' => $salaryAccount->id,
                'debit' => $payroll->gross_salary,
                'credit' => 0,
                'description' => 'Salaire brut',
            ],
            // Crédit : Salaire net à payer
            [
                'account_id' => $personnelAccount->id,
                'debit' => 0,
                'credit' => $payroll->net_salary,
                'description' => 'Salaire net à payer',
            ],
        ];

        // Ajouter cotisations sociales si > 0
        if ($payroll->social_contributions > 0) {
            $lines[] = [
                'account_id' => $socialAccount->id,
                'debit' => 0,
                'credit' => $payroll->social_contributions,
                'description' => 'Cotisations sociales',
            ];
        }

        // Ajouter impôts si > 0
        if ($payroll->taxes > 0) {
            $lines[] = [
                'account_id' => $taxAccount->id,
                'debit' => 0,
                'credit' => $payroll->taxes,
                'description' => 'Impôts sur salaires',
            ];
        }

        // Autres retenues
        if ($payroll->other_deductions > 0) {
            $lines[] = [
                'account_id' => $personnelAccount->id,
                'debit' => 0,
                'credit' => $payroll->other_deductions,
                'description' => 'Autres retenues',
            ];
        }

        // Créer l'écriture
        $entry = $this->accountingService->createJournalEntry([
            'entry_date' => $payroll->payment_date,
            'reference' => $payroll->payroll_number,
            'description' => "Paie {$payroll->employee->full_name} - " . $payroll->pay_period_start->format('m/Y'),
            'lines' => $lines,
        ]);

        // Lier l'écriture à la paie
        $entry->update(['payroll_id' => $payroll->id]);
    }
}
