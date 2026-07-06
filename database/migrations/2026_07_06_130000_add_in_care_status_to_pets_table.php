<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE pets MODIFY COLUMN status ENUM('available', 'adopted', 'unavailable', 'inactive', 'suspended', 'in_care') DEFAULT 'available'");
        }
    }

    public function down(): void
    {
        DB::table('pets')->where('status', 'in_care')->update(['status' => 'available']);

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE pets MODIFY COLUMN status ENUM('available', 'adopted', 'unavailable', 'inactive', 'suspended') DEFAULT 'available'");
        }
    }
};
