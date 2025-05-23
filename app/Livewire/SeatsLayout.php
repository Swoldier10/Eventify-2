<?php

namespace App\Livewire;

use App\Models\Guest;
use App\Models\Table;
use App\Models\TableArrangement;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class SeatsLayout extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public array $tables = [];

    public $guests;

    public $invitationId;

    public int $maxSeatsPerTable;

    public $originalTables;

    public function mount($invitationId): void
    {
        $this->invitationId = $invitationId;

        $this->loadTables();
        $this->loadGuests();

        $this->maxSeatsPerTable = TableArrangement::where('invitation_id', $invitationId)
            ->value('max_seats_per_table') ?? 10;

        $this->originalTables = $this->tables;

        $this->form->fill();
    }

    #[On('loadGuests')]
    public function loadGuests()
    {
        $this->guests = Guest::where('invitation_id', $this->invitationId)
            ->where('user_id', Auth::id())
            ->get();
    }

    #[On('loadTables')]
    public function loadTables(): void
    {
        $this->tables = Table::where('invitation_id', $this->invitationId)
            ->with('guests')
            ->get()
            ->mapWithKeys(fn($table) => [
                $table->id => [
                    'id' => $table->id,
                    'table_number' => $table->table_number,
                    'guests' => $table->guests->map(fn($g) => ['name' => $g->id])->toArray(),
                ]
            ])
            ->toArray();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ...$this->getGuestsArrangedByTable()
            ])
            ->statePath('data');
    }

    public function getGuestsArrangedByTable(): array
    {
        $layout = [];
        foreach ($this->tables as $index => $data) {
            $availableGuestOptions = $this->getAvailableGuestOptionsForTable($data);

            $layout[] =
                Section::make(__('translations.Table') . ' ' . ($data['table_number']))
                    ->key($index)
                    ->description(__('translations.Choose the guests you would like to seat at this table'))
                    ->headerActions([
                        Action::make("delete_table_{$index}")
                            ->hiddenLabel()
                            ->icon('heroicon-m-trash')
                            ->color('danger')
                            ->size(ActionSize::Small)
                            ->action(function ($livewire) use ($data) {
                                $this->deleteTable($data['id']);
                                $livewire->dispatch('refreshTableListing');
                            }),
                    ])
                    ->schema([
                        TableRepeater::make("tables.{$index}.guests")
                            ->schema([
                                Select::make("name")
                                    ->label(__('translations.Name'))
                                    ->options(
                                        $this->guests
                                            ->map(function ($guest) {
                                                $name = $guest->name;

                                                if ($guest->partner_name || $guest->nr_of_kids > 0) {
                                                    $name .= ' - ';

                                                    if ($guest->partner_name) {
                                                        $name .= $guest->partner_name;
                                                    }

                                                    if ($guest->nr_of_kids > 0) {
                                                        if ($guest->partner_name) {
                                                            $name .= ' ';
                                                        }
                                                        $name .= '(+' . $guest->nr_of_kids . ' ' . ($guest->nr_of_kids > 1 ? __('translations.Children') : __('translations.Child')) . ')';
                                                    }
                                                }

                                                return [
                                                    'name' => $name,
                                                    'id' => $guest->id,
                                                ];
                                            })
                                            ->pluck('name', 'id')
                                    )
                                    ->reactive()
                                    ->disabled(fn($get) => $this->isGuestAlreadyAssignedToTable($index, $get('name')))
                                    ->afterStateUpdated(fn($state) => $this->assignGuestToTable($index, $state))
                                    ->disableOptionWhen(fn(string $value): bool => in_array($value, collect($this->tables)->pluck('guests')->flatten(1)->pluck('name')->unique()->toArray())),
                            ])
                            ->default($data['guests'] ?? [])
                            ->hiddenLabel()
                            ->reorderable(false)
                            ->minItems(1)
                            ->maxItems($this->maxSeatsPerTable)
                            ->columnSpan('full')
                            ->addActionLabel(__('translations.Add guest'))
                            ->deleteAction(function ($action) use ($data) {
                                return $action
                                    ->extraAttributes([
                                        'class' => 'my-auto'
                                    ])
                                    ->action(function ($arguments, $state, $livewire) use ($data) {
                                        $item = $state[$arguments['item']] ?? null;
                                        if (!$item || empty($item['name'])) {
                                            return;
                                        }

                                        $guestId = $item['name'];
                                        if (!$guestId) {
                                            return;
                                        }

                                        $guest = Guest::find($guestId);
                                        if ($guest) {
                                            $guest->tables()->detach();
                                        }

                                        unset($livewire->data['tables'][$data['id']]['guests'][$arguments['item']]);
                                        foreach ($livewire->data['tables'] ?? [] as $index => $tableData) {
                                            $this->tables[$index]['guests'] = $livewire->data['tables'][$index]['guests'];
                                        }

                                        Notification::make()
                                            ->success()
                                            ->title(__('translations.Guest removed from table'))
                                            ->send();
                                    });
                            }),
                        Placeholder::make('table_guest_count')
                            ->hiddenLabel()
                            ->extraAttributes([
                                'class' => 'mx-auto'
                            ])
                            ->content(function ($livewire) use ($index) {
//                                $guests = $livewire->tables[$index]['guests'];
                                if (count($livewire->tables))
                                {
//                                    $guests = head($livewire->tables)['guests'];
                                    $guests = $livewire->tables[$index]['guests'];
                                    $guestCount = 0;

                                    foreach ($guests ?? [] as $guestData) {
                                        $guest = Guest::find($guestData['name']);

                                        if (!$guest) {
                                            continue;
                                        }

                                        $guestCount += 1;

                                        if (!empty($guest->partner_name)) {
                                            $guestCount += 1;
                                        }

                                        if (!empty($guest->nr_of_kids)) {
                                            $guestCount += $guest->nr_of_kids;
                                        }
                                    }

                                    return $guestCount . ' / ' . $this->maxSeatsPerTable . ' ' . __('translations.Guests');
                                }
                            })
                    ])
                    ->collapsible();
        }

        return $layout;
    }

    public function isGuestAlreadyAssignedToTable($tableIndex, $guestId): bool
    {
        if (!isset($this->tables[$tableIndex]['id']) || !$guestId) {
            return false;
        }

        $tableId = $this->tables[$tableIndex]['id'];

        return DB::table('guest_table')
            ->where('table_id', $tableId)
            ->where('guest_id', $guestId)
            ->exists();
    }

    protected function getAvailableGuestOptionsForTable(array $data): \Illuminate\Support\Collection
    {
        $options = $this->guests->pluck('name', 'id');

        $assignedGuestIds = collect($this->tables)->pluck('guests')->flatten(1)->pluck('name')->unique()->toArray();
        $selectedGuestIds = collect($this->tables['guests'] ?? [])->pluck('name')->all();

        $unassignedGuestIds = $options->keys()->diff($assignedGuestIds)->all();

        // Merge unassigned + selected to ensure selected names still show
        return $options->only(array_unique(array_merge(
            $unassignedGuestIds,
            $selectedGuestIds
        )));
    }


    public function assignGuestToTable($tableId, $guestId): void
    {
        $guest = Guest::find($guestId);
        $table = Table::with('guests')->find($tableId);

        if (!$guest || !$table) {
            return;
        }

        // Calculate how many seats this guest requires
        $seatsReserved = 1;
        $seatsReserved += max(($guest->nr_of_people - 1), 0);
        $seatsReserved += $guest->nr_of_kids ? ($guest->nr_of_kids ?? 0) : 0;

        // Get max seats allowed per table from invitation (assuming relation exists)
        $maxSeats = $table->invitation->tableArrangement->max_seats_per_table ?? 10;

        // Calculate how many seats are already occupied at a specific table by all guests currently assigned to it
        $currentSeats = $table->guests->sum(function ($g) {
            return $g->pivot->seats_reserved;
        });

        // Check if the table has room
        if ($currentSeats + $seatsReserved > $maxSeats) {
            Notification::make()
                ->title(__('translations.This table is full!'))
                ->danger()
                ->send();
            return;
        }

        $this->tables[$tableId]['guests'][] = [
            'name' => $guestId
        ];

        // Attach guest to table
        $table->guests()->attach($guest->id, ['seats_reserved' => $seatsReserved]);

        // Refresh component
        $this->dispatch('refreshTableListing');
    }


    public function deleteTable($id): void
    {
        $table = Table::find($id);

        if ($table) {
            $table->guests()->detach();
            $table->delete();
        }

        // Remove from local state (UI refresh)
        $this->tables = array_values(array_filter($this->tables, fn($table) => $table['id'] != $id));

        // Re-number remaining tables (for UI display)
        foreach ($this->tables as $index => &$tableData) {
            $newNumber = $index + 1;

            Table::where('id', $tableData['id'])->update([
                'table_number' => $newNumber,
            ]);

            $tableData['table_number'] = $newNumber;
        }
    }

    #[On('addTable')]
    public function addTable(): void
    {
        $newTable = Table::create([
            'invitation_id' => $this->invitationId,
            'table_number' => count($this->tables) + 1,
        ]);

        $guests = $newTable->guests()->exists() ? $newTable->guests()->pluck('id')->toArray() : [];

        $this->tables[] = [
            'id' => $newTable->id,
            'table_number' => count($this->tables) + 1,
            'guests' => $guests,
        ];
    }

    #[On('refreshTableListing')]
    public function refreshTableListing(): void
    {
        $this->maxSeatsPerTable = TableArrangement::where('invitation_id', $this->invitationId)
            ->value('max_seats_per_table') ?? 10;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.seats-layout');
    }
}
