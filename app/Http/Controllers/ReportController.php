<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function balanceSheet(): View
    {
        // Actifs
        $assets = Account::where('type', 'asset')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();
        $totalAssets = $assets->sum('balance');

        // Passifs
        $liabilities = Account::where('type', 'liability')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();
        $totalLiabilities = $liabilities->sum('balance');

        // Capitaux propres
        $equity = Account::where('type', 'equity')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();
        $totalEquity = $equity->sum('balance');

        return view('reports.balance-sheet', compact(
            'assets', 'totalAssets',
            'liabilities', 'totalLiabilities',
            'equity', 'totalEquity'
        ));
    }

    public function financialStatement(): View
    {
        // Produits (Revenues)
        $revenues = Account::where('type', 'revenue')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();
        $totalRevenues = $revenues->sum('balance');

        // Charges (Expenses)
        $expenses = Account::where('type', 'expense')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();
        $totalExpenses = $expenses->sum('balance');

        // Résultat
        $result = $totalRevenues - $totalExpenses;

        return view('reports.financial-statement', compact(
            'revenues', 'totalRevenues',
            'expenses', 'totalExpenses',
            'result'
        ));
    }

    public function trialBalance(): View
    {
        // Tous les comptes actifs avec leurs soldes
        $accounts = Account::where('is_active', true)
            ->orderBy('code')
            ->get();

        // Grouper par type
        $accountsByType = $accounts->groupBy('type');

        // Calculer les totaux
        $totalDebit = $accounts->filter(fn($a) => $a->balance > 0)->sum('balance');
        $totalCredit = $accounts->filter(fn($a) => $a->balance < 0)->sum(fn($a) => abs($a->balance));

        return view('reports.trial-balance', compact('accounts', 'accountsByType', 'totalDebit', 'totalCredit'));
    }
}
