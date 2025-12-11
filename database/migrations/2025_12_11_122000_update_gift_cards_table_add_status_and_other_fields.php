<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add gift_card_number column if it doesn't exist
        if (!Schema::hasColumn('gift_cards', 'gift_card_number')) {
            Schema::table('gift_cards', function (Blueprint $table) {
                $table->string('gift_card_number')->nullable()->after('id');
            });
        }

        // Populate existing records with unique gift card numbers
        $existingCards = DB::table('gift_cards')->whereNull('gift_card_number')->get();
        foreach ($existingCards as $card) {
            $cardNumber = 'SZGC-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 4)) . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
            // Ensure uniqueness
            while (DB::table('gift_cards')->where('gift_card_number', $cardNumber)->exists()) {
                $cardNumber = 'SZGC-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 4)) . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
            }
            
            DB::table('gift_cards')->where('id', $card->id)->update([
                'gift_card_number' => $cardNumber,
            ]);
        }

        // Add remaining columns
        Schema::table('gift_cards', function (Blueprint $table) {
            if (!Schema::hasColumn('gift_cards', 'pin')) {
                $table->string('pin')->nullable()->after('gift_card_number');
            }
            if (!Schema::hasColumn('gift_cards', 'original_purchase_amount')) {
                $table->decimal('original_purchase_amount', 10, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('gift_cards', 'discount_applied')) {
                $table->decimal('discount_applied', 10, 2)->default(0)->after('original_purchase_amount');
            }
            if (!Schema::hasColumn('gift_cards', 'sender_name')) {
                $table->string('sender_name')->nullable()->after('discount_applied');
            }
            if (!Schema::hasColumn('gift_cards', 'sender_email')) {
                $table->string('sender_email')->nullable()->after('sender_name');
            }
            if (!Schema::hasColumn('gift_cards', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('sender_email');
            }
            if (!Schema::hasColumn('gift_cards', 'recipient_email')) {
                $table->string('recipient_email')->nullable()->after('recipient_name');
            }
            if (!Schema::hasColumn('gift_cards', 'recipient_phone')) {
                $table->string('recipient_phone')->nullable()->after('recipient_email');
            }
            if (!Schema::hasColumn('gift_cards', 'delivery_method')) {
                $table->enum('delivery_method', ['self', 'sms', 'email'])->default('email')->after('recipient_phone');
            }
            if (!Schema::hasColumn('gift_cards', 'delivery_datetime')) {
                $table->dateTime('delivery_datetime')->nullable()->after('delivery_method');
            }
            if (!Schema::hasColumn('gift_cards', 'message')) {
                $table->text('message')->nullable()->after('delivery_datetime');
            }
            if (!Schema::hasColumn('gift_cards', 'status')) {
                $table->enum('status', ['active', 'expired', 'used_up'])->default('active')->after('message');
            }
            if (!Schema::hasColumn('gift_cards', 'expires_at')) {
                $table->date('expires_at')->nullable()->after('status');
            }
        });

        // Update existing records with default values
        DB::table('gift_cards')->whereNull('original_purchase_amount')->update([
            'original_purchase_amount' => DB::raw('amount'),
        ]);
        
        // Update status based on is_active
        DB::table('gift_cards')->whereNull('status')->update([
            'status' => DB::raw('CASE WHEN is_active = 1 THEN "active" ELSE "expired" END'),
        ]);

        // Add unique index manually using raw SQL to avoid issues
        try {
            DB::statement('CREATE UNIQUE INDEX gift_cards_gift_card_number_unique ON gift_cards(gift_card_number)');
        } catch (\Exception $e) {
            // Index might already exist, that's okay
        }
    }

    public function down(): void
    {
        // Drop unique index first
        try {
            DB::statement('DROP INDEX gift_cards_gift_card_number_unique ON gift_cards');
        } catch (\Exception $e) {
            // Index might not exist
        }

        Schema::table('gift_cards', function (Blueprint $table) {
            $columns = [
                'gift_card_number', 'pin', 'original_purchase_amount', 'discount_applied',
                'sender_name', 'sender_email', 'recipient_name', 'recipient_email',
                'recipient_phone', 'delivery_method', 'delivery_datetime', 'message',
                'status', 'expires_at'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('gift_cards', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
