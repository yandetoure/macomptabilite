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
        // Récupérer les IDs des comptes concernés par des écritures réconciliées
        $reconciledAccountIds = \App\Models\JournalEntryLine::whereHas('journalEntry', function($q) {
            $q->where('is_reconciled', true);
        })->pluck('account_id')->unique();

        // Actifs (seulement ceux avec solde non nul et non réconciliés)
        $assets = Account::where('type', 'asset')
            ->where('is_active', true)
            ->where('balance', '!=', 0)
            ->whereNotIn('id', $reconciledAccountIds)
            ->orderBy('code')
            ->get();
        $totalAssets = $assets->sum('balance');

        // Passifs (seulement ceux avec solde non nul et non réconciliés)
        $liabilities = Account::where('type', 'liability')
            ->where('is_active', true)
            ->where('balance', '!=', 0)
            ->whereNotIn('id', $reconciledAccountIds)
            ->orderBy('code')
            ->get();
        $totalLiabilities = $liabilities->sum('balance');

        // Capitaux propres (seulement ceux avec solde non nul et non réconciliés)
        $equity = Account::where('type', 'equity')
            ->where('is_active', true)
            ->where('balance', '!=', 0)
            ->whereNotIn('id', $reconciledAccountIds)
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
        // Récupérer les IDs des comptes concernés par des écritures réconciliées
        $reconciledAccountIds = \App\Models\JournalEntryLine::whereHas('journalEntry', function($q) {
            $q->where('is_reconciled', true);
        })->pluck('account_id')->unique();

        // Produits (Revenues) - seulement ceux avec solde non nul et non réconciliés
        $revenues = Account::where('type', 'revenue')
            ->where('is_active', true)
            ->where('balance', '!=', 0)
            ->whereNotIn('id', $reconciledAccountIds)
            ->orderBy('code')
            ->get();
        $totalRevenues = $revenues->sum('balance');

        // Charges (Expenses) - seulement ceux avec solde non nul et non réconciliés
        $expenses = Account::where('type', 'expense')
            ->where('is_active', true)
            ->where('balance', '!=', 0)
            ->whereNotIn('id', $reconciledAccountIds)
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
        $accounts = Account::where('is_active', true)
            ->orderBy('code')
            ->get();
        
        // Calculer les totaux débit/crédit pour chaque compte à partir des lignes d'écriture
        foreach ($accounts as $account) {
            $totalDebit = \App\Models\JournalEntryLine::whereHas('journalEntry', function($q) {
                $q->where('status', 'posted');
            })
            ->where('account_id', $account->id)
            ->sum('debit');
            
            $totalCredit = \App\Models\JournalEntryLine::whereHas('journalEntry', function($q) {
                $q->where('status', 'posted');
            })
            ->where('account_id', $account->id)
            ->sum('credit');
            
            $account->total_debit = $totalDebit;
            $account->total_credit = $totalCredit;
            $account->solde = $totalCredit - $totalDebit; // Crédit - Débit
        }
        
        $accountsByType = $accounts->groupBy('type');
        
        $totalDebit = $accounts->sum('total_debit');
        $totalCredit = $accounts->sum('total_credit');

        return view('reports.trial-balance', compact('accounts', 'accountsByType', 'totalDebit', 'totalCredit'));
    }

    public function cashFlow(): View
    {
        // Comptes de trésorerie (Banque et Caisse)
        $cashAccounts = Account::whereIn('code', ['512', '531'])
            ->where('is_active', true)
            ->get();

        // Solde de trésorerie actuel
        $currentCash = $cashAccounts->sum('balance');

        // Mouvements du mois en cours
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Entrées (crédits sur comptes de trésorerie)
        $cashInflows = \App\Models\JournalEntryLine::whereHas('journalEntry', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->where('status', 'posted')
                  ->whereBetween('entry_date', [$startOfMonth, $endOfMonth]);
        })
        ->whereIn('account_id', $cashAccounts->pluck('id'))
        ->where('debit', '>', 0)
        ->with('journalEntry')
        ->orderBy('created_at', 'desc')
        ->get();

        // Sorties (débits sur comptes de trésorerie - négatif car c'est une sortie)
        $cashOutflows = \App\Models\JournalEntryLine::whereHas('journalEntry', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->where('status', 'posted')
                  ->whereBetween('entry_date', [$startOfMonth, $endOfMonth]);
        })
        ->whereIn('account_id', $cashAccounts->pluck('id'))
        ->where('credit', '>', 0)
        ->with('journalEntry')
        ->orderBy('created_at', 'desc')
        ->get();

        $totalInflows = $cashInflows->sum('debit');
        $totalOutflows = $cashOutflows->sum('credit');
        $netCashFlow = $totalInflows - $totalOutflows;

        return view('reports.cash-flow', compact(
            'cashAccounts', 'currentCash',
            'cashInflows', 'cashOutflows',
            'totalInflows', 'totalOutflows', 'netCashFlow'
        ));
    }

    public function generalLedger(Request $request): View
    {
        // Filtrer les écritures non réconciliées
        $showReconciled = $request->get('show_reconciled', false);
        
        $accounts = Account::where('is_active', true)
            ->orderBy('code')
            ->get();

        // Pour chaque compte, récupérer ses mouvements
        foreach ($accounts as $account) {
            $query = \App\Models\JournalEntryLine::whereHas('journalEntry', function($q) use ($showReconciled) {
                $q->where('status', 'posted');
                if (!$showReconciled) {
                    $q->where('is_reconciled', false);
                }
            })
            ->where('account_id', $account->id)
            ->with(['journalEntry' => function($q) {
                $q->orderBy('entry_date', 'asc')->orderBy('entry_number', 'asc');
            }])
            ->get();

            $account->movements = $query;
            
            // Calculer le solde progressif
            $balance = 0;
            foreach ($account->movements as $movement) {
                if (in_array($account->type, ['asset', 'expense'])) {
                    $balance += $movement->debit - $movement->credit;
                } else {
                    $balance += $movement->credit - $movement->debit;
                }
                $movement->running_balance = $balance;
            }
        }

        // Filtrer les comptes qui ont des mouvements
        $accounts = $accounts->filter(function($account) {
            return $account->movements->count() > 0;
        });

        return view('reports.general-ledger', compact('accounts', 'showReconciled'));
    }
}
