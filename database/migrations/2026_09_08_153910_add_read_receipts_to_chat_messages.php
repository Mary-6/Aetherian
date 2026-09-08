<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->boolean('read_by_admin')->default(false)->after('is_admin');
            $table->boolean('read_by_guest')->default(false)->after('read_by_admin');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['read_by_admin', 'read_by_guest']);
        });
    }
};
