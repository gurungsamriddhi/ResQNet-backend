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
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn([
                'housing_type',
                'employment_status',
                'family_members_count',
                'has_other_pets',
                'experience_with_pets',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->string('housing_type')->nullable()->after('message');
            $table->string('employment_status')->nullable()->after('housing_type');
            $table->integer('family_members_count')->nullable()->after('employment_status');
            $table->boolean('has_other_pets')->nullable()->after('family_members_count');
            $table->boolean('experience_with_pets')->nullable()->after('has_other_pets');
        });
    }
};
