<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\SendOtpNotification;
use Illuminate\Support\Facades\Cache;


/**
 * @mixin \Spatie\Permission\Traits\HasRoles
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'address',
        'is_active',
        'fcm_token',
        'organization_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'deleted_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token; // The column name where you store the mobile device token
    }

     // ─── Relationships ─────────────────────────────────
    /**
     * The organization this user belongs to.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function rescueReports()
    {
        return $this->hasMany(RescueReport::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function savedPets()
    {
        return $this->belongsToMany(Pet::class, 'saved_pets')->withTimestamps();
    }

    // Add this method inside your User class

    /**
     * Get the user's profile.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function sendOtp($type = 'email')
    {
        $cooldownKey = "otp_cooldown_{$type}_{$this->email}";
        if (Cache::has($cooldownKey)) {
            $secondsRemaining = Cache::get($cooldownKey)->diffInSeconds(now());
            throw new \Exception("Please wait 60 seconds before requesting a new OTP.");
        }

        $otp = rand(100000, 999999);
        $key = "otp_{$type}_{$this->email}";

        //save otp in cache (temporary memory) for 10 minutes
        Cache::put($key, $otp, now()->addMinutes(10)); // 10 minutes expiry
        Cache::put($cooldownKey, now()->addSeconds(60), now()->addSeconds(60)); // 60s cooldown

        $this->notify(new SendOtpNotification($otp, $type));
    }

    public function verifyOtp($otp, $type = 'email')
    {
        $key = "otp_{$type}_{$this->email}";
        //get otp from cache
        $storedOtp = Cache::get($key);

        if ($storedOtp && $storedOtp == $otp) {
            //delete otp from cache
            Cache::forget($key);
            return true;
        }

        return false;
    }
}
