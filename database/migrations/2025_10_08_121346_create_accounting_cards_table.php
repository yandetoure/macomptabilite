<?php declare(strict_types=1); 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de la card (ex: "Factures Clients", "Cash", "Banque")
            $table->enum('type', ['invoice_customer', 'invoice_supplier', 'cash', 'bank', 'custom']); // Type de card
            $table->string('icon')->nullable(); // Icône à afficher
            $table->string('color')->default('#3b82f6'); // Couleur de la card
            $table->foreignId('debit_account_id')->nullable()->constrained('accounts')->onDelete('set null'); // Compte au débit par défaut
            $table->foreignId('credit_account_id')->nullable()->constrained('accounts')->onDelete('set null'); // Compte au crédit par défaut
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0); // Ordre d'affichage
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_cards');
    }
};
