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
        Schema::table('verification_requests', function (Blueprint $table): void {
            $table->index(
                ['status', 'created_at'],
                'verification_requests_status_created_index'
            );
        });

        Schema::table('reports', function (Blueprint $table): void {
            $table->index(['status', 'created_at'], 'reports_status_created_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table): void {
            $table->dropIndex('reports_status_created_index');
        });

        Schema::table('verification_requests', function (Blueprint $table): void {
            $table->dropIndex('verification_requests_status_created_index');
        });
    }
};
