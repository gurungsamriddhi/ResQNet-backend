<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // is_active column + index were already dropped in 2026_06_15_165734
        // This migration exists only for historical compatibility (was created
        // before the vets redesign migration was fixed). It's a no-op now.
    }

    public function down(): void
    {
        // Restore handled by 2026_06_15_165734's down method
    }
};