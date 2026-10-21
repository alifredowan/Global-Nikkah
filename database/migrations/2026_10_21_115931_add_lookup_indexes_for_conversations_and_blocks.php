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
        Schema::table('conversation_participants', function (Blueprint $table): void {
            $table->index('user_id', 'conversation_participants_user_id_index');
        });

        Schema::table('blocked_users', function (Blueprint $table): void {
            $table->index('blocked_user_id', 'blocked_users_blocked_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_participants', function (Blueprint $table): void {
            $table->dropIndex('conversation_participants_user_id_index');
        });

        Schema::table('blocked_users', function (Blueprint $table): void {
            $table->dropIndex('blocked_users_blocked_user_id_index');
        });
    }
};
