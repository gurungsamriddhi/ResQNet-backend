<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Multiple images per pet. One image is marked as primary (used in listings).
     *
     * Relationships:
     *   - BELONGS TO one pet (pet_id)
     *
     * Why a separate table instead of JSON column on pets?
     *   Normalized design — can query, count, reorder images independently.
     *
     * NOTE: No softDeletes here. When an image is deleted, the physical
     *       file should be cleaned up too. Soft-deleting creates orphaned
     *       files or inconsistent state.
     */
    public function up(): void
    {
        Schema::create('pet_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')
                  ->constrained()
                  ->onDelete('cascade'); // Pet deleted = images deleted

            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
            // No softDeletes — images should be hard-deleted with file cleanup
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_images');
    }
};
