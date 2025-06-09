<?php

namespace App\Filament\Pages;

use App\Filament\Pages\CustomizeTemplate\AdvancedCustomization;
use App\Filament\Pages\CustomizeTemplate\DetailsOfCelebrants;
use App\Filament\Pages\CustomizeTemplate\GeneralInfo;
use App\Filament\Pages\CustomizeTemplate\InvitationSettings;
use App\Filament\Pages\CustomizeTemplate\Locations;
use App\Models\Invitation;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class EditTemplate extends Page implements HasActions
{
    use HasPageSidebar, InteractsWithActions;

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
        return
            FilamentPageSidebar::make()
//            ->topbarNavigation()
            ->setNavigationItems([
//                Action::make('viewTemplate')
//                    ->label(__('translations.Preview'))
//                    ->icon('heroicon-m-eye')
//                    ->form([
//                        TextInput::make('input')
//                    ])
//                    ->action(fn() => dd('dadjajdaj')),

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

    public static function initData(&$data, &$eventType): void
    {
        if (Filament::getTenant()) {
            $data = Filament::getTenant()->toArray();
            $eventType = match (Filament::getTenant()->event_type_id) {
                1 => 'wedding',
                2 => 'baptism',
                default => 'party',
            };
        } else {
            $data = Cache::get('eventify-cached-data');
        }
    }

    public static function previewAction(){
        return Action::make('preview')
            ->label(__('translations.Preview'))
            ->icon('heroicon-m-eye')
            ->form(function () {
                $invitation = static::getCurrentInvitation();
                $templateClass = $invitation->invitationTemplate->class_name ?? \App\Livewire\PeaceInvitation::class;
                
                // Determine the correct route based on template class
                $templateRoute = match($templateClass) {
                    \App\Livewire\NaturalInvitation::class => 'natural-invitation',
                    \App\Livewire\PeaceInvitation::class => 'peace-invitation',
                    default => 'peace-invitation'
                };
                
                $previewUrl = url($templateRoute);
                
                return [
                    \Filament\Forms\Components\Placeholder::make('template_preview')
                        ->hiddenLabel()
                        ->content(new \Illuminate\Support\HtmlString(
                            '<iframe src="' . $previewUrl . '" width="100%" height="600px" style="border:none; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></iframe>'
                        )),
                ];
            })
            ->modalWidth(MaxWidth::SevenExtraLarge)
            ->modalHeading(__('translations.Preview'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('translations.Close'))
            ->extraModalWindowAttributes([
                'style' => 'margin-top: 2rem; z-index: 9999;',
                'class' => 'modal-container'
            ])
            ->action(fn() => null);
    }
}
