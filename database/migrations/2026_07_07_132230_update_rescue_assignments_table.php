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
        Schema::table('rescue_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('rescue_assignments', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropUnique(['rescue_report_id', 'user_id']);
                $table->dropIndex(['user_id']);
                $table->dropColumn('user_id');
            }
            $table->foreignId('organization_team_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unique(['rescue_report_id', 'organization_team_id'], 'rescue_team_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rescue_assignments', function (Blueprint $table) {
            $table->dropForeign(['organization_team_id']);
            $table->dropUnique('rescue_team_unique');
            $table->dropColumn('organization_team_id');
            
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unique(['rescue_report_id', 'user_id']);
            $table->index('user_id');
        });
    }
};
