<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Form;
use Filament\Pages\Page;

class SeatsLayout extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.seats-layout';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Livewire::make(\App\Livewire\SeatsLayout::class)
            ])
            ->statePath('data');
    }
}
