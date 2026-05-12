<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adoption requests submitted by public users for specific pets.
     *
     * Relationships:
     *   - BELONGS TO one pet (pet_id) — the pet being requested
     *   - BELONGS TO one user (user_id) — the person requesting adoption
     *
     * Status flow: pending → approved | rejected
     * When approved: application logic changes pet status to 'adopted'
     *
     * user_id is nullable with SET NULL — if user deletes their account,
     * the adoption request record is preserved for org's history.
     *
     * Composite index on (pet_id, status) — the most common query:
     * "Show me all pending requests for this pet"
     */
    public function up(): void
    {
        Schema::create('adoption_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')
                  ->constrained()
                  ->onDelete('cascade'); // Pet deleted = requests deleted

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null'); // Keep request history if user leaves

            $table->text('message')->nullable();
            $table->string('living_situation')->nullable();
            $table->boolean('has_other_pets')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['pet_id', 'status']); // Composite index for common queries
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_requests');
    }
};
