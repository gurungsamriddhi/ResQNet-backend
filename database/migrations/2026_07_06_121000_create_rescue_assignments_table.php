<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rescue_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rescue_report_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('role')->nullable();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

            $table->unique(['rescue_report_id', 'user_id']);
            $table->index('rescue_report_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rescue_assignments');
    }
};
