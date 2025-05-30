<?php

namespace App\Filament\Pages\CustomizeTemplate;

use App\Filament\Pages\EditTemplate;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\IconPosition;
use Illuminate\Contracts\Support\Htmlable;

class DetailsOfCelebrants extends Page implements HasActions
{
    use InteractsWithActions;
    use HasPageSidebar;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.customize-template.details-of-celebrants';

    protected static bool $shouldRegisterNavigation = false;

    public static function sidebar(): \AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar
    {
        return EditTemplate::sidebar();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Livewire::make(\App\Livewire\CustomizeTemplate\DetailsOfCelebrants::class),
                Grid::make([
                    'default' => 2
                ])
                    ->schema([
                        Action::make('prev_step')
                            ->label(__('translations.Back'))
                            ->icon('icon-arrow-left')
                            ->iconPosition(IconPosition::Before)
                            ->extraAttributes([
                                'style' => 'float:left'
                            ])
                            ->action(function () {
                                $this->redirect(GeneralInfo::getUrl());
                            })
                            ->toFormComponent(),
                        Action::make('next_step')
                            ->label(__('translations.Next step'))
                            ->icon('icon-arrow-right')
                            ->iconPosition(IconPosition::After)
                            ->extraAttributes([
                                'style' => 'float:right'
                            ])
                            ->action(function () {
                                $this->redirect(Locations::getUrl());
                            })
                            ->toFormComponent(),
                    ]),
            ])
            ->statePath('data');
    }

    public function getTitle(): string|Htmlable
    {
        return __('translations.Details of the celebrants');
    }

    public function previewAction(): \Filament\Actions\Action
    {
        return EditTemplate::previewAction();
    }
}
