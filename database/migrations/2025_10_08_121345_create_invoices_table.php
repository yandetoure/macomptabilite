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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Numéro de facture
            $table->enum('type', ['customer', 'supplier']); // Client ou Fournisseur
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->string('party_name'); // Nom du client ou fournisseur
            $table->text('description')->nullable();
            $table->decimal('total_amount', 15, 2); // Montant total
            $table->decimal('paid_amount', 15, 2)->default(0); // Montant payé
            $table->enum('status', ['draft', 'pending', 'partial', 'paid'])->default('pending'); // Statut
            $table->string('file_path')->nullable(); // Chemin du fichier uploadé
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
