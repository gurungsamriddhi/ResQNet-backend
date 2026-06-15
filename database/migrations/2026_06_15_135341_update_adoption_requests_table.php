<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {

            // Rename admin_notes -> organization_response
            $table->renameColumn('admin_notes', 'organization_response');

            // Expand status workflow
            $table->enum('status', [
                'pending',
                'under_review',
                'approved',
                'rejected',
                'completed',
            ])->default('pending')->change();

            // Track who reviewed the application
            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('organization_response')
                ->constrained('users')
                ->nullOnDelete();

            // When response email/notification was sent
            $table->timestamp('response_sent_at')
                ->nullable()
                ->after('reviewed_at');

            // When adoption was actually completed
            $table->timestamp('adopted_at')
                ->nullable()
                ->after('response_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {

            $table->dropForeign(['reviewed_by']);

            $table->dropColumn([
                'reviewed_by',
                'response_sent_at',
                'adopted_at',
            ]);

            $table->renameColumn(
                'organization_response',
                'admin_notes'
            );

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending')->change();
        });
    }
};
