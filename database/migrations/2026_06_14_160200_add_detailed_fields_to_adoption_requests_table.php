<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add more detailed application fields to adoption_requests table.
     */
    public function up(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->boolean('experience_with_pets')->nullable()->after('has_other_pets');
            $table->integer('family_members_count')->nullable()->after('experience_with_pets');
            $table->string('housing_type')->nullable()->after('family_members_count'); // e.g., rent, own, living_with_parents
            $table->string('employment_status')->nullable()->after('housing_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn([
                'experience_with_pets',
                'family_members_count',
                'housing_type',
                'employment_status'
            ]);
        });
    }
};
