<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE user_profiles MODIFY COLUMN experience_with_pets ENUM('none','beginner','intermediate','experienced') NULL");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE user_profiles MODIFY COLUMN experience_with_pets TINYINT(1) NULL');
    }
};
