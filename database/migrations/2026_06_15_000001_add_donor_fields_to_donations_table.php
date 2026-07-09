<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (! Schema::hasColumn('donations', 'donor_name')) {
                $table->string('donor_name')->nullable()->after('organization_id');
            }
            if (! Schema::hasColumn('donations', 'donor_email')) {
                $table->string('donor_email')->nullable()->after('donor_name');
            }
            if (! Schema::hasColumn('donations', 'donor_phone')) {
                $table->string('donor_phone', 20)->nullable()->after('donor_email');
            }
            if (! Schema::hasColumn('donations', 'is_anonymous')) {
                $table->boolean('is_anonymous')->default(false)->after('donor_phone');
            }
            if (! Schema::hasColumn('donations', 'frequency')) {
                $table->enum('frequency', ['one_time', 'monthly'])->default('one_time')->after('is_anonymous');
            }
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE donations MODIFY payment_method VARCHAR(50) NOT NULL');
        }
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $columns = ['donor_name', 'donor_email', 'donor_phone', 'is_anonymous', 'frequency'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('donations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE donations MODIFY payment_method ENUM('khalti', 'esewa') NOT NULL");
        }
    }
};
