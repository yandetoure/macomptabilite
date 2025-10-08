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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('type')->constrained('customers')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->after('customer_id')->constrained('suppliers')->onDelete('set null');
            // On garde party_name en option pour les cas où on n'a pas de customer/supplier enregistré
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['customer_id', 'supplier_id']);
        });
    }
};
