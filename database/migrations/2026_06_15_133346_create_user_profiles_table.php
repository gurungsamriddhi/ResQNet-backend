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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()                    // ← Important: one profile per user
                ->constrained()
                ->onDelete('cascade');

            // Adoption eligibility fields
            $table->enum('housing_type', ['apartment', 'house', 'condo', 'other'])->nullable();
            $table->enum('employment_status', ['employed', 'unemployed', 'student', 'retired'])->nullable();
            $table->unsignedTinyInteger('family_members_count')->nullable();
            $table->boolean('has_other_pets')->nullable()->default(false);
            $table->boolean('experience_with_pets')->nullable()->default(false);

            // Optional extra info
            $table->text('bio')->nullable();
            $table->string('occupation')->nullable();

            // Optional: more adoption-relevant fields
            $table->string('preferred_pet_type')->nullable();     // dog, cat, etc.
            $table->string('preferred_pet_size')->nullable();     // small, medium, large
            $table->integer('max_pets_allowed')->nullable()->default(1);
            $table->boolean('has_yard')->nullable()->default(false);
            $table->boolean('is_allergic')->nullable()->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
