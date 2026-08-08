<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Turns the existing payments table into GATED's full revenue ledger.
     * `type` stays as the broad existing category (rent/service/invoice/
     * maintenance); `revenue_stream` adds the fine-grained classification
     * finance reporting needs. base_amount/fee_percent/fee_amount let us
     * show transparent breakdowns (e.g. "Contractor Cost / GATED Fee / Total"
     * or "Rent / Commission / Owner Receives") instead of one opaque number.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('revenue_stream')->nullable()->after('type');
            // package_fee | tenant_placement | rent_commission | maintenance_fee |
            // renovation_fee | emergency_service | advertising | inspection_report

            $table->decimal('base_amount', 14, 2)->nullable()->after('amount'); // e.g. contractor invoice or monthly rent the fee is derived from
            $table->decimal('fee_percent', 5, 2)->nullable()->after('base_amount'); // % applied to base_amount to get GATED's cut
            $table->decimal('owner_amount', 14, 2)->nullable()->after('fee_percent'); // what the owner nets, when relevant (rent minus commission)

            $table->foreignId('property_package_id')->nullable()->after('lease_id')->constrained()->nullOnDelete();
            $table->foreignId('renovation_project_id')->nullable()->after('property_package_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('property_package_id');
            $table->dropConstrainedForeignId('renovation_project_id');
            $table->dropColumn(['revenue_stream', 'base_amount', 'fee_percent', 'owner_amount']);
        });
    }
};
