<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Transparent contractor-invoice vs GATED-fee breakdown for maintenance
     * jobs (spec: "Contractor Cost / GATED Fee / Total" shown separately in
     * the client portal). Populated when admin logs the contractor invoice,
     * which also creates a linked Payment record for billing.
     */
    public function up(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->decimal('contractor_cost', 12, 2)->nullable()->after('assigned_to');
            $table->decimal('gated_fee_percent', 5, 2)->nullable()->after('contractor_cost');
            $table->decimal('gated_fee_amount', 12, 2)->nullable()->after('gated_fee_percent');
            $table->decimal('total_cost', 12, 2)->nullable()->after('gated_fee_amount');
            $table->string('invoice_path')->nullable()->after('total_cost');
            $table->foreignId('payment_id')->nullable()->after('invoice_path')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_id');
            $table->dropColumn(['contractor_cost', 'gated_fee_percent', 'gated_fee_amount', 'total_cost', 'invoice_path']);
        });
    }
};
