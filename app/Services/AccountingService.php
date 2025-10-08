<?php declare(strict_types=1);

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountingService
{
    /**
     * Créer une écriture comptable en partie double
     */
    public function createJournalEntry(array $data): JournalEntry
    {
        return DB::transaction(function () use ($data) {
            // Générer un numéro d'écriture unique
            $entryNumber = $this->generateEntryNumber();

            $entry = JournalEntry::create([
                'entry_number' => $entryNumber,
                'entry_date' => $data['entry_date'] ?? now(),
                'reference' => $data['reference'] ?? null,
                'description' => $data['description'] ?? null,
                'status' => 'draft',
                'created_by' => auth()->id(),
                'invoice_id' => $data['invoice_id'] ?? null,
                'payment_id' => $data['payment_id'] ?? null,
            ]);

            // Créer les lignes d'écriture
            if (isset($data['lines']) && is_array($data['lines'])) {
                foreach ($data['lines'] as $line) {
                    $entry->lines()->create([
                        'account_id' => $line['account_id'],
                        'debit' => $line['debit'] ?? 0,
                        'credit' => $line['credit'] ?? 0,
                        'description' => $line['description'] ?? null,
                    ]);
                }
            }

            // Vérifier et mettre à jour l'équilibre
            $this->updateEntryBalance($entry);

            return $entry->fresh('lines.account');
        });
    }

    /**
     * Créer une écriture à partir d'une facture client
     */
    public function createEntryFromCustomerInvoice(Invoice $invoice): JournalEntry
    {
        // Client au débit (411), Ventes au crédit (701)
        $customerAccount = Account::where('code', '411')->first();
        $salesAccount = Account::where('code', '701')->first();

        if (!$customerAccount || !$salesAccount) {
            throw new \Exception('Comptes comptables non configurés (411, 701)');
        }

        return $this->createJournalEntry([
            'entry_date' => $invoice->invoice_date,
            'reference' => $invoice->invoice_number,
            'description' => "Facture client {$invoice->invoice_number} - {$invoice->party_name}",
            'invoice_id' => $invoice->id,
            'lines' => [
                [
                    'account_id' => $customerAccount->id,
                    'debit' => $invoice->total_amount,
                    'credit' => 0,
                    'description' => 'Client',
                ],
                [
                    'account_id' => $salesAccount->id,
                    'debit' => 0,
                    'credit' => $invoice->total_amount,
                    'description' => 'Vente',
                ],
            ],
        ]);
    }

    /**
     * Créer une écriture à partir d'une facture fournisseur
     */
    public function createEntryFromSupplierInvoice(Invoice $invoice): JournalEntry
    {
        // Achats au débit (601), Fournisseur au crédit (401)
        $purchaseAccount = Account::where('code', '601')->first();
        $supplierAccount = Account::where('code', '401')->first();

        if (!$purchaseAccount || !$supplierAccount) {
            throw new \Exception('Comptes comptables non configurés (601, 401)');
        }

        return $this->createJournalEntry([
            'entry_date' => $invoice->invoice_date,
            'reference' => $invoice->invoice_number,
            'description' => "Facture fournisseur {$invoice->invoice_number} - {$invoice->party_name}",
            'invoice_id' => $invoice->id,
            'lines' => [
                [
                    'account_id' => $purchaseAccount->id,
                    'debit' => $invoice->total_amount,
                    'credit' => 0,
                    'description' => 'Achat',
                ],
                [
                    'account_id' => $supplierAccount->id,
                    'debit' => 0,
                    'credit' => $invoice->total_amount,
                    'description' => 'Fournisseur',
                ],
            ],
        ]);
    }

    /**
     * Créer une écriture pour un avoir client (inversion de la facture)
     */
    public function createEntryFromCustomerCreditNote(Invoice $invoice): JournalEntry
    {
        // Ventes au débit (701), Client au crédit (411) - INVERSÉ
        $customerAccount = Account::where('code', '411')->first();
        $salesAccount = Account::where('code', '701')->first();

        if (!$customerAccount || !$salesAccount) {
            throw new \Exception('Comptes comptables non configurés (411, 701)');
        }

        return $this->createJournalEntry([
            'entry_date' => $invoice->invoice_date,
            'reference' => $invoice->invoice_number,
            'description' => "Avoir client {$invoice->invoice_number} - {$invoice->party_name}",
            'invoice_id' => $invoice->id,
            'lines' => [
                [
                    'account_id' => $salesAccount->id,
                    'debit' => $invoice->total_amount,
                    'credit' => 0,
                    'description' => 'Annulation vente',
                ],
                [
                    'account_id' => $customerAccount->id,
                    'debit' => 0,
                    'credit' => $invoice->total_amount,
                    'description' => 'Client - Avoir',
                ],
            ],
        ]);
    }

    /**
     * Créer une écriture pour un avoir fournisseur (inversion de la facture)
     */
    public function createEntryFromSupplierCreditNote(Invoice $invoice): JournalEntry
    {
        // Fournisseur au débit (401), Achats au crédit (601) - INVERSÉ
        $purchaseAccount = Account::where('code', '601')->first();
        $supplierAccount = Account::where('code', '401')->first();

        if (!$purchaseAccount || !$supplierAccount) {
            throw new \Exception('Comptes comptables non configurés (601, 401)');
        }

        return $this->createJournalEntry([
            'entry_date' => $invoice->invoice_date,
            'reference' => $invoice->invoice_number,
            'description' => "Avoir fournisseur {$invoice->invoice_number} - {$invoice->party_name}",
            'invoice_id' => $invoice->id,
            'lines' => [
                [
                    'account_id' => $supplierAccount->id,
                    'debit' => $invoice->total_amount,
                    'credit' => 0,
                    'description' => 'Fournisseur - Avoir',
                ],
                [
                    'account_id' => $purchaseAccount->id,
                    'debit' => 0,
                    'credit' => $invoice->total_amount,
                    'description' => 'Annulation achat',
                ],
            ],
        ]);
    }

    /**
     * Créer une écriture de paiement
     */
    public function createEntryFromPayment(Payment $payment, int $debitAccountId, int $creditAccountId): JournalEntry
    {
        $description = "Paiement {$payment->payment_method} - {$payment->payment_number}";
        
        if ($payment->invoice) {
            $description .= " - Facture {$payment->invoice->invoice_number}";
        }

        return $this->createJournalEntry([
            'entry_date' => $payment->payment_date,
            'reference' => $payment->payment_number,
            'description' => $description,
            'payment_id' => $payment->id,
            'invoice_id' => $payment->invoice_id,
            'lines' => [
                [
                    'account_id' => $debitAccountId,
                    'debit' => $payment->amount,
                    'credit' => 0,
                ],
                [
                    'account_id' => $creditAccountId,
                    'debit' => 0,
                    'credit' => $payment->amount,
                ],
            ],
        ]);
    }

    /**
     * Créer une écriture personnalisée depuis une card
     */
    public function createEntryFromCard(array $data, int $debitAccountId, int $creditAccountId): JournalEntry
    {
        return $this->createJournalEntry([
            'entry_date' => $data['date'] ?? now(),
            'reference' => $data['reference'] ?? null,
            'description' => $data['description'] ?? null,
            'lines' => [
                [
                    'account_id' => $debitAccountId,
                    'debit' => $data['amount'],
                    'credit' => 0,
                ],
                [
                    'account_id' => $creditAccountId,
                    'debit' => 0,
                    'credit' => $data['amount'],
                ],
            ],
        ]);
    }

    /**
     * Mettre à jour le statut d'équilibre d'une écriture
     */
    public function updateEntryBalance(JournalEntry $entry): void
    {
        $totalDebit = $entry->lines()->sum('debit');
        $totalCredit = $entry->lines()->sum('credit');
        
        $isBalanced = abs($totalDebit - $totalCredit) < 0.01; // Tolérance de 1 centime
        
        $entry->update([
            'is_balanced' => $isBalanced,
            'status' => $isBalanced ? 'posted' : 'draft',
        ]);

        // Mettre à jour les soldes des comptes si l'écriture est comptabilisée
        if ($isBalanced && $entry->status === 'posted') {
            $this->updateAccountBalances($entry);
        }
    }

    /**
     * Mettre à jour les soldes des comptes
     */
    protected function updateAccountBalances(JournalEntry $entry): void
    {
        foreach ($entry->lines as $line) {
            $account = $line->account;
            
            // Calcul du solde selon le type de compte
            if (in_array($account->type, ['asset', 'expense'])) {
                // Actif et Charges: débit augmente, crédit diminue
                $account->balance = $account->balance + $line->debit - $line->credit;
            } else {
                // Passif, Capitaux propres et Produits: crédit augmente, débit diminue
                $account->balance = $account->balance + $line->credit - $line->debit;
            }
            
            $account->save();
        }
    }

    /**
     * Générer un numéro d'écriture unique
     */
    protected function generateEntryNumber(): string
    {
        $year = Carbon::now()->year;
        $lastEntry = JournalEntry::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastEntry ? ((int) substr($lastEntry->entry_number, -4)) + 1 : 1;

        return "JE-{$year}-" . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Générer un numéro de facture unique
     */
    public function generateInvoiceNumber(string $type): string
    {
        $year = Carbon::now()->year;
        $prefix = $type === 'customer' ? 'FC' : 'FF';
        
        $lastInvoice = Invoice::where('type', $type)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, -4)) + 1 : 1;

        return "{$prefix}-{$year}-" . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Générer un numéro de paiement unique
     */
    public function generatePaymentNumber(): string
    {
        $year = Carbon::now()->year;
        
        $lastPayment = Payment::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastPayment ? ((int) substr($lastPayment->payment_number, -4)) + 1 : 1;

        return "PAY-{$year}-" . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

