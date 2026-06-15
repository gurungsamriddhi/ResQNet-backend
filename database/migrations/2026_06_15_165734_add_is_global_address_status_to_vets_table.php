<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Redesign vets table for global vs organization vet distinction.
     *
     * Adds:
     *   - is_global (bool)  — true for platform-wide vets, false for org-specific
     *   - address  (text)   — vet clinic / practice address
     *   - status   (string) — 'active' or 'inactive' (replaces is_active)
     *
     * Migrates existing data from is_active → status, then drops is_active.
     */
    public function up(): void
    {
        Schema::table('vets', function (Blueprint $table) {
            $table->boolean('is_global')->default(false)->after('organization_id');
            $table->text('address')->nullable()->after('email');
            $table->string('status', 20)->default('active')->after('license_number');
        });

        // Migrate existing data
        DB::table('vets')->whereNull('organization_id')->update(['is_global' => true]);
        DB::table('vets')->where('is_active', true)->update(['status' => 'active']);
        DB::table('vets')->where('is_active', false)->update(['status' => 'inactive']);

        Schema::table('vets', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->index('is_global');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('vets', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('license_number');
        });

        DB::table('vets')->where('status', 'active')->update(['is_active' => true]);
        DB::table('vets')->where('status', 'inactive')->update(['is_active' => false]);

        Schema::table('vets', function (Blueprint $table) {
            $table->dropIndex(['is_global']);
            $table->dropIndex(['status']);
            $table->dropColumn(['is_global', 'address', 'status']);
        });
    }
};
