<?php

namespace App\Livewire\CustomizeTemplate;

use App\Filament\Pages\EditTemplate;
use App\Models\Invitation;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;
use Livewire\Component;


class GeneralInfo extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $eventType;

    public function mount($eventType = null): void
    {
        EditTemplate::initData($this->data, $eventType);
        $this->eventType = $eventType;
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('title')
                            ->hiddenLabel()
                            ->columnSpan(2)
                            ->content(function () {
                                $title = __('translations.General info');
                                $subTitle = __('translations.The first step is to establish a few essential details about your invitation');

                                return new HtmlString('
                                    <div class="flex justify-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                             stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z"/>
                                        </svg>
                                    </div>

                                    <h5 class="text-xl font-medium text-gray-900 dark:text-white text-center mb-1">' . $title . '</h5>
                                    <p class="text-gray-400 text-center block dark:text-gray-300">' . $subTitle . '</p>'
                                );
                            }),
                        TextInput::make('name')
                            ->live(onBlur: true)
                            ->label(__('translations.The title of the invitation'))
                            ->columnSpan(2)
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.This title will be displayed to the guests during distribution.'))
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                            ]),
                        TextInput::make('email')
                            ->email()
                            ->live(onBlur: true)
                            ->label(__('translations.Email'))
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1,
                            ])
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.The email address where we will send notifications about your invitation.'))
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                                'email' => __('translations.Please enter a valid email address.'),
                            ]),
                        TextInput::make('secondary_email')
                            ->email()
                            ->live(onBlur: true)
                            ->label(__('translations.Secondary Email'))
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1,
                            ])
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.The secondary email address where we will send notifications about your invitation.'))
                    ]),
            ])
            ->statePath('data');
    }

    public function updatedData(): void
    {
        if ($invitation = Filament::getTenant()) {
            $data = $this->form->getState();

            foreach (Invitation::$generalInfoFields as $field) {
                $invitation->{$field} = $data[$field];
            }

            $invitation->save();
        } else {
            $cachedData = Cache::get('eventify-cached-data');

            foreach ($this->data ?? [] as $key => $value) {
                $cachedData[$key] = $value;
            }

            Cache::put('eventify-cached-data', $cachedData);
        }
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.customize-template.general-info');
    }

    #[On('validateData')]
    public function validateData(): void
    {
        $this->form->getState();
        $this->dispatch('nextPage', afterValidation: true)->to(Index::class);
    }
}
