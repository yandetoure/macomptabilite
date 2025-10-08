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
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->boolean('is_reconciled')->default(false)->after('is_balanced');
            $table->timestamp('reconciled_at')->nullable()->after('is_reconciled');
            $table->unsignedBigInteger('reconciled_by')->nullable()->after('reconciled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropColumn(['is_reconciled', 'reconciled_at', 'reconciled_by']);
        });
    }
};
