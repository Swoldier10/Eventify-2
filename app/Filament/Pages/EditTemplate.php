<?php

namespace App\Filament\Pages;

use App\Filament\Pages\CustomizeTemplate\AdvancedCustomization;
use App\Filament\Pages\CustomizeTemplate\DetailsOfCelebrants;
use App\Filament\Pages\CustomizeTemplate\GeneralInfo;
use App\Filament\Pages\CustomizeTemplate\InvitationSettings;
use App\Filament\Pages\CustomizeTemplate\InvitationType;
use App\Filament\Pages\CustomizeTemplate\Locations;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class EditTemplate extends Page
{
    use HasPageSidebar;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.edit-template';

    public static function getNavigationLabel(): string
    {
        return __('translations.Edit invitation');
    }

    public function getTitle(): string|Htmlable
    {
        return __('translations.Edit invitation');
    }

    public static function sidebar(): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle('Application Settings')
            ->setDescription('general, admin, website, sms, payments, notifications, shipping')
            ->setNavigationItems([
                PageNavigationItem::make(__('translations.General info'))
                    ->url(GeneralInfo::getUrl())
                    ->icon('icon-identification')
                    ->isActiveWhen(function () {
                        return request()->routeIs(GeneralInfo::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make(__('translations.Details of the celebrants'))
                    ->url(DetailsOfCelebrants::getUrl())
                    ->icon('icon-document-magnifying-glass')
                    ->isActiveWhen(function () {
                        return request()->routeIs(DetailsOfCelebrants::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make(__('translations.Locations'))
                    ->url(Locations::getUrl())
                    ->icon('icon-map-pin')
                    ->isActiveWhen(function () {
                        return request()->routeIs(Locations::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make(__('translations.Advanced customization'))
                    ->url(AdvancedCustomization::getUrl())
                    ->icon('icon-pencil-square')
                    ->isActiveWhen(function () {
                        return request()->routeIs(AdvancedCustomization::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make(__('translations.Guest settings'))
                    ->url(InvitationSettings::getUrl())
                    ->icon('heroicon-o-cog-6-tooth')
                    ->isActiveWhen(function () {
                        return request()->routeIs(InvitationSettings::getRouteName());
                    })
                    ->visible(true),
            ]);
    }
}
