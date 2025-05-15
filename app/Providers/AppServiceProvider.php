<?php

namespace App\Providers;

use App\Http\Responses\RegisterResponse;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RegistrationResponse::class, RegisterResponse::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
