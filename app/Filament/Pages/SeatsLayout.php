<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class SeatsLayout extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.pages.seats-layout';

    protected static ?int $navigationSort = 95;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Livewire::make(\App\Livewire\SeatsLayout::class)
            ])
            ->statePath('data');
    }

    public static function getNavigationLabel(): string
    {
        return __('translations.Seating arrangement');
    }

    public function getTitle(): string|Htmlable
    {
        return __('translations.Seating arrangement');
    }
}
