<?php

namespace App\Livewire\CustomizeTemplate;

use App\Models\Invitation;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\SimplePage;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Index extends SimplePage implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected ?string $maxWidth = '';
    protected static string $view = 'livewire.customize-template.index';
    protected static string $layout = 'components.layouts.app';

    public ?string $eventType;

    public Model $invitation;

    public int $selectedPageIndex = 0;

    public function mount($eventType=null)
    {
        $this->eventType = $eventType;
    }

    public function selectPage($index): void
    {
        $this->selectedPageIndex = $index;
    }

    public function nextPage(): void
    {
        $this->selectedPageIndex++;
    }

    public function prevPage(): void
    {
        $this->selectedPageIndex--;
    }

    public function viewTemplate(): Action
    {
        return Action::make('viewTemplate')
            ->label(__('translations.View template'))
            ->color(Color::hex('#d2ad57'))
            ->form([
                TextInput::make('input')
            ])
            ->action(fn () => dd('dadjajdaj'));
    }

    public function toLogin(): void
    {
        $this->redirect(Filament::getRegistrationUrl());
    }

}
