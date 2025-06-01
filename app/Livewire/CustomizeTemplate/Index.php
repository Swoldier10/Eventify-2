<?php

namespace App\Livewire\CustomizeTemplate;

use App\Livewire\Components\PricingPlans;
use App\Livewire\PeaceInvitation;
use App\Models\Invitation;
use App\Models\InvitationTemplate;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\SimplePage;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;

class Index extends SimplePage implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected ?string $maxWidth = '';
    protected static string $view = 'livewire.customize-template.index';
    protected static string $layout = 'components.layouts.app';

    public ?string $eventType;

    public Model $invitationTemplate;

    public int $selectedPageIndex = 0;

    public function mount($eventType = null, int $invitationTemplateId = null)
    {
        $this->eventType = $eventType;
        $this->invitationTemplate = InvitationTemplate::findOrFail($invitationTemplateId);
    }

    public function selectPage($index): void
    {
        $this->selectedPageIndex = $index;
    }

    #[On('nextPage')]
    public function nextPage(bool $afterValidation = false): void
    {
        if ($afterValidation) {
            $this->selectedPageIndex++;
            return;
        }

        switch ($this->selectedPageIndex) {
            case 0:
                $this->dispatch('validateData')->to(GeneralInfo::class);
                break;
            case 1:
                $this->dispatch('validateData')->to(DetailsOfCelebrants::class);
                break;
            case 2:
                $this->dispatch('validateData')->to(Locations::class);
                break;
            case 3:
                $this->dispatch('validateData')->to(AdvancedCustomization::class);
                break;
            case 4:
                $this->dispatch('validateData')->to(PricingPlans::class);
                break;
            case 5:
                $this->dispatch('validateData')->to(InvitationSettings::class);
                break;
            default:
                $this->selectedPageIndex++;
                break;
        }
    }

    public function prevPage(): void
    {
        $this->selectedPageIndex--;
    }

    public function viewTemplate(): Action
    {
        return Action::make('viewTemplate')
            ->label(__('translations.Preview'))
            ->icon('heroicon-m-eye')
            ->form(function (){
                return [Livewire::make($this->invitationTemplate->class_name)];
            })
            ->modalWidth('50%')
            ->action(fn() => null);
    }
}
