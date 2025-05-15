<?php

namespace App\Livewire\CustomizeTemplate;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AdvancedCustomization extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $eventType;

    public function mount($eventType=null): void
    {
        $cachedData = Cache::get('eventify-cached-data');
        $this->eventType = $eventType;
        $this->form->fill($cachedData);
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
                                $title = __('translations.Advanced customization');
                                return new HtmlString('
                                 <div class="flex justify-center mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                    </svg>
                                </div>

                                <h5 class="text-xl font-medium text-gray-900 text-center mb-6">' . $title . '</h5>');
                            }),
                        Tabs::make('Tabs')
                            ->tabs([
                                Tabs\Tab::make(__('translations.First page'))
                                    ->columns(2)
                                    ->schema([
                                        FileUpload::make('background_photo_first_page')
                                            ->live(onBlur: true)
                                            ->image()
                                            ->imageEditor()
                                            ->label(__('translations.Background image for the first page'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose a background image for the first page of the invitation.'))
                                            ->columnSpan(2),
                                        TextInput::make('invitation_subtitle')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Subtitle for the first page'))
                                            ->columnSpan(2)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.This text will replace the date and location text under your name on the first page.')),
                                        ColorPicker::make('title_color')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Title color for the first page'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.You can choose another color for the title on the first page (your names).'))
                                            ->columnSpan([
                                                'default' => 2,
                                                'md' => 1
                                            ]),
                                        ColorPicker::make('subtitle_color')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Subtitle color for the first page'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.You can choose another color for the subtitle on the first page (the date and location).'))
                                            ->columnSpan([
                                                'default' => 2,
                                                'md' => 1
                                            ]),
                                    ]),
                                Tabs\Tab::make(__('translations.Countdown section'))
                                    ->schema([
                                        FileUpload::make('countdown_image')
                                            ->live(onBlur: true)
                                            ->image()
                                            ->imageEditor()
                                            ->label(__('translations.Background image for the countdown page'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose a background image for the countdown page.')),
                                        TextInput::make('countdown_text')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Countdown section text'))
                                            ->placeholder(__('translations.We will become a family in')),
                                        RadioDeck::make('countdown')
                                            ->live()
                                            ->hiddenLabel()
                                            ->color('primary')
                                            ->required()
                                            ->default('party')
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose the event for which the countdown will be calculated.'))
                                            ->options([
                                                'civil_wedding' => __('translations.Civil wedding'),
                                                'religious_wedding' => __('translations.Religious wedding'),
                                                'party' => __('translations.Party (default)')
                                            ])
                                            ->iconSize(IconSize::Small)
                                            ->iconPosition(IconPosition::After)
                                            ->columns(3),
                                    ]),
                                Tabs\Tab::make(__('translations.Description section'))
                                    ->columns(2)
                                    ->schema([
                                        FileUpload::make('couple_section_image')
                                            ->live(onBlur: true)
                                            ->image()
                                            ->imageEditor()
                                            ->label(__('translations.Choose a couple image'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose a couple image with both of you.'))
                                            ->columnSpan(2),
                                        TextInput::make('description_title')
                                            ->live(onBlur: true)
                                            ->placeholder(__('translations.Our love'))
                                            ->label(__('translations.Description title'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.This text will replace the title in the description section.'))
                                            ->columnSpan([
                                                'default' => 2,
                                                'md' => 1
                                            ]),
                                        TextInput::make('description_subtitle')
                                            ->live(onBlur: true)
                                            ->placeholder(__('translations.Shared with you'))
                                            ->label(__('translations.Description subtitle'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.This text will replace the subtitle in the description section.'))
                                            ->columnSpan([
                                                'default' => 2,
                                                'md' => 1
                                            ]),
                                        Textarea::make('description_section_text')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Description text'))
                                            ->placeholder(__('translations.There are moments in life that you wait for with bated breath and butterflies in your stomach, and this, for us, is one of them. ...'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.You can replace the text in the description section.'))
                                            ->columnSpan(2),
                                    ]),
                                Tabs\Tab::make(__('translations.Confirmation options'))
                                    ->schema([
                                        Radio::make('need_accommodation')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Possibility of choosing accommodation upon confirmation'))
                                            ->boolean()
                                            ->inline(),
                                        Radio::make('need_vegetarian_menu')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Possibility of choosing a vegetarian menu upon confirmation'))
                                            ->boolean()
                                            ->inline(),
                                        Radio::make('possibility_to_select_nr_kids')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Possibility to select number of children upon confirmation'))
                                            ->boolean()
                                            ->inline(),
                                        TextInput::make('additional_question')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Add an additional question'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.If needed, you can add an additional question to the confirmation form.')),
                                        TextInput::make('additional_text')
                                            ->live(onBlur: true)
                                            ->placeholder(__('translations.Do you want to tell us something?'))
                                            ->label(__('translations.Replace question message with another text'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.If needed, you can replace the standard message question in the confirmation form.')),
                                        DateTimePicker::make('confirmation_deadline')
                                            ->live(onBlur: true)
                                            ->format('d/m/Y')
                                            ->seconds(false)
                                            ->label(__('translations.Confirmation deadline'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.If you choose a deadline for confirmation, we will display text above the confirmation form to specify this.'))
                                    ]),
                                Tabs\Tab::make(__('translations.General'))
                                    ->schema([
                                        FileUpload::make('whatsapp_thumbnail')
                                            ->live(onBlur: true)
                                            ->image()
                                            ->imageEditor()
                                            ->label(__('translations.Choose the thumbnail image for Whatsapp'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose an image that will appear on Whatsapp when you share this way.')),
                                        TextInput::make('text_displayed_when_sharing')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Text displayed when sharing on social media'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.This text will appear in the thumbnail when the invitation is sent on Facebook/Whatsapp.'))
                                            ->placeholder(__('translations.We invite you to our wedding!'))
                                    ]),
                            ])
                    ])
                    ->hidden(fn() => $this->eventType !== 'wedding'),

                Section::make('')
                    ->schema([
                        Placeholder::make('title')
                            ->columnSpan(2)
                            ->hiddenLabel()
                            ->content(function () {
                                $title = __('translations.Advanced customization');
                                return new HtmlString('
                                 <div class="flex justify-center mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                    </svg>
                                </div>

                                <h5 class="text-xl font-medium text-gray-900 text-center mb-6">' . $title . '</h5>');
                            }),
                        Tabs::make('Tabs')
                            ->tabs([
                                Tabs\Tab::make(__('translations.First page'))
                                    ->columns(2)
                                    ->schema([
                                        FileUpload::make('background_photo_first_page')
                                            ->live(onBlur: true)
                                            ->image()
                                            ->imageEditor()
                                            ->label(__('translations.Background image for the first page'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose a background image for the first page of the invitation.'))
                                            ->columnSpan(2),
                                        TextInput::make('invitation_subtitle')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Subtitle for the first page'))
                                            ->columnSpan(2)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __("translations.This text will replace the date and location text under child's name on the first page.")),
                                        ColorPicker::make('title_color')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Title color for the first page'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __("translations.You can choose another color for the title on the first page (child's name)."))
                                            ->columnSpan([
                                                'default' => 2,
                                                'md' => 1
                                            ]),
                                        ColorPicker::make('subtitle_color')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Subtitle color for the first page'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.You can choose another color for the subtitle on the first page (the date and location).'))
                                            ->columnSpan([
                                                'default' => 2,
                                                'md' => 1
                                            ]),
                                    ]),
                                Tabs\Tab::make(__('translations.Countdown section'))
                                    ->schema([
                                        FileUpload::make('countdown_image')
                                            ->live(onBlur: true)
                                            ->image()
                                            ->imageEditor()
                                            ->label(__('translations.Background image for the countdown page'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose a background image for the countdown page.')),
                                        TextInput::make('countdown_text')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Countdown section text'))
                                            ->placeholder(__('translations.Just a little longer until the big day:')),
                                        RadioDeck::make('countdown')
                                            ->live()
                                            ->hiddenLabel()
                                            ->color('primary')
                                            ->required()
                                            ->default('party')
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose the event for which the countdown will be calculated.'))
                                            ->options([
                                                'religious_wedding' => __('translations.Religious ceremony'),
                                                'party' => __('translations.Party (default)')
                                            ])
                                            ->iconSize(IconSize::Small)
                                            ->iconPosition(IconPosition::After)
                                            ->columns(2),
                                    ]),
                                Tabs\Tab::make(__('translations.Description section'))
                                    ->columns(2)
                                    ->schema([
                                        FileUpload::make('child_section_image')
                                            ->live(onBlur: true)
                                            ->image()
                                            ->imageEditor()
                                            ->label(__('translations.Choose an image with your child/children'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose an image with your child/children'))
                                            ->columnSpan(2),
                                        TextInput::make('description_title')
                                            ->live(onBlur: true)
                                            ->placeholder(__('translations.We warmly invite you to'))
                                            ->label(__('translations.Description title'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.This text will replace the title in the description section.'))
                                            ->columnSpan([
                                                'default' => 2,
                                                'md' => 1
                                            ]),
                                        TextInput::make('description_subtitle')
                                            ->live(onBlur: true)
                                            ->placeholder(__('translations.Our baptism'))
                                            ->label(__('translations.Description subtitle'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.This text will replace the subtitle in the description section.'))
                                            ->columnSpan([
                                                'default' => 2,
                                                'md' => 1
                                            ]),
                                        Textarea::make('description_section_text')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Description text'))
                                            ->placeholder(__('translations.With the help of mom and dad, an amazing party is in the making! It would be wonderful to be together and enjoy every moment!...'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.You can replace the text in the description section.'))
                                            ->columnSpan(2),
                                    ]),
                                Tabs\Tab::make(__('translations.Confirmation options'))
                                    ->schema([
                                        Radio::make('need_accommodation')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Possibility of choosing accommodation upon confirmation'))
                                            ->boolean()
                                            ->inline(),
                                        Radio::make('need_vegetarian_menu')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Possibility of choosing a vegetarian menu upon confirmation'))
                                            ->boolean()
                                            ->inline(),
                                        Radio::make('possibility_to_select_nr_kids')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Possibility to select number of children upon confirmation'))
                                            ->boolean()
                                            ->inline(),
                                        TextInput::make('additional_question')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Add an additional question'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.If needed, you can add an additional question to the confirmation form.')),
                                        TextInput::make('additional_text')
                                            ->live(onBlur: true)
                                            ->placeholder(__('translations.Do you want to tell us something?'))
                                            ->label(__('translations.Replace question message with another text'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.If needed, you can replace the standard message question in the confirmation form.')),
                                        DateTimePicker::make('confirmation_deadline')
                                            ->live(onBlur: true)
                                            ->format('d/m/Y')
                                            ->seconds(false)
                                            ->label(__('translations.Confirmation deadline'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.If you choose a deadline for confirmation, we will display text above the confirmation form to specify this.'))
                                    ]),
                                Tabs\Tab::make(__('translations.General'))
                                    ->schema([
                                        FileUpload::make('whatsapp_thumbnail')
                                            ->live(onBlur: true)
                                            ->image()
                                            ->imageEditor()
                                            ->label(__('translations.Choose the thumbnail image for Whatsapp'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.Choose an image that will appear on Whatsapp when you share this way.')),
                                        TextInput::make('text_displayed_when_sharing')
                                            ->live(onBlur: true)
                                            ->label(__('translations.Text displayed when sharing on social media'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('translations.This text will appear in the thumbnail when the invitation is sent on Facebook/Whatsapp.'))
                                            ->placeholder(__('translations.We invite you to our wedding!'))
                                    ]),
                            ])
                    ])
                    ->hidden(fn() => $this->eventType !== 'baptism')

            ])
            ->statePath('data');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.customize-template.advanced-customization');
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
}
