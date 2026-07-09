<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (! Schema::hasColumn('donations', 'purchase_order_id')) {
                $table->string('purchase_order_id', 100)->unique()->nullable()->after('id');
            }
            if (! Schema::hasColumn('donations', 'pidx')) {
                $table->string('pidx', 100)->nullable()->unique()->after('purchase_order_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $columns = ['purchase_order_id', 'pidx'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('donations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
