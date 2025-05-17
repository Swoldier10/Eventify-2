<?php

namespace App\Livewire\CustomizeTemplate;

use App\Filament\Pages\EditTemplate;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tapp\FilamentGoogleAutocomplete\Forms\Components\GoogleAutocomplete;

class Locations extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $eventType;

    public function mount($eventType=null): void
    {
        if ($eventType === null) {
            $eventTypeId = EditTemplate::getCurrentInvitation()->event_type_id;

            $this->eventType = match ($eventTypeId) {
                1 => 'wedding',
                2 => 'baptism',
                default => 'party',
            };
        } else {
            $this->eventType = $eventType;
        }

        $cachedData = Cache::get('eventify-cached-data');
        $this->form->fill($cachedData);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('title')
                            ->columnSpan(2)
                            ->hiddenLabel()
                            ->content(function () {
                                $title = __('translations.Locations');
                                $description = __("translations.Let's choose the location where the magic of this event will come to life.");
                                return new HtmlString('
                                        <div class="flex justify-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                 stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                            </svg>
                                        </div>

                                        <h5 class="text-xl font-medium text-gray-900 text-center mb-1 dark:text-white">' . $title . '</h5>
                                        <p class="text-gray-400 text-center block dark:text-gray-300">' . $description . '</p>');
                            }),
                        Placeholder::make('')
                            ->columnSpan(2)
                            ->content(function () {
                                return new HtmlString('<span class="font-bold text-lg">' . __('translations.Civil wedding') . '</span>');
                            })
                            ->hiddenLabel(),
                        GoogleAutocomplete::make('civil_wedding_google_search')
                            ->columnSpan(2)
                            ->autocompleteLabel(__('translations.Search address'))
                            ->autocompletePlaceholder(__('translations.Search address'))
                            ->label('Searching on Google...')
                            ->columnSpan(2)
                            ->live(onBlur: true)
                            ->withFields([
                                TextInput::make('civil_wedding_address')
                                    ->live(onBlur: true)
                                    ->extraInputAttributes([
                                        'data-google-field' => '{street_number} {route}',
                                    ])
                                    ->label(__('translations.Street'))
                                    ->required(),
                                TextInput::make('civil_wedding_city')
                                    ->live(onBlur: true)
                                    ->label(__('translations.City'))
                                    ->required()
                                    ->extraInputAttributes([
                                        'data-google-field' => 'locality',
                                    ]),
                                TextInput::make('civil_wedding_country')
                                    ->live(onBlur: true)
                                    ->extraInputAttributes([
                                        'data-google-field' => 'country',
                                    ])
                                    ->label(__('translations.Country'))
                                    ->required(),
                                DateTimePicker::make('civil_wedding_date_time')
                                    ->live(onBlur: true)
                                    ->format('d/m/Y')
                                    ->seconds(false)
                                    ->label(__('translations.Date and time'))
                                    ->placeholder(__('translations.Select date'))
                                    ->required()
                            ])
                            ->hiddenLabel(),
                        Placeholder::make('')
                            ->columnSpan(2)
                            ->content(function () {
                                return new HtmlString('<span class="font-bold text-lg">' . __('translations.Religious wedding') . '</span>');
                            })
                            ->hiddenLabel(),
                        GoogleAutocomplete::make('religious_wedding_google_search')
                            ->columnSpan(2)
                            ->autocompleteLabel(__('translations.Search address'))
                            ->autocompletePlaceholder(__('translations.Search address'))
                            ->label('Searching on Google...')
                            ->live(onBlur: true)
                            ->withFields([
                                TextInput::make('religious_wedding_address')
                                    ->live(onBlur: true)
                                    ->extraInputAttributes([
                                        'data-google-field' => '{street_number} {route}',
                                    ])
                                    ->label(__('translations.Street'))
                                    ->required(),
                                TextInput::make('religious_wedding_city')
                                    ->live(onBlur: true)
                                    ->label(__('translations.City'))
                                    ->required()
                                    ->extraInputAttributes([
                                        'data-google-field' => 'locality',
                                    ]),
                                TextInput::make('religious_wedding_country')
                                    ->live(onBlur: true)
                                    ->label(__('translations.Country'))
                                    ->required()
                                    ->extraInputAttributes([
                                        'data-google-field' => 'country',
                                    ]),
                                DateTimePicker::make('religious_wedding_date_time')
                                    ->live(onBlur: true)
                                    ->format('d/m/Y')
                                    ->seconds(false)
                                    ->label(__('translations.Date and time'))
                                    ->placeholder(__('translations.Select date'))
                                    ->required()
                            ])
                            ->hiddenLabel(),
                        Placeholder::make('')
                            ->columnSpan(2)
                            ->content(function () {
                                return new HtmlString('<span class="font-bold text-lg">' . __('translations.Party') . '</span>');
                            })
                            ->hiddenLabel(),
                        GoogleAutocomplete::make('party_google_search')
                            ->columnSpan(2)
                            ->autocompleteLabel(__('translations.Search address'))
                            ->autocompletePlaceholder(__('translations.Search address'))
                            ->label('Searching on Google...')
                            ->live(onBlur: true)
                            ->withFields([
                                TextInput::make('party_address')
                                    ->live(onBlur: true)
                                    ->extraInputAttributes([
                                        'data-google-field' => '{street_number} {route}',
                                    ])
                                    ->label(__('translations.Street'))
                                    ->required(),
                                TextInput::make('party_city')
                                    ->live(onBlur: true)
                                    ->label(__('translations.City'))
                                    ->required()
                                    ->extraInputAttributes([
                                        'data-google-field' => 'locality',
                                    ]),
                                TextInput::make('party_country')
                                    ->live(onBlur: true)
                                    ->label(__('translations.Country'))
                                    ->required()
                                    ->extraInputAttributes([
                                        'data-google-field' => 'country',
                                    ]),
                                DateTimePicker::make('party_date_time')
                                    ->live(onBlur: true)
                                    ->format('d/m/Y')
                                    ->seconds(false)
                                    ->label(__('translations.Date and time'))
                                    ->placeholder(__('translations.Select date'))
                                    ->required()
                            ])
                            ->hiddenLabel(),
                    ])
                    ->hidden(fn() => $this->eventType !== 'wedding'),
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('title')
                            ->columnSpan(2)
                            ->hiddenLabel()
                            ->content(function () {
                                $title = __('translations.Locations');
                                $description = __("translations.Let's choose the location where the magic of this event will come to life.");
                                return new HtmlString('
                                        <div class="flex justify-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                 stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                            </svg>
                                        </div>

                                        <h5 class="text-xl font-medium text-gray-900 text-center mb-1 dark:text-white">' . $title . '</h5>
                                        <p class="text-gray-400 text-center block dark:text-gray-300">' . $description . '</p>');
                            }),
                        Placeholder::make('')
                            ->columnSpan(2)
                            ->content(function () {
                                return new HtmlString('<span class="font-bold text-lg">' . __('translations.Religious ceremony') . '</span>');
                            })
                            ->hiddenLabel(),
                        GoogleAutocomplete::make('religious_wedding_google_search')
                            ->columnSpan(2)
                            ->autocompleteLabel(__('translations.Search address'))
                            ->autocompletePlaceholder(__('translations.Search address'))
                            ->label('Searching on Google...')
                            ->live(onBlur: true)
                            ->withFields([
                                TextInput::make('religious_wedding_address')
                                    ->live(onBlur: true)
                                    ->extraInputAttributes([
                                        'data-google-field' => '{street_number} {route}',
                                    ])
                                    ->label(__('translations.Street'))
                                    ->required(),
                                TextInput::make('religious_wedding_city')
                                    ->live(onBlur: true)
                                    ->label(__('translations.City'))
                                    ->required()
                                    ->extraInputAttributes([
                                        'data-google-field' => 'locality',
                                    ]),
                                TextInput::make('religious_wedding_country')
                                    ->live(onBlur: true)
                                    ->label(__('translations.Country'))
                                    ->required()
                                    ->extraInputAttributes([
                                        'data-google-field' => 'country',
                                    ]),
                                DateTimePicker::make('religious_wedding_date_time')
                                    ->live(onBlur: true)
                                    ->format('d/m/Y')
                                    ->seconds(false)
                                    ->label(__('translations.Date and time'))
                                    ->placeholder(__('translations.Select date'))
                                    ->required()
                            ])
                            ->hiddenLabel(),
                        Placeholder::make('')
                            ->columnSpan(2)
                            ->content(function () {
                                return new HtmlString('<span class="font-bold text-lg">' . __('translations.Party') . '</span>');
                            })
                            ->hiddenLabel(),
                        GoogleAutocomplete::make('party_google_search')
                            ->columnSpan(2)
                            ->autocompleteLabel(__('translations.Search address'))
                            ->autocompletePlaceholder(__('translations.Search address'))
                            ->label('Searching on Google...')
                            ->live(onBlur: true)
                            ->withFields([
                                TextInput::make('party_address')
                                    ->live(onBlur: true)
                                    ->extraInputAttributes([
                                        'data-google-field' => '{street_number} {route}',
                                    ])
                                    ->label(__('translations.Street'))
                                    ->required(),
                                TextInput::make('party_city')
                                    ->live(onBlur: true)
                                    ->label(__('translations.City'))
                                    ->required()
                                    ->extraInputAttributes([
                                        'data-google-field' => 'locality',
                                    ]),
                                TextInput::make('party_country')
                                    ->live(onBlur: true)
                                    ->label(__('translations.Country'))
                                    ->required()
                                    ->extraInputAttributes([
                                        'data-google-field' => 'country',
                                    ]),
                                DateTimePicker::make('party_date_time')
                                    ->live(onBlur: true)
                                    ->format('d/m/Y')
                                    ->seconds(false)
                                    ->label(__('translations.Date and time'))
                                    ->placeholder(__('translations.Select date'))
                                    ->required()
                            ])
                            ->hiddenLabel(),
                    ])
                    ->hidden(fn() => $this->eventType !== 'baptism'),
            ])
            ->statePath('data');
    }

    public function updatedData(): void
    {
        $cachedData = Cache::get('eventify-cached-data');

        foreach ($this->data ?? [] as $key => $value) {
            if (is_array($this->data[$key]) && head($this->data[$key]) instanceof TemporaryUploadedFile) {
                $uploaded = head($this->data[$key]);
                $filename = now()->timestamp . '_' . $uploaded->getClientOriginalName();

                $cachedData[$key] = $uploaded
                    ->storeAs('invitationImages', $filename, 'public');
            } else {
                $cachedData[$key] = $value;
            }
        }

        Cache::put('eventify-cached-data', $cachedData);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.customize-template.locations');
    }
}
