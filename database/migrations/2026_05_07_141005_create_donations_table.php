<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Donations from public users to organizations.
     * Integrated with Khalti / eSewa payment gateways.
     *
     * Relationships:
     *   - BELONGS TO one user (user_id) — the donor
     *   - BELONGS TO one organization (organization_id) — the recipient
     *
     * Both FKs are nullable with SET NULL — financial records must NEVER
     * be deleted even if the user or org is removed. This is critical
     * for auditing and accounting.
     *
     * payment_payload: Stores the raw JSON response from Khalti/eSewa.
     * Essential for debugging payment issues and for audit trails.
     *
     * Status flow: pending → completed | failed
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null'); // Preserve donation records

            $table->foreignId('organization_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null'); // Preserve donation records

            $table->decimal('amount', 10, 2); // Amount in NPR
            $table->enum('payment_method', ['khalti', 'esewa']);
            $table->string('transaction_id')->nullable(); // From payment gateway
            $table->enum('status', ['pending', 'completed', 'failed'])
                  ->default('pending');
            $table->json('payment_payload')->nullable(); // Raw gateway response
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
