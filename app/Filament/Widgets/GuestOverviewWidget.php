<?php

namespace App\Filament\Widgets;

use App\Models\Guest;
use App\Models\UnconfirmedGuest;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class GuestOverviewWidget extends AdvancedStatsOverviewWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make(__('translations.Confirmed guests'), function (){
                $loggedUserId = $this->getLoggedUserId();
                $invitationId = $this->getCurrentInvitationId();

                return Guest::where('user_id', $loggedUserId)
                    ->where('invitation_id', $invitationId)
                    ->count();
            })
                ->icon('heroicon-o-user')
                ->progress(69)
                ->progressBarColor('success')
                ->iconBackgroundColor('success')
                ->chartColor('success')
                ->iconPosition('start')
                ->description(__('translations.This is the total number of people who have confirmed so far'))
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('success')
                ->iconColor('success'),

            Stat::make(__('translations.Number of families with children'), function (){
                $loggedUserId = $this->getLoggedUserId();
                $invitationId = $this->getCurrentInvitationId();

                return Guest::where('user_id', $loggedUserId)
                    ->where('invitation_id', $invitationId)
                    ->where('nr_of_kids', '>', 0)
                    ->count();
            })
                ->icon('heroicon-o-face-smile')
                ->description(__('translations.This is the total number of families who will be attending with at least one child'))
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('primary')
                ->iconColor('warning'),

            Stat::make(__('translations.Not attending'), function (){
                $loggedUserId = $this->getLoggedUserId();
                $invitationId = $this->getCurrentInvitationId();

                return UnconfirmedGuest::where('user_id', $loggedUserId)
                    ->where('invitation_id', $invitationId)
                    ->count();
            })
                ->icon('heroicon-o-shield-exclamation')
                ->description(__('translations.This is the total number of people who have confirmed that they will NOT attend so far'))
                ->descriptionIcon('heroicon-o-chevron-down', 'before')
                ->descriptionColor('danger')
                ->iconColor('danger'),

        ];
    }

    public function getCurrentInvitationId(): ?string
    {
        $requestData = request()->all();
        $snapshot = json_decode($requestData['components'][0]['snapshot'], true);
        $path = $snapshot['memo']['path'] ?? null;

        return $path ? explode('/', $path)[1] : null;
    }

    public function getLoggedUserId()
    {
        return Auth::user()->id;
    }
}
