<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Account;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JournalEntryController extends Controller
{
    protected AccountingService $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    public function index(): View
    {
        $entries = JournalEntry::with(['creator', 'invoice', 'payment'])
            ->orderBy('entry_date', 'desc')
            ->paginate(50);

        return view('journal-entries.index', compact('entries'));
    }

    public function create(): View
    {
        $accounts = Account::where('is_active', true)->orderBy('code')->get();

        return view('journal-entries.create', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
            'lines.*.description' => 'nullable|string',
        ]);

        $entry = $this->accountingService->createJournalEntry($validated);

        return redirect()->route('journal-entries.show', $entry)
            ->with('success', 'Écriture créée avec succès');
    }

    public function show(JournalEntry $journal_entry): View
    {
        $journal_entry->load(['lines.account', 'invoice', 'payment', 'payroll.employee', 'creator']);

        return view('journal-entries.show', ['entry' => $journal_entry]);
    }

    public function journal(): View
    {
        $entries = JournalEntry::with(['lines.account', 'creator'])
            ->where('status', 'posted')
            ->orderBy('entry_date', 'asc')
            ->orderBy('entry_number', 'asc')
            ->get();

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($entries as $entry) {
            $totalDebit += $entry->getTotalDebit();
            $totalCredit += $entry->getTotalCredit();
        }

        return view('journal-entries.journal', compact('entries', 'totalDebit', 'totalCredit'));
    }

    /**
     * Réconcilier une écriture
     */
    public function reconcile(JournalEntry $journal_entry): RedirectResponse
    {
        if ($journal_entry->status !== 'posted') {
            return redirect()->back()
                ->withErrors(['error' => 'Seules les écritures comptabilisées peuvent être réconciliées']);
        }

        if ($journal_entry->is_reconciled) {
            return redirect()->back()
                ->withErrors(['error' => 'Cette écriture est déjà réconciliée']);
        }

        $journal_entry->update([
            'is_reconciled' => true,
            'reconciled_at' => now(),
            'reconciled_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Écriture réconciliée avec succès');
    }

    /**
     * Annuler la réconciliation
     */
    public function unreconcile(JournalEntry $journal_entry): RedirectResponse
    {
        if (!$journal_entry->is_reconciled) {
            return redirect()->back()
                ->withErrors(['error' => 'Cette écriture n\'est pas réconciliée']);
        }

        $journal_entry->update([
            'is_reconciled' => false,
            'reconciled_at' => null,
            'reconciled_by' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Réconciliation annulée avec succès');
    }
}
