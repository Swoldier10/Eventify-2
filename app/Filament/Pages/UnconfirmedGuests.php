<?php

namespace App\Filament\Pages;

use App\Models\Invitation;
use App\Models\UnconfirmedGuest;
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
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class UnconfirmedGuests extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.unconfirmed-guests';
    protected static ?int $navigationSort = 93;

    public Model $record;

    public function mount(): void
    {
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
                ExportAction::make()
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
                        ExcelExport::make(__('translations.In order of confirmation'))
                            ->withFilename(function () {
                                return __('translations.Unconfirmed guests');
                            })
                            ->fromTable(),
                    ])
            ]);

    }

    protected function getTableQuery()
    {
        $loggedUserId = Auth::user()->id;
        return UnconfirmedGuest::query()->where('invitation_id', $this->record?->id)
            ->where('user_id', $loggedUserId)
            ->orderBy('name');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label(__('translations.Name'))
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('name', 'like', "%{$search}%");
                })
                ->sortable(),

            TextColumn::make('phone_number')
                ->label(__('translations.Phone number'))
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('phone_number', 'like', "%{$search}%");
                }),

            TextColumn::make('note')
                ->label(__('translations.Note')),

        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('translations.Unconfirmed guests');
    }

    public function getTitle(): string|Htmlable
    {
        return __('translations.List of guests who will not be able to attend');
    }
}
