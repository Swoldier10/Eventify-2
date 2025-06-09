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
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\SimplePage;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\HtmlString;
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
            ->form(function () {
                $templateClass = $this->invitationTemplate->class_name ?? \App\Livewire\PeaceInvitation::class;
                
                // Determine the correct route based on template class
                $templateRoute = match($templateClass) {
                    \App\Livewire\NaturalInvitation::class => 'natural-invitation',
                    \App\Livewire\PeaceInvitation::class => 'peace-invitation',
                    default => 'peace-invitation'
                };
                
                $previewUrl = url($templateRoute);
                
                return [
                    Placeholder::make('template_preview')
                        ->hiddenLabel()
                        ->content(new HtmlString(
                            '<iframe src="' . $previewUrl . '" width="100%" height="600px" style="border:none; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></iframe>'
                        )),
                ];
            })
            ->modalWidth(MaxWidth::SevenExtraLarge)
            ->modalHeading(__('translations.Preview'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('translations.Close'))
            ->extraModalWindowAttributes([
                'style' => 'margin-top: 2rem; z-index: 9999;',
                'class' => 'modal-container'
            ])
            ->action(fn() => null);
    }
}
