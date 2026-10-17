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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable();
            }

            $table->string('avatar_path')->nullable();
            $table->string('location')->nullable();
            $table->string('store_name')->nullable();
            $table->text('store_about')->nullable();
            $table->string('operating_days')->nullable();
            $table->string('business_hours')->nullable();
            $table->text('meetup_locations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_path',
                'location',
                'store_name',
                'store_about',
                'operating_days',
                'business_hours',
                'meetup_locations',
            ]);
        });
    }
};
