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
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('gift_card_number')->unique();
            $table->string('pin')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('original_purchase_amount', 10, 2);
            $table->decimal('discount_applied', 10, 2)->default(0);
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->enum('delivery_method', ['self', 'sms', 'email'])->default('email');
            $table->dateTime('delivery_datetime')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['active', 'expired', 'used_up'])->default('active');
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};
