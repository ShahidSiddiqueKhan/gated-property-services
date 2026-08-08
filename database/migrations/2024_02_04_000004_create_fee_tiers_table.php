<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin-editable tiered fee percentages for maintenance coordination and
     * renovation project management. FeeCalculator picks the row whose
     * [min_amount, max_amount) range contains the invoice/project value.
     */
    public function up(): void
    {
        Schema::create('fee_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // maintenance | renovation
            $table->decimal('min_amount', 14, 2)->default(0);
            $table->decimal('max_amount', 14, 2)->nullable(); // null = no upper bound
            $table->decimal('fee_percent', 5, 2);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_tiers');
    }
};
