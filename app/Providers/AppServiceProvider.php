<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Donation;
use App\Models\RescueReport;
use App\Models\AdoptionRequest;
use App\Models\Pet;
use App\Policies\DonationPolicy;
use App\Policies\RescueReportPolicy;
use App\Policies\AdoptionRequestPolicy;
use App\Policies\PetPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(\Laravel\Sanctum\PersonalAccessToken::class);

        Gate::policy(Donation::class, DonationPolicy::class);
        Gate::policy(RescueReport::class, RescueReportPolicy::class);
        Gate::policy(AdoptionRequest::class, AdoptionRequestPolicy::class);
        Gate::policy(Pet::class, PetPolicy::class);
    }
}
