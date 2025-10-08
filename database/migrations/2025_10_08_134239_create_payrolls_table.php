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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_number')->unique(); // Numéro de paie
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('pay_period_start'); // Début de période
            $table->date('pay_period_end'); // Fin de période
            $table->date('payment_date'); // Date de paiement
            $table->decimal('gross_salary', 15, 2); // Salaire brut
            $table->decimal('social_contributions', 15, 2)->default(0); // Cotisations sociales
            $table->decimal('taxes', 15, 2)->default(0); // Impôts
            $table->decimal('other_deductions', 15, 2)->default(0); // Autres retenues
            $table->decimal('net_salary', 15, 2); // Salaire net
            $table->enum('status', ['draft', 'validated', 'paid'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
