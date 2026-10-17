<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('gadgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lender_id'); // link to users table
            $table->string('name');
            $table->string('category');
            $table->string('brand')->nullable();
            $table->string('condition')->nullable();
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->integer('rental_duration')->nullable();
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->decimal('rental_payment', 10, 2)->nullable();
            $table->text('rental_agreement')->nullable();
            $table->date('availability_date')->nullable();
            $table->string('status')->default('Available');
            $table->json('meetup_options')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('lender_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gadgets');
    }
};
