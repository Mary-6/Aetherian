<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('sender_city')->nullable()->after('sender_address');
            $table->string('recipient_city')->nullable()->after('recipient_address');
            $table->string('carrier')->nullable()->after('destination');
            $table->decimal('payment_amount', 12, 2)->nullable()->after('declared_value');
            $table->string('currency', 3)->default('USD')->after('payment_amount');
            $table->date('pickup_date')->nullable()->after('currency');
            $table->dateTime('departure_time')->nullable()->after('pickup_date');
            $table->date('estimated_delivery_at')->nullable()->after('departure_time');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn([
                'sender_city',
                'recipient_city',
                'carrier',
                'payment_amount',
                'currency',
                'pickup_date',
                'departure_time',
                'estimated_delivery_at',
            ]);
        });
    }
};
