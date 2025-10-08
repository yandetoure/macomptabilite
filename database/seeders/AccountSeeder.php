<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // Actif
            ['code' => '401', 'name' => 'Fournisseurs', 'type' => 'liability'],
            ['code' => '411', 'name' => 'Clients', 'type' => 'asset'],
            ['code' => '512', 'name' => 'Banque', 'type' => 'asset'],
            ['code' => '531', 'name' => 'Caisse', 'type' => 'asset'],
            
            // Charges
            ['code' => '601', 'name' => 'Achats de marchandises', 'type' => 'expense'],
            ['code' => '606', 'name' => 'Achats non stockés de matières et fournitures', 'type' => 'expense'],
            ['code' => '61', 'name' => 'Services extérieurs', 'type' => 'expense'],
            ['code' => '62', 'name' => 'Autres services extérieurs', 'type' => 'expense'],
            ['code' => '63', 'name' => 'Impôts, taxes et versements assimilés', 'type' => 'expense'],
            ['code' => '64', 'name' => 'Charges de personnel', 'type' => 'expense'],
            ['code' => '65', 'name' => 'Autres charges de gestion courante', 'type' => 'expense'],
            ['code' => '66', 'name' => 'Charges financières', 'type' => 'expense'],
            ['code' => '67', 'name' => 'Charges exceptionnelles', 'type' => 'expense'],
            
            // Produits
            ['code' => '701', 'name' => 'Ventes de produits finis', 'type' => 'revenue'],
            ['code' => '706', 'name' => 'Prestations de services', 'type' => 'revenue'],
            ['code' => '707', 'name' => 'Ventes de marchandises', 'type' => 'revenue'],
            ['code' => '76', 'name' => 'Produits financiers', 'type' => 'revenue'],
            ['code' => '77', 'name' => 'Produits exceptionnels', 'type' => 'revenue'],
            
            // Capitaux propres
            ['code' => '101', 'name' => 'Capital', 'type' => 'equity'],
            ['code' => '106', 'name' => 'Réserves', 'type' => 'equity'],
            ['code' => '108', 'name' => 'Compte de l\'exploitant', 'type' => 'equity'],
            ['code' => '120', 'name' => 'Résultat de l\'exercice', 'type' => 'equity'],
            
            // TVA
            ['code' => '4456', 'name' => 'TVA déductible', 'type' => 'asset'],
            ['code' => '4457', 'name' => 'TVA collectée', 'type' => 'liability'],
        ];

        foreach ($accounts as $account) {
            Account::create([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => $account['type'],
                'is_active' => true,
                'balance' => 0,
            ]);
        }
    }
}
