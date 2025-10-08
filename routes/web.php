<?php declare(strict_types=1); 

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AccountingCardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Plan comptable (Accounts)
    Route::resource('accounts', AccountController::class);
    
    // Écritures comptables (Journal Entries)
    Route::get('/journal-entries/journal/view', [JournalEntryController::class, 'journal'])->name('journal-entries.journal');
    Route::resource('journal-entries', JournalEntryController::class);
    Route::post('/journal-entries/{entry}/post', [JournalEntryController::class, 'post'])->name('journal-entries.post');
    Route::post('/journal-entries/{journal_entry}/reconcile', [JournalEntryController::class, 'reconcile'])->name('journal-entries.reconcile');
    Route::post('/journal-entries/{journal_entry}/unreconcile', [JournalEntryController::class, 'unreconcile'])->name('journal-entries.unreconcile');
    
    // Factures (Invoices)
    Route::resource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
    Route::post('/invoices/{invoice}/cancel-payment', [InvoiceController::class, 'cancelPayment'])->name('invoices.cancel-payment');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    
    // Paiements (Payments)
    Route::resource('payments', PaymentController::class);
    
    // Cards comptables
    Route::resource('cards', AccountingCardController::class);
    Route::post('/cards/{card}/transaction', [AccountingCardController::class, 'createTransaction'])->name('cards.transaction');
    
    // Clients
    Route::resource('customers', CustomerController::class);
    
    // Fournisseurs
    Route::resource('suppliers', SupplierController::class);
    
    // Employés et Paie
    Route::resource('employees', EmployeeController::class);
    Route::resource('payrolls', PayrollController::class);
    Route::post('/payrolls/{payroll}/validate', [PayrollController::class, 'validate'])->name('payrolls.validate');
    
    // Rapports
    Route::get('/reports/trial-balance', [ReportController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('/reports/balance-sheet', [ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
    Route::get('/reports/financial-statement', [ReportController::class, 'financialStatement'])->name('reports.financial-statement');
    Route::get('/reports/cash-flow', [ReportController::class, 'cashFlow'])->name('reports.cash-flow');
    Route::get('/reports/general-ledger', [ReportController::class, 'generalLedger'])->name('reports.general-ledger');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
