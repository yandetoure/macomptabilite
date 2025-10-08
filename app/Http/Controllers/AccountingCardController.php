<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AccountingCard;
use App\Models\Account;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountingCardController extends Controller
{
    protected AccountingService $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    public function index(): View
    {
        $cards = AccountingCard::with(['debitAccount', 'creditAccount'])
            ->orderBy('order')
            ->get();

        return view('cards.index', compact('cards'));
    }

    public function create(): View
    {
        $accounts = Account::where('is_active', true)->orderBy('code')->get();

        return view('cards.create', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:invoice_customer,invoice_supplier,cash,bank,custom',
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|max:7',
            'debit_account_id' => 'required|exists:accounts,id',
            'credit_account_id' => 'required|exists:accounts,id',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = true;
        $validated['order'] = $validated['order'] ?? AccountingCard::max('order') + 1;

        AccountingCard::create($validated);

        return redirect()->route('cards.index')
            ->with('success', 'Card créée avec succès');
    }

    public function show(AccountingCard $card): View
    {
        $card->load(['debitAccount', 'creditAccount']);

        return view('cards.show', compact('card'));
    }

    public function edit(AccountingCard $card): View
    {
        $accounts = Account::where('is_active', true)->orderBy('code')->get();

        return view('cards.edit', compact('card', 'accounts'));
    }

    public function update(Request $request, AccountingCard $card): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:invoice_customer,invoice_supplier,cash,bank,custom',
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|max:7',
            'debit_account_id' => 'required|exists:accounts,id',
            'credit_account_id' => 'required|exists:accounts,id',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $card->update($validated);

        return redirect()->route('cards.index')
            ->with('success', 'Card mise à jour avec succès');
    }

    public function destroy(AccountingCard $card): RedirectResponse
    {
        $card->delete();

        return redirect()->route('cards.index')
            ->with('success', 'Card supprimée avec succès');
    }

    /**
     * Créer une transaction depuis une card
     */
    public function createTransaction(Request $request, AccountingCard $card): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $this->accountingService->createEntryFromCard(
                $validated,
                $card->debit_account_id,
                $card->credit_account_id
            );

            return redirect()->back()
                ->with('success', 'Transaction enregistrée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la création de la transaction: ' . $e->getMessage()]);
        }
    }
}
