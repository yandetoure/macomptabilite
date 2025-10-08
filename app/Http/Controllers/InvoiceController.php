<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Payment;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    protected AccountingService $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    public function index(Request $request): View
    {
        $query = Invoice::with(['customer', 'supplier', 'creator']);

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('invoices.create', compact('customers', 'suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:customer,supplier',
            'is_credit_note' => 'nullable|boolean',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'party_name' => 'nullable|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Remplir party_name depuis customer ou supplier si non fourni
        if (empty($validated['party_name'])) {
            if ($validated['type'] === 'customer' && !empty($validated['customer_id'])) {
                $customer = Customer::find($validated['customer_id']);
                $validated['party_name'] = $customer ? $customer->name : 'Client';
            } elseif ($validated['type'] === 'supplier' && !empty($validated['supplier_id'])) {
                $supplier = Supplier::find($validated['supplier_id']);
                $validated['party_name'] = $supplier ? $supplier->name : 'Fournisseur';
            } else {
                $validated['party_name'] = $validated['type'] === 'customer' ? 'Client' : 'Fournisseur';
            }
        }

        // Gérer is_credit_note
        $validated['is_credit_note'] = $request->has('is_credit_note') ? true : false;

        // Générer le numéro de facture
        $prefix = $validated['is_credit_note'] ? 'AV' : ($validated['type'] === 'customer' ? 'FC' : 'FF');
        $year = now()->year;
        $lastInvoice = Invoice::where('type', $validated['type'])
            ->where('is_credit_note', $validated['is_credit_note'])
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        $nextNumber = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, -4)) + 1 : 1;
        $validated['invoice_number'] = "{$prefix}-{$year}-" . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'pending';

        // Upload du fichier
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('invoices', 'public');
            $validated['file_path'] = $path;
        }

        $invoice = Invoice::create($validated);

        // Créer l'écriture comptable automatiquement
        try {
            if ($invoice->is_credit_note) {
                // Pour les avoirs, on inverse les écritures
                if ($invoice->type === 'customer') {
                    $this->accountingService->createEntryFromCustomerCreditNote($invoice);
                } else {
                    $this->accountingService->createEntryFromSupplierCreditNote($invoice);
                }
            } else {
                // Facture normale
                if ($invoice->type === 'customer') {
                    $this->accountingService->createEntryFromCustomerInvoice($invoice);
                } else {
                    $this->accountingService->createEntryFromSupplierInvoice($invoice);
                }
            }
        } catch (\Exception $e) {
            // Log l'erreur mais continue
            logger()->error('Erreur lors de la création de l\'écriture comptable: ' . $e->getMessage());
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'supplier', 'payments', 'journalEntries.lines.account']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('invoices.edit', compact('invoice', 'customers', 'suppliers'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:customer,supplier',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'party_name' => 'nullable|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Upload du fichier
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            if ($invoice->file_path) {
                Storage::disk('public')->delete($invoice->file_path);
            }
            $path = $request->file('file')->store('invoices', 'public');
            $validated['file_path'] = $path;
        }

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture mise à jour avec succès');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        if ($invoice->file_path) {
            Storage::disk('public')->delete($invoice->file_path);
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Facture supprimée avec succès');
    }

    public function markPaid(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank,check,transfer,other',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Créer le paiement
        $paymentNumber = $this->accountingService->generatePaymentNumber();

        $payment = Payment::create([
            'payment_number' => $paymentNumber,
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'invoice_id' => $invoice->id,
            'reference' => $validated['reference'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Mettre à jour le montant payé de la facture
        $invoice->paid_amount += $validated['amount'];
        
        if ($invoice->paid_amount >= $invoice->total_amount) {
            $invoice->status = 'paid';
        } elseif ($invoice->paid_amount > 0) {
            $invoice->status = 'partial';
        }
        
        $invoice->save();

        // Créer l'écriture comptable de paiement
        try {
            // Déterminer les comptes selon le type de facture et méthode de paiement
            if ($invoice->type === 'customer') {
                // Client paie: Banque/Caisse au débit, Client au crédit
                $debitAccountCode = $validated['payment_method'] === 'cash' ? '531' : '512';
                $creditAccountCode = '411';
            } else {
                // On paie fournisseur: Fournisseur au débit, Banque/Caisse au crédit
                $debitAccountCode = '401';
                $creditAccountCode = $validated['payment_method'] === 'cash' ? '531' : '512';
            }

            $debitAccount = \App\Models\Account::where('code', $debitAccountCode)->first();
            $creditAccount = \App\Models\Account::where('code', $creditAccountCode)->first();

            if (!$debitAccount || !$creditAccount) {
                logger()->error("Comptes comptables manquants: Débit=$debitAccountCode, Crédit=$creditAccountCode");
                return redirect()->route('invoices.show', $invoice)
                    ->withErrors(['error' => "Les comptes comptables nécessaires ($debitAccountCode, $creditAccountCode) n'existent pas. Veuillez les créer dans le plan comptable."]);
            }

            $entry = $this->accountingService->createEntryFromPayment(
                $payment,
                $debitAccount->id,
                $creditAccount->id
            );
            
            logger()->info("Écriture comptable créée: {$entry->entry_number}");
        } catch (\Exception $e) {
            logger()->error('Erreur lors de la création de l\'écriture de paiement: ' . $e->getMessage());
            return redirect()->route('invoices.show', $invoice)
                ->withErrors(['error' => 'Erreur lors de la création de l\'écriture comptable: ' . $e->getMessage()]);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Paiement enregistré avec succès');
    }

    /**
     * Annuler le paiement d'une facture
     */
    public function cancelPayment(Request $request, Invoice $invoice): RedirectResponse
    {
        if ($invoice->status !== 'paid' && $invoice->status !== 'partial') {
            return redirect()->back()
                ->withErrors(['error' => 'Cette facture n\'est pas marquée comme payée']);
        }

        try {
            \DB::transaction(function () use ($invoice) {
                // Supprimer tous les paiements liés
                $payments = $invoice->payments;
                
                foreach ($payments as $payment) {
                    // Supprimer les écritures comptables liées au paiement
                    $journalEntries = $payment->journalEntries;
                    
                    foreach ($journalEntries as $entry) {
                        // Inverser les soldes des comptes avant de supprimer
                        foreach ($entry->lines as $line) {
                            $account = $line->account;
                            
                            if (in_array($account->type, ['asset', 'expense'])) {
                                $account->balance = $account->balance - $line->debit + $line->credit;
                            } else {
                                $account->balance = $account->balance - $line->credit + $line->debit;
                            }
                            
                            $account->save();
                        }
                        
                        // Supprimer l'écriture
                        $entry->delete();
                    }
                    
                    // Supprimer le paiement
                    $payment->delete();
                }
                
                // Remettre le statut de la facture à pending
                $invoice->update([
                    'paid_amount' => 0,
                    'status' => 'pending',
                ]);
            });

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Paiement annulé et écritures comptables supprimées');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de l\'annulation : ' . $e->getMessage()]);
        }
    }

    public function download(Invoice $invoice)
    {
        if (!$invoice->file_path || !Storage::disk('public')->exists($invoice->file_path)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::disk('public')->download($invoice->file_path);
    }
}
