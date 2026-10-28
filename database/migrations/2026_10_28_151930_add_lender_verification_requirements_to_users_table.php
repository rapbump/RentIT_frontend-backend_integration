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
        Schema::table('users', function (Blueprint $table): void {
            $table->string('verification_official_name')->nullable();
            $table->string('verification_province')->nullable();
            $table->string('verification_city')->nullable();
            $table->string('verification_barangay')->nullable();
            $table->string('verification_street_address')->nullable();
            $table->string('government_id_path')->nullable();
            $table->string('residence_proof_path')->nullable();
            $table->string('barangay_certification_path')->nullable();
            $table->timestamp('seller_verification_submitted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'verification_official_name',
                'verification_province',
                'verification_city',
                'verification_barangay',
                'verification_street_address',
                'government_id_path',
                'residence_proof_path',
                'barangay_certification_path',
                'seller_verification_submitted_at',
            ]);
        });
    }
};
