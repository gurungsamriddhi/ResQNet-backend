<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rescue_case_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rescue_report_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->foreignId('changed_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('rescue_report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rescue_case_histories');
    }
};
