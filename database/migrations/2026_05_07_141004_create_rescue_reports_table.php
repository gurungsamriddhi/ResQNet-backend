<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rescue reports submitted by public users when they find animals in distress.
     *
     * Relationships:
     *   - BELONGS TO one user (user_id) — the reporter
     *   - BELONGS TO one organization (organization_id, NULLABLE) — assigned later
     *   - BELONGS TO one pet (pet_id, NULLABLE) — linked after animal is rescued and registered
     *
     * Why organization_id is NULLABLE:
     *   When first submitted, no org is assigned. Admin or system assigns one later.
     *
     * Why pet_id is NULLABLE:
     *   The rescued animal gets a pet record AFTER being taken in by a shelter.
     *   This creates a lifecycle: Report → Rescue → Pet Created → Adoption
     *
     * Status flow: reported → assigned → in_progress → resolved
     *
     * latitude/longitude are NULLABLE to handle cases where GPS is unavailable
     * (user can provide address manually instead).
     */
    public function up(): void
    {
        Schema::create('rescue_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            $table->foreignId('organization_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            $table->foreignId('pet_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            $table->string('title')->nullable();
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address')->nullable();
            $table->enum('urgency', ['low', 'medium', 'high', 'critical'])
                  ->default('medium');
            $table->enum('status', ['reported', 'assigned', 'in_progress', 'resolved'])
                  ->default('reported');
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('urgency');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rescue_reports');
    }
};
