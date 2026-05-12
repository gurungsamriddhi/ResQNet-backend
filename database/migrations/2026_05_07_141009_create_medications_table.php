<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Medications prescribed to a pet by a vet.
     * A medication is "active" if today is between start_date and end_date.
     * end_date NULL means ongoing/indefinite medication.
     */
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('vet_id')->nullable()->constrained()->onDelete('set null');

            $table->string('medicine_name');
            $table->string('dosage');           // e.g., "250mg"
            $table->string('frequency')->nullable(); // e.g., "twice daily"
            $table->date('start_date');
            $table->date('end_date')->nullable();    // NULL = ongoing
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
