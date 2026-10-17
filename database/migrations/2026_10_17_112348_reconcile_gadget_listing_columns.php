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
            if (! Schema::hasColumn('gadgets', 'model')) {
                $table->string('model')->nullable();
            }

            if (! Schema::hasColumn('gadgets', 'weekly_rate')) {
                $table->decimal('weekly_rate', 10, 2)->nullable();
            }

            if (! Schema::hasColumn('gadgets', 'monthly_rate')) {
                $table->decimal('monthly_rate', 10, 2)->nullable();
            }

            if (! Schema::hasColumn('gadgets', 'cancellation_policy')) {
                $table->string('cancellation_policy')->nullable();
            }

            if (! Schema::hasColumn('gadgets', 'pickup_mode')) {
                $table->string('pickup_mode')->nullable();
            }

            if (! Schema::hasColumn('gadgets', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->nullable();
            }

            if (! Schema::hasColumn('gadgets', 'availability_end_date')) {
                $table->date('availability_end_date')->nullable();
            }

            if (! Schema::hasColumn('gadgets', 'gallery_paths')) {
                $table->json('gallery_paths')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
