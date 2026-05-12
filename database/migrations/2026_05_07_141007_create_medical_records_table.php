<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Medical records for each pet — checkups, treatments, surgeries, etc.
     * Core of the health tracking system, accessible via QR code scan.
     *
     * Relationships:
     *   - BELONGS TO one pet (pet_id) — required, cascade on delete
     *   - BELONGS TO one vet (vet_id) — nullable, set null if vet removed
     *
     * vet_id is nullable with SET NULL: medical records should be preserved
     * even if the vet is removed from the system. The record still shows
     * what was done, just without the specific vet reference.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')
                  ->constrained()
                  ->onDelete('cascade'); // Pet deleted = records deleted

            $table->foreignId('vet_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null'); // Keep record even if vet removed

            $table->enum('type', [
                'checkup', 'treatment', 'surgery', 'injury', 'observation', 'other'
            ])->default('checkup');
            $table->string('title'); // e.g., "Leg fracture treatment"
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->date('record_date');
            $table->date('next_visit_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('record_date');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
