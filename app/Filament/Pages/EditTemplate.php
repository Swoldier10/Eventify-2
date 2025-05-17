<?php

namespace App\Filament\Pages;

use App\Filament\Pages\CustomizeTemplate\AdvancedCustomization;
use App\Filament\Pages\CustomizeTemplate\DetailsOfCelebrants;
use App\Filament\Pages\CustomizeTemplate\GeneralInfo;
use App\Filament\Pages\CustomizeTemplate\InvitationSettings;
use App\Filament\Pages\CustomizeTemplate\InvitationType;
use App\Filament\Pages\CustomizeTemplate\Locations;
use App\Models\Invitation;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Request;

class EditTemplate extends Page
{
    use HasPageSidebar;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.edit-template';

    protected static ?int $navigationSort = 92;

    protected static bool $shouldRegisterNavigation = false;

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

    public static function getCurrentInvitation()
    {
        // Get the current URL path (excluding the domain)
        $path = Request::path();
        $segments = explode('/', $path);
        $invitationId = $segments[1];
        return Invitation::findOrFail($invitationId);
    }
}
