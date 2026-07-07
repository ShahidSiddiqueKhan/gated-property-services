<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // owner/client
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lease_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('rent'); // rent, service, invoice, maintenance
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('due'); // paid, due, overdue, pending_review
            $table->string('method')->nullable(); // bank_transfer, cash, card, other
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
