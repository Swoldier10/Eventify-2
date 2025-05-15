<?php

namespace App\Filament\Pages;

use App\Exports\ArrangedByTablesExport;
use App\Models\Guest;
use App\Models\Invitation;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class DownloadGuestList extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static string $view = 'filament.pages.download-guest-list';
    protected static ?int $navigationSort = 92;

    public Model $record;

    public function mount(): void
    {
        // Get the current URL path (excluding the domain)
        $path = Request::path();
        $segments = explode('/', $path);
        $invitationId = $segments[1];
        $this->record = Invitation::findOrFail($invitationId);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->headerActions([
                \Filament\Tables\Actions\Action::make('Export Arranged by Tables')
                    ->action(fn() => $this->exportArrangedByTables())
                    ->hidden(function () {
                        $result = $this->getTableQuery();
                        if ($result->count() == 0) {
                            return true;
                        }
                        return false;
                    })
                    ->icon('heroicon-o-arrow-down-tray')
                    ->label(__('translations.Download the seating arrangement')),


                \pxlrbt\FilamentExcel\Actions\Tables\ExportAction::make()
                    ->label(__('translations.Download'))
                    ->modalSubmitActionLabel(__('translations.Download'))
                    ->hidden(function () {
                        $result = $this->getTableQuery();
                        if ($result->count() == 0) {
                            return true;
                        }
                        return false;
                    })
                    ->exports([
                        ExcelExport::make(__('translations.Alphabetical'))
                            ->withFilename(function () {
                                return __('translations.Alphabetical');
                            })
                            ->fromTable()
                            ->modifyQueryUsing(function () {
                                $loggedUserId = Auth::user()?->id;
                                return Guest::where('user_id', $loggedUserId)
                                    ->where('invitation_id', $this->record?->id)
                                    ->orderBy('name');
                            }),
                        ExcelExport::make(__('translations.In order of confirmation'))
                            ->withFilename(function () {
                                return __('translations.Guest list');
                            })
                            ->fromTable(),
                    ])
            ]);


    }

    public function exportArrangedByTables()
    {
        return Excel::download(new ArrangedByTablesExport($this->record?->id), __('translations.Seating arrangement') . '.xlsx');
    }

    protected function getTableQuery()
    {
        $loggedUserId = Auth::user()->id;
        return Guest::query()->where('invitation_id', $this->record?->id)
            ->where('user_id', $loggedUserId)
            ->orderBy('name');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('nr_of_people')
                ->label(__('translations.Number of people')),

            TextColumn::make('name')
                ->label(__('translations.Name'))
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('name', 'like', "%{$search}%");
                })
                ->sortable(),

            TextColumn::make('partner_name')
                ->label(__('translations.Partner name'))
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('partner_name', 'like', "%{$search}%");
                })
                ->sortable(),

            TextColumn::make('phone_number')
                ->label(__('translations.Phone number'))
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('phone_number', 'like', "%{$search}%");
                }),

            TextColumn::make('nr_of_kids')
                ->label(__('translations.Number of kids')),

            TextColumn::make('need_accomodation')
                ->label(__('translations.Need accommodation'))
                ->getStateUsing(function ($record) {
                    if ($record?->need_accomodation) {
                        return __('translations.Yes');
                    }
                    return __('translations.No');
                })
                ->sortable(),

            TextColumn::make('vegetarian_menu')
                ->label(__('translations.Vegetarian menu'))
                ->getStateUsing(function ($record) {
                    if ($record?->vegetarian_menu) {
                        return __('translations.Yes');
                    }
                    return __('translations.No');
                })
                ->sortable(),

            TextColumn::make('note')
                ->label(__('translations.Note')),

            TextColumn::make('additional_question_answer')
                ->label(__('translations.Additional question answer')),

        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('translations.Download guest list');
    }

    public function getTitle(): string|Htmlable
    {
        return __('translations.List of guests who have confirmed their attendance');
    }
}
