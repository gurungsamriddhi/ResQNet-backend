<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Organizations represent animal shelters and rescue groups.
     *
     * Relationships:
     *   - BELONGS TO one user (user_id) — the org admin who manages it
     *   - HAS MANY pets, vets, rescue_reports, donations
     *
     * Each user can manage at most ONE organization (user_id is unique).
     * latitude/longitude enable proximity-based rescue report routing.
     * status enum is more flexible than a simple boolean is_verified.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained()
                  ->onDelete('cascade'); // If org admin deleted, org goes too

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('registration_number')->nullable(); // Nepal NGO registration
            $table->decimal('latitude', 10, 8)->nullable();    // GPS for proximity search
            $table->decimal('longitude', 11, 8)->nullable();   // GPS for proximity search

            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'rejected'])
                  ->default('pending');

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
