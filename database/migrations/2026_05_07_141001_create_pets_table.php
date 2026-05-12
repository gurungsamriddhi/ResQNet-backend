<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pets listed by organizations for adoption.
     *
     * Relationships:
     *   - BELONGS TO one organization (organization_id)
     *   - HAS MANY pet_images, adoption_requests
     *   - HAS MANY medical_records, vaccinations, medications
     *
     * qr_code_id: Auto-generated unique ID (e.g., "RQN-00042") for QR codes.
     *   Shelter staff print QR codes, attach to animal collars.
     *   Scanning opens the animal's full profile + medical history.
     *
     * organization_id is nullable with SET NULL so that if an org is
     * force-deleted, pet records are preserved for historical purposes.
     * (Normally orgs are soft-deleted, so this rarely fires.)
     *
     * Status flow: available → adopted | unavailable | inactive
     *
     * NOTE: qr_code_id has ->unique() which already creates an index.
     *       No separate ->index('qr_code_id') needed.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            $table->string('qr_code_id', 20)->unique(); // unique() already creates index
            $table->string('name');
            $table->enum('species', ['dog', 'cat', 'bird', 'rabbit', 'other'])
                  ->default('dog');
            $table->string('breed', 100)->nullable();
            $table->unsignedInteger('age_months')->nullable();
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->enum('size', ['small', 'medium', 'large', 'xl'])->default('medium');
            $table->string('color', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('health_status')->nullable();
            $table->boolean('is_vaccinated')->default(false);
            $table->boolean('is_neutered')->default(false);
            $table->enum('status', ['available', 'adopted', 'unavailable', 'inactive'])
                  ->default('available');
            $table->timestamp('adopted_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('species');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
