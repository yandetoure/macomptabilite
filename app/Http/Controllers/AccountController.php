<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $accounts = Account::orderBy('code')->get();
        
        // Grouper les comptes par classe (premier chiffre)
        $accountsByClass = $accounts->groupBy(function($account) {
            return substr($account->code, 0, 1);
        });

        return view('accounts.index', compact('accounts', 'accountsByClass'));
    }

    public function create(): View
    {
        $parentAccounts = Account::where('is_active', true)->orderBy('code')->get();

        return view('accounts.create', compact('parentAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:accounts,code|max:50',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = true;
        $validated['balance'] = 0;

        Account::create($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Compte créé avec succès');
    }

    public function edit(Account $account): View
    {
        $parentAccounts = Account::where('is_active', true)
            ->where('id', '!=', $account->id)
            ->orderBy('code')
            ->get();

        return view('accounts.edit', compact('account', 'parentAccounts'));
    }

    public function update(Request $request, Account $account): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:accounts,code,' . $account->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Compte mis à jour avec succès');
    }
}
