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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique(); // Numéro d'écriture unique
            $table->date('entry_date'); // Date de l'écriture
            $table->string('reference')->nullable(); // Référence (ex: numéro de facture)
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'posted'])->default('draft'); // Brouillon ou comptabilisé
            $table->boolean('is_balanced')->default(false); // Écriture équilibrée
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('invoice_id')->nullable(); // Lien avec facture
            $table->unsignedBigInteger('payment_id')->nullable(); // Lien avec paiement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
