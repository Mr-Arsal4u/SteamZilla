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
        // Update payment_method enum to include 'square'
        // MySQL doesn't support direct enum modification, so we use raw SQL
        \DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_method ENUM('card', 'gift_card', 'square', 'cash') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        \DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_method ENUM('card', 'gift_card') NULL");
    }
};
