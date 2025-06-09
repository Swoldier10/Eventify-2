<?php

namespace App\Filament\Pages;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Table;
use App\Models\TableArrangement;
use Awcodes\TableRepeater\Header;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class SeatsLayout extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.pages.seats-layout';

    protected static ?int $navigationSort = 95;

    public ?array $data = [];

    public ?Invitation $invitation;

    public ?array $seatlessGuests = [];

    public function mount(): void
    {
        $this->invitation = Filament::getTenant();
        $arrangement = $this->invitation->tableArrangement;
        $this->data['max_seats_per_table'] = $arrangement?->max_seats_per_table ?? null;
        $this->data['tables'] = $this->invitation->tables->toArray();

        foreach ($this->data['tables'] as $key => $table) {
            $tableModel = Table::find($table['id']);
            $this->data['tables'][$key]['guests'] = $tableModel->guests->toArray();
        }
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
                            ->afterStateUpdated(function ($livewire) {
                                $livewire->dispatch('refreshTableListing');
                            })
                            ->live(onBlur: true)
                    ]),

                Actions::make([
                    Action::make('add_table')
                        ->label(__('translations.Add table'))
                        ->size(ActionSize::Small)
                        ->icon('heroicon-m-plus')
                        ->action(function ($livewire) {
                            $newTable = Table::create([
                                'invitation_id' => $this->invitation->id,
                                'table_number' => count($this->data['tables']) + 1,
                            ]);
                            $this->data['tables'][] = $newTable;
                            $livewire->dispatch('refreshTableListing');
                        })
                ]),
                ...$this->getTables()
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
            $tableArrangement = TableArrangement::where('invitation_id', $this->invitation->id)->first();

            if ($this->checkIfTableExceedMaxLimit($value)) {
                Notification::make('error')
                    ->title(__('translations.There are tables that exceed the maximum number of guests.'))
                    ->warning()
                    ->send();

                $this->data['max_seats_per_table'] = $tableArrangement->max_seats_per_table;
            } else {
                $tableArrangement->update([
                    'max_seats_per_table' => $value
                ]);

                Notification::make()
                    ->title(__('translations.Data saved successfully!'))
                    ->success()
                    ->send();
            }
        }
    }

    private function checkIfTableExceedMaxLimit(int $newLimit)
    {
        foreach ($this->data['tables'] as $table) {
            foreach ($table['guests'] as $guest) {
                if ($guest['nr_of_people'] + $guest['nr_of_kids'] > $newLimit) {
                    return true;
                }
            }
        }
        return false;
    }

    private function getTables(): array
    {
        $tables = [];
        foreach ($this->data['tables'] ?? [] as $index => $table) {
            $tables[] = Section::make('')
                ->schema([
                    Grid::make(2)
                        ->columns([
                            'default' => 2,
                            'sm' => 2,
                            'xl' => 2,
                            '2xl' => 2,
                        ])
                        ->schema([
                            Placeholder::make('table_number')
                                ->hiddenLabel()
                                ->content(function () use ($table) {
                                    $tableNumber = '<div class="rounded-full bg-black text-white font-bold text-4xl w-[50px] h-[50px] flex items-center justify-center">' . $table['table_number'] . '</div>';
                                    return new HtmlString('<div class="flex justify-between">' . $tableNumber . '</div>');
                                }),
                            Action::make('delete_table_' . $index)
                                ->iconButton()
                                ->icon('heroicon-m-trash')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->modalHeading(__('translations.Delete table'))
                                ->tooltip(__('translations.Delete table'))
                                ->action(function ($livewire) use ($index) {
                                    // delete table from db
                                    Table::find($this->data['tables'][$index]['id'])->delete();

                                    // remove table from data
                                    unset($this->data['tables'][$index]);

                                    // reIndex table numbers
                                    $tableNumber = 1;
                                    foreach ($this->data['tables'] as $key => $table) {
                                        Table::find($table['id'])->update(['table_number' => $tableNumber]);
                                        $this->data['tables'][$key]['table_number'] = $tableNumber;
                                        $tableNumber++;
                                    }

                                    $livewire->dispatch('refreshTableListing');
                                })
                                ->extraAttributes([
                                    'class' => 'float-right'
                                ])
                                ->toFormComponent()
                        ]),
                    \Awcodes\TableRepeater\Components\TableRepeater::make('tables.' . $index . '.guests')
                        ->hiddenLabel()
                        ->reorderable(false)
                        ->emptyLabel(__('translations.No guests'))
                        ->headers([
                            Header::make('guests')
                                ->label(__('translations.Guests')),
                            Header::make('kids_count')
                                ->label(__('translations.Number of children'))
                        ])
                        ->schema([
                            Placeholder::make('guests')
                                ->hiddenLabel()
                                ->content(fn(Get $get) => $get('name') . ($get('partner_name') ? ' & ' . $get('partner_name') : '')),
                            Placeholder::make('nr_of_kids')
                                ->hiddenLabel()
                                ->content(fn($state) => $state)
                        ])
                        ->addAction(function ($action) use ($table, $index) {
                            return $action
                                ->label(__('translations.Add guest'))
                                ->icon('heroicon-m-plus')
                                ->form([
                                    Select::make('guest_id')
                                        ->required()
                                        ->label(__('translations.Guests'))
                                        ->searchable()
                                        ->allowHtml()
                                        ->disableOptionWhen(fn(string $label) => str_contains($label, 'disabled-option'))
                                        ->options(function ($livewire) use($table){
                                            $tableAvailableSeats = $this->data['max_seats_per_table'];

                                            foreach ($table['guests'] ?? [] as $guest) {
                                                $tableAvailableSeats -= ($guest['nr_of_people'] + $guest['nr_of_kids']);
                                            }


                                            $seatedGuestsIds = [];

                                            foreach ($livewire->data['tables'] ?? [] as $table) {
                                                foreach ($table['guests'] ?? [] as $guest) {
                                                    $seatedGuestsIds[] = $guest['id'];
                                                }
                                            }

                                            // filter our guests that already have seats and those which wouldn't fit the table
                                            $tempGuests = $this->invitation->guests()->whereNotIn('id', $seatedGuestsIds)->get();

                                            $guestsThatDontFit = $tempGuests->filter(function ($guest) use ($tableAvailableSeats) {
                                                return ($guest->nr_of_people + $guest->nr_of_kids) > $tableAvailableSeats;
                                            });

                                            // format guests fields
                                            return $tempGuests->map(function ($guest) use($guestsThatDontFit){
                                                $namePart = $guest->name;
                                                $partnerPart = $guest->partner_name ? ' & ' . $guest->partner_name : '';
                                                $kidsPart = ($guest->nr_of_kids > 0 ? ' (+ ' . $guest->nr_of_kids . ' ' . __('translations.Children') . ')' : '');

                                                $dontFitIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 text-orange-500 disabled-option">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                                    </svg>
                                                    ';
                                                return [
                                                    'value' => $guest->id,
                                                    'label' => '<div class="flex gap-2 items-center">' . (in_array($guest['id'], $guestsThatDontFit->pluck('id')->toArray()) ? $dontFitIcon : '') . $namePart . $partnerPart . $kidsPart . '</div>',
                                                ];
                                            })->pluck('label', 'value');
                                        })
                                ])
                                ->modalSubmitActionLabel(__('translations.Add guest'))
                                ->action(function ($livewire, array $data) use ($table, $index) {
                                    $tableModel = Table::find($table['id']);
                                    $tableModel->guests()->attach($data['guest_id']);

                                    $livewire->data['tables'][$index]['guests'][] = Guest::find($data['guest_id'])->toArray();
                                    $livewire->dispatch('refreshTableListing');
                                });
                        })
                        ->deleteAction(function ($action) use ($table, $index) {
                            return $action
                                ->toolTip(__('translations.Delete guest'))
                                ->icon('heroicon-m-trash')
                                ->requiresConfirmation()
                                ->action(function ($livewire, $arguments) use ($table, $index) {
                                    $guest = $table['guests'][$arguments['item']];
                                    $tableModel = Table::find($table['id']);
                                    $tableModel->guests()->detach($guest['id']);

                                    unset($livewire->data['tables'][$index]['guests'][$arguments['item']]);
                                    $livewire->dispatch('refreshTableListing');
                                });
                        }),
                    Placeholder::make('tables.' . $index)
                        ->hiddenLabel()
                        ->content(function ($livewire) use ($table, $index) {
                            $guestCount = 0;

                            foreach ($livewire->data['tables'][$index]['guests'] ?? [] as $guest) {
                                $guestCount += $guest['nr_of_people'] + $guest['nr_of_kids'];
                            }
                            return new HtmlString('<div class="flex justify-between">' . $guestCount . ' / ' . $this->data['max_seats_per_table'] .  ' ' . __('translations.Guests') .'</div>');
                        })
                ]);
        }
        return $tables;
    }

    #[On('refreshTableListing')]
    public function refreshTableListing(): void
    {

    }
}
