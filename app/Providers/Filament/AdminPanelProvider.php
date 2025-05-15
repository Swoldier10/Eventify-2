<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\GuestList;
use App\Filament\Widgets\GuestOverviewWidget;
use App\Models\Invitation;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Tobiasla78\FilamentSimplePages\FilamentSimplePagesPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandLogo(asset('images/Logo_Eventify.png'))
            ->darkModeBrandLogo(asset('images/Logo Eventify_Dark.png'))
            ->brandLogoHeight('4rem')
            ->brandName('Eventify')
            ->sidebarCollapsibleOnDesktop()
            ->tenant(Invitation::class)
            ->viteTheme('resources/css/app.css')
            ->registration()
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->login()
            ->colors([
                'primary' => '#d2ad57',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                GuestOverviewWidget::class,
                GuestList::class
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugin(
                FilamentEditProfilePlugin::make()
                    ->shouldShowAvatarForm()
                    ->shouldShowBrowserSessionsForm(false)
                    ->shouldShowDeleteAccountForm(false)
                    ->setIcon('heroicon-o-user')
                    ->setNavigationLabel(__('translations.My Profile'))
                    ->setTitle(__('translations.My Profile')),
            )
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()?->name)
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
