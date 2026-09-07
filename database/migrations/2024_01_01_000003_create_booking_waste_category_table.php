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
        Schema::create('booking_waste_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dropoff_booking_id')->constrained('dropoff_bookings')->onDelete('cascade');
            $table->foreignId('waste_category_id')->constrained('waste_categories')->onDelete('cascade');
            $table->decimal('estimated_weight', 8, 2);
            $table->decimal('verified_weight', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['dropoff_booking_id', 'waste_category_id'], 'booking_category_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_waste_category');
    }
};
