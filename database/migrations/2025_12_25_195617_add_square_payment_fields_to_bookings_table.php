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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('square_payment_id')->nullable()->after('payment_method');
            $table->string('square_receipt_url')->nullable()->after('square_payment_id');
            $table->string('square_refund_id')->nullable()->after('square_receipt_url');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'cancelled'])->default('pending')->after('square_refund_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['square_payment_id', 'square_receipt_url', 'square_refund_id', 'payment_status']);
        });
    }
};
