<?php

namespace App\Livewire\CustomizeTemplate;

use App\Livewire\Components\PricingPlans;
use App\Models\Plan;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;
use Livewire\Component;

class InvitationType extends Component implements HasForms
{
    use InteractsWithForms;

    public array $data = [];

    public function mount(): void
    {
        $data = [];
        foreach (Plan::all() ?? [] as $plan){
            $data[$plan->id] = [
                'name' => $plan->name,
                'price' => $plan->price,
            ];

            foreach ($plan->features ?? [] as $feature){
                $data[$plan->id]['specs'][] = $feature->name;
            }
        }

        $this->data['plans'] = $data;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.customize-template.invitation-type');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                ->schema([
                    Placeholder::make('title')
                        ->columnSpan(2)
                        ->hiddenLabel()
                        ->content(function () {
                            $title = __('translations.Invitation type');
                            return new HtmlString('
                                 <div class="flex justify-center mb-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M21.75 9v.906a2.25 2.25 0 0 1-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 0 0 1.183 1.981l6.478 3.488m8.839 2.51-4.66-2.51m0 0-1.023-.55a2.25 2.25 0 0 0-2.134 0l-1.022.55m0 0-4.661 2.51m16.5 1.615a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V8.844a2.25 2.25 0 0 1 1.183-1.981l7.5-4.039a2.25 2.25 0 0 1 2.134 0l7.5 4.039a2.25 2.25 0 0 1 1.183 1.98V19.5Z"/>
                                    </svg>
                                </div>

                                <h5 class="text-xl font-medium text-gray-900 text-center mb-6">' . $title . '</h5>');
                        }),
                    Livewire::make(PricingPlans::class, [
                        'plans' => $this->data['plans']
                    ])
                ])
            ])
            ->statePath('data');
    }

    #[On('validateData')]
    public function validateData(): void
    {
        $this->form->getState();
        $this->dispatch('nextPage', afterValidation: true)->to(Index::class);
    }
}
