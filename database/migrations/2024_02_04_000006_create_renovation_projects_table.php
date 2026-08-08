<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('renovation_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('contractor_name')->nullable();
            $table->string('contractor_contact')->nullable();
            $table->decimal('project_value', 14, 2); // agreed/estimated renovation value, used to compute the tiered fee
            $table->decimal('fee_percent', 5, 2); // resolved from fee_tiers at creation time (renovation category)
            $table->decimal('fee_amount', 14, 2); // project_value * fee_percent
            $table->decimal('final_cost', 14, 2)->nullable(); // actual total cost once completed, if it differs from project_value
            $table->string('status')->default('proposed'); // proposed | approved | in_progress | completed | cancelled
            $table->string('approval_status')->default('pending'); // pending | approved | rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('renovation_projects');
    }
};
