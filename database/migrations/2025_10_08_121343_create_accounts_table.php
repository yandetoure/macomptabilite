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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Numéro de compte (ex: 411, 512, etc.)
            $table->string('name'); // Nom du compte
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']); // Actif, Passif, Capitaux propres, Produits, Charges
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->onDelete('cascade'); // Pour hiérarchie
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('balance', 15, 2)->default(0); // Solde du compte
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
