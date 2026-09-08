<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->unsignedInteger('guest_unread_count')->default(0)->after('guest_phone');
            $table->unsignedInteger('admin_unread_count')->default(0)->after('guest_unread_count');
        });
    }

    public function down(): void
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->dropColumn(['guest_unread_count', 'admin_unread_count']);
        });
    }
};
