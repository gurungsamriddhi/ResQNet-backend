<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add suspension support to the pets table.
     *
     * Changes:
     *   - Expands status enum to include 'suspended'
     *   - Adds suspension_reason (nullable TEXT) — required when suspended
     *   - Adds created_by (nullable FK → users) — tracks who created the pet
     */
    public function up(): void
    {
        // MySQL requires ALTER TABLE to modify an existing ENUM; SQLite doesn't support MODIFY COLUMN
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE pets MODIFY COLUMN status ENUM('available', 'adopted', 'unavailable', 'inactive', 'suspended') DEFAULT 'available'");
        }

        Schema::table('pets', function (Blueprint $table) {
            $table->text('suspension_reason')->nullable()->after('status');
            $table->foreignId('created_by')
                  ->nullable()
                  ->after('organization_id')
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // First clear any suspended pets back to inactive so the enum shrink doesn't fail
        DB::table('pets')->where('status', 'suspended')->update(['status' => 'inactive']);

        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['suspension_reason', 'created_by']);
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE pets MODIFY COLUMN status ENUM('available', 'adopted', 'unavailable', 'inactive') DEFAULT 'available'");
        }
    }
};
