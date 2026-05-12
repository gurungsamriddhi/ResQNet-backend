<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Veterinarians registered under an organization.
     * Referenced in medical_records, vaccinations, and medications.
     *
     * Relationships:
     *   - BELONGS TO one organization (organization_id)
     *   - HAS MANY medical_records, vaccinations, medications
     *
     * NOTE: No explicit index on organization_id — the foreign key
     *       constraint already creates one automatically in MySQL.
     */
    public function up(): void
    {
        Schema::create('vets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                  ->constrained()
                  ->onDelete('cascade'); // Org deleted = vets deleted

            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('specialization')->nullable(); // e.g., "Small Animal Surgery"
            $table->string('license_number', 100)->nullable(); // NVC registration number
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            // No index on organization_id — FK already creates one
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vets');
    }
};
