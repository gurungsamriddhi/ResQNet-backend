<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rescue_reports', function (Blueprint $table) {
            $table->string('case_code')->unique()->nullable()->after('id');
            $table->timestamp('assigned_at')->nullable()->after('resolved_at');
            $table->timestamp('in_progress_at')->nullable()->after('assigned_at');
            $table->index('case_code');
        });
    }

    public function down(): void
    {
        Schema::table('rescue_reports', function (Blueprint $table) {
            $table->dropIndex(['case_code']);
            $table->dropColumn(['case_code', 'assigned_at', 'in_progress_at']);
        });
    }
};
