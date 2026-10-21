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
        Schema::table('photo_access_grants', function (Blueprint $table): void {
            $table->index(
                ['granted_to_user_id', 'status'],
                'photo_access_grants_recipient_status_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photo_access_grants', function (Blueprint $table): void {
            $table->dropIndex('photo_access_grants_recipient_status_index');
        });
    }
};
