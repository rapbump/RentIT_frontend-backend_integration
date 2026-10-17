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
        Schema::table('gadgets', function (Blueprint $table) {
            $table->string('model')->nullable();
            $table->decimal('weekly_rate', 10, 2)->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->string('cancellation_policy')->nullable();
            $table->string('pickup_mode')->nullable();
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->date('availability_end_date')->nullable();
            $table->json('gallery_paths')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            $table->dropColumn([
                'model',
                'weekly_rate',
                'monthly_rate',
                'cancellation_policy',
                'pickup_mode',
                'delivery_fee',
                'availability_end_date',
                'gallery_paths',
            ]);
        });
    }
};
