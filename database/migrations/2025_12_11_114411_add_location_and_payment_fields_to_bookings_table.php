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
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('place_id')->nullable()->after('longitude');
            $table->enum('payment_method', ['card', 'gift_card'])->nullable()->after('total_price');
            $table->foreignId('gift_card_id')->nullable()->after('payment_method')->constrained('gift_cards')->onDelete('set null');
            $table->decimal('gift_card_discount', 10, 2)->default(0)->after('gift_card_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['gift_card_id']);
            $table->dropColumn(['latitude', 'longitude', 'place_id', 'payment_method', 'gift_card_id', 'gift_card_discount']);
        });
    }
};
