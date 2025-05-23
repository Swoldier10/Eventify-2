<?php

namespace App\Providers;

use App\Http\Responses\RegisterResponse;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Support\Facades\FilamentColor;
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
        FilamentColor::register([
            'primary' => [
                50 => '235, 202, 126',
                100 => '235, 202, 126',
                200 => '235, 202, 126',
                300 => '235, 202, 126',
                400 => '235, 202, 126',
                500 => '211, 180, 106',
                600 => '235, 202, 126',
                700 => '235, 202, 126',
                800 => '235, 202, 126',
                900 => '235, 202, 126',
                950 => '235, 202, 126',
            ]
        ]);
    }
}
