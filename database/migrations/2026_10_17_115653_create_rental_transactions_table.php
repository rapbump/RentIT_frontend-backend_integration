<?php

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
        Schema::create('rental_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lender_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('renter_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('gadget_id')->constrained('gadgets')->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();

            $table->index(['lender_id', 'start_date']);
            $table->index(['lender_id', 'status', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_transactions');
    }
};
