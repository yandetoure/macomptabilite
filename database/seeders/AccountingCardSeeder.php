<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountingCard;
use App\Models\Account;

class AccountingCardSeeder extends Seeder
{
    public function run(): void
    {
        $banque = Account::where('code', '512')->first();
        $caisse = Account::where('code', '531')->first();
        $clients = Account::where('code', '411')->first();
        $fournisseurs = Account::where('code', '401')->first();
        
        if ($banque && $clients) {
            AccountingCard::create([
                'name' => 'Encaissement Banque',
                'type' => 'bank',
                'icon' => '🏦',
                'color' => '#3b82f6',
                'debit_account_id' => $banque->id,
                'credit_account_id' => $clients->id,
                'is_active' => true,
                'order' => 1,
                'description' => 'Enregistrer un paiement client par virement bancaire',
            ]);
        }

        if ($caisse && $clients) {
            AccountingCard::create([
                'name' => 'Encaissement Cash',
                'type' => 'cash',
                'icon' => '💵',
                'color' => '#10b981',
                'debit_account_id' => $caisse->id,
                'credit_account_id' => $clients->id,
                'is_active' => true,
                'order' => 2,
                'description' => 'Enregistrer un paiement client en espèces',
            ]);
        }

        if ($fournisseurs && $banque) {
            AccountingCard::create([
                'name' => 'Paiement Fournisseur Banque',
                'type' => 'bank',
                'icon' => '🏧',
                'color' => '#ef4444',
                'debit_account_id' => $fournisseurs->id,
                'credit_account_id' => $banque->id,
                'is_active' => true,
                'order' => 3,
                'description' => 'Payer un fournisseur par virement',
            ]);
        }

        if ($fournisseurs && $caisse) {
            AccountingCard::create([
                'name' => 'Paiement Fournisseur Cash',
                'type' => 'cash',
                'icon' => '💸',
                'color' => '#f59e0b',
                'debit_account_id' => $fournisseurs->id,
                'credit_account_id' => $caisse->id,
                'is_active' => true,
                'order' => 4,
                'description' => 'Payer un fournisseur en espèces',
            ]);
        }
    }
}
