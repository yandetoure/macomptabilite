<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\JournalEntry;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\AccountingCard;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_customers' => Customer::where('is_active', true)->count(),
            'total_suppliers' => Supplier::where('is_active', true)->count(),
            'pending_customer_invoices' => Invoice::where('type', 'customer')
                ->whereIn('status', ['pending', 'partial'])
                ->sum('total_amount'),
            'pending_supplier_invoices' => Invoice::where('type', 'supplier')
                ->whereIn('status', ['pending', 'partial'])
                ->sum('total_amount'),
            'total_payments_this_month' => Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            'journal_entries_count' => JournalEntry::where('status', 'posted')->count(),
        ];

        // Récupérer les dernières factures
        $recentInvoices = Invoice::with(['customer', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Récupérer les derniers paiements
        $recentPayments = Payment::with('invoice')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Récupérer les cards actives
        $accountingCards = AccountingCard::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('dashboard', compact('stats', 'recentInvoices', 'recentPayments', 'accountingCards'));
    }
}
