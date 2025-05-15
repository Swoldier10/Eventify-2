<?php

namespace App\Filament\Pages\CustomizeTemplate;

use App\Filament\Pages\EditTemplate;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\IconPosition;
use Illuminate\Contracts\Support\Htmlable;

class InvitationSettings extends Page
{
    use HasPageSidebar;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.customize-template.invitation-settings';
    protected static bool $shouldRegisterNavigation = false;

    public static function sidebar(): \AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar
    {
        return EditTemplate::sidebar();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Livewire::make(\App\Livewire\CustomizeTemplate\InvitationSettings::class),
                Grid::make(2)
                    ->schema([
                        Action::make('prev_step')
                            ->label(__('translations.Back'))
                            ->icon('icon-arrow-left')
                            ->iconPosition(IconPosition::Before)
                            ->extraAttributes([
                                'style' => 'float:left'
                            ])
                            ->action(function () {
                                $this->redirect(AdvancedCustomization::getUrl());
                            })
                            ->toFormComponent(),
                    ]),
            ])
            ->statePath('data');
    }

    public function getTitle(): string|Htmlable
    {
        return __('translations.Guest settings');
    }
}
