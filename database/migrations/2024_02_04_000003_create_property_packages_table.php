<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A property's subscription to a management package. Kept append-only
     * (new row on package change/renewal) so history is preserved — only
     * one row per property is normally 'active' at a time.
     */
    public function up(): void
    {
        Schema::create('property_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->restrictOnDelete();
            $table->string('frequency')->default('monthly'); // monthly | quarterly | annually
            $table->decimal('base_price', 12, 2); // package monthly_price snapshot at time of subscribing
            $table->decimal('discount_percent', 5, 2)->default(0); // frequency discount applied (0/5/10)
            $table->decimal('final_price', 12, 2); // price actually billed for the chosen frequency, after discount
            $table->decimal('commission_percent', 5, 2); // package default, or admin override up to 12%
            $table->boolean('commission_overridden')->default(false);
            $table->string('status')->default('active'); // active | expired | cancelled
            $table->date('started_at');
            $table->date('renews_at')->nullable();
            $table->date('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_packages');
    }
};
