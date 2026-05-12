<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Vaccination records for each pet.
     * Index on next_due_date enables efficient "overdue vaccinations" queries.
     */
    public function up(): void
    {
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('vet_id')->nullable()->constrained()->onDelete('set null');

            $table->string('vaccine_name');
            $table->string('batch_number', 100)->nullable();
            $table->date('date_administered');
            $table->date('next_due_date')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('date_administered');
            $table->index('next_due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
