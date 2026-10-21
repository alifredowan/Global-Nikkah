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
        Schema::table('users', function (Blueprint $table): void {
            $table->index(
                ['role', 'is_active', 'gender', 'marital_status'],
                'users_discovery_lookup_index'
            );
        });

        Schema::table('saved_searches', function (Blueprint $table): void {
            $table->index(['user_id', 'created_at'], 'saved_searches_user_created_index');
        });

        Schema::table('notifications', function (Blueprint $table): void {
            $table->index(
                ['notifiable_type', 'notifiable_id', 'read_at', 'created_at'],
                'notifications_unread_lookup_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table): void {
            $table->dropIndex('notifications_unread_lookup_index');
        });

        Schema::table('saved_searches', function (Blueprint $table): void {
            $table->dropIndex('saved_searches_user_created_index');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex('users_discovery_lookup_index');
        });
    }
};
