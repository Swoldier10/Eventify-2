<?php

namespace App\Filament\Widgets;

use App\Models\Guest;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

class GuestList extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('translations.Confirmed guest list'))
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        $loggedUserId = Auth::user()->id;
        $invitationId = $this->getCurrentInvitationId();

        return Guest::query()->where('invitation_id', $invitationId)
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

    public function getCurrentInvitationId(): ?string
    {
        $requestData = request()->all();
        $snapshot = json_decode($requestData['components'][0]['snapshot'], true);
        $path = $snapshot['memo']['path'] ?? null;

        return $path ? explode('/', $path)[1] : null;
    }
}
