<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // 'gateway' identifies which processor (if any) handled the payment:
            // null/manual = client marked it paid manually pending finance review,
            // stripe = paid online by card via Stripe Checkout,
            // jazzcash = paid online via JazzCash mobile wallet.
            $table->string('gateway')->nullable()->after('method');
            $table->string('gateway_reference')->nullable()->after('gateway'); // Stripe session/payment intent id, or JazzCash pp_TxnRefNo
            $table->string('gateway_currency', 10)->nullable()->after('gateway_reference');
            $table->json('gateway_payload')->nullable()->after('gateway_currency'); // raw response, for audit/support
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['gateway', 'gateway_reference', 'gateway_currency', 'gateway_payload']);
        });
    }
};
