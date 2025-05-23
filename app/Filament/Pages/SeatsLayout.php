<?php

namespace App\Filament\Pages;

use App\Models\TableArrangement;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Support\Htmlable;

class SeatsLayout extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.pages.seats-layout';

    protected static ?int $navigationSort = 95;

    public ?array $data = [];

    public int $invitationId;

    public function mount()
    {
        $this->invitationId = Filament::getTenant()->toArray()['id'];
        $arrangement = \App\Models\TableArrangement::where('invitation_id', $this->invitationId)->first();
        $this->data['max_seats_per_table'] = $arrangement?->max_seats_per_table ?? null;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('max_seats_per_table')
                            ->label(__('translations.How many guests will be seated at each table?'))
                            ->numeric()
                            ->minValue(1)
                            ->afterStateUpdated(function ($livewire){
                                $livewire->dispatch('refreshTableListing');
                            })
                            ->live(onBlur: true)  // triggers only after input loses focus
                    ]),

                Actions::make([
                    Action::make('add_table')
                        ->label(__('translations.Add table'))
                        ->size(ActionSize::Small)
                        ->icon('heroicon-m-plus')
                        ->action(function () {
                            $this->dispatch('addTable');
                        })
                ]),
                Livewire::make(\App\Livewire\SeatsLayout::class, ['invitationId' => $this->invitationId])
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

    public function updatedData($value, $key)
    {
        if ($key === 'max_seats_per_table') {
            TableArrangement::updateOrCreate(
                ['invitation_id' => $this->invitationId],
                ['max_seats_per_table' => $value]
            );

            Notification::make()
                ->title(__('translations.Data saved successfully!'))
                ->success()
                ->send();
        }
    }
}
