<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add profile-related fields to the users table.
     *
     * New columns:
     *   - phone: Nepal users often prefer phone-based identity
     *   - avatar: Profile picture storage path
     *   - address: User location (useful for adoption matching)
     *   - is_active: Allows admin to disable accounts without deleting
     *   - phone_verified_at: Track phone verification separately
     *   - last_login_at: Analytics — track active users
     *   - deleted_at: Soft delete support (never lose user data)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->unique()->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->text('address')->nullable()->after('avatar');
            $table->boolean('is_active')->default(true)->after('address');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('phone_verified_at');
            $table->softDeletes();

            $table->index('is_active');
        });
    }

    /**
     * Reverse the profile fields addition.
     * Drop indexes before columns to avoid MySQL constraint errors.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone']);
            $table->dropIndex(['is_active']);
            $table->dropSoftDeletes();
            $table->dropColumn([
                'phone',
                'avatar',
                'address',
                'is_active',
                'phone_verified_at',
                'last_login_at',
            ]);
        });
    }
};
