<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vets', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('vets', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('vets', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->index('is_active');
        });
    }
};