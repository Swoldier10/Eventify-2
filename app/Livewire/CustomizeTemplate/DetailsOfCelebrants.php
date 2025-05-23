<?php

namespace App\Livewire\CustomizeTemplate;

use App\Filament\Pages\EditTemplate;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DetailsOfCelebrants extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $eventType;

    public function mount($eventType = null): void
    {
        EditTemplate::initData($this->data, $eventType);
        $this->eventType = $eventType;

        if (Filament::getTenant()) {
            $this->data['photo_type'] = Filament::getTenant()->celebrantsImageDisplayType;
        }
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
                                $title = __('translations.Details of the celebrants');

                                return new HtmlString('
                                    <div class="flex justify-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                             stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                                        </svg>
                                    </div>

                                    <h5 class="text-xl font-medium text-gray-900 text-center mb-6 dark:text-white">' . $title . '</h5>');
                            }),
                        TextInput::make('bride_first_name')
                            ->live(onBlur: true)
                            ->label(__("translations.Bride's first name"))
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1
                            ])
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                            ]),
                        TextInput::make('bride_last_name')
                            ->live(onBlur: true)
                            ->label(__("translations.Bride's last name"))
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1
                            ])
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                            ]),
                        TextInput::make('groom_first_name')
                            ->live(onBlur: true)
                            ->label(__("translations.Groom's first name"))
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1
                            ])
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                            ]),
                        TextInput::make('groom_last_name')
                            ->live(onBlur: true)
                            ->label(__("translations.Groom's last name"))
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1
                            ])
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                            ]),
                        Placeholder::make('')
                            ->hiddenLabel()
                            ->columnSpan(2)
                            ->content(function () {
                                return new HtmlString('<div class="border-b border-gray-200 mt-6 dark:border-gray-700"></div>');
                            }),
                        Grid::make(3)
                            ->columnSpan(2)
                            ->schema([
                                Placeholder::make('display_type')
                                    ->columnSpan(3)
                                    ->label(function () {
                                        return new HtmlString('<div class="font-bold text-base">' . __('translations.Image display mode') . '</div>');
                                    })
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __("translations.Your photos from the couple's section of the invitation will be displayed according to this setting.")),
                                RadioDeck::make('photo_type')
                                    ->live()
                                    ->hiddenLabel()
                                    ->color('primary')
                                    ->required()
                                    ->validationMessages([
                                        'required' => __('translations.This field is required.'),
                                    ])
                                    ->columnSpan(3)
                                    ->default('individual_photo')
                                    ->options([
                                        'individual_photo' => __('translations.Individual photos'),
                                        'common_photo' => __('translations.Common photo'),
                                        'no_photo' => __('translations.No photos')
                                    ])
                                    ->descriptions([
                                        'individual_photo' => __('translations.Each with its photo and description'),
                                        'common_photo' => __('translations.One photo and one description for both'),
                                        'no_photo' => __('translations.We will not display any photos')
                                    ])
                                    ->icons([
                                        'individual_photo' => 'icon-arrow-right',
                                        'common_photo' => 'icon-arrow-right',
                                        'no_photo' => 'icon-arrow-right'
                                    ])
                                    ->iconSize(IconSize::Small)
                                    ->iconPosition(IconPosition::After)
                                    ->columns(3),
                            ]),
                        FileUpload::make('bride_photo')
                            ->live(onBlur: true)
                            ->image()
                            ->imageEditor()
                            ->panelAspectRatio('2:1')
                            ->label(__('translations.Bride photo'))
                            ->visible(function (Get $get) {
                                return $get('photo_type') == 'individual_photo';
                            })
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1
                            ])
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                            ]),
                        FileUpload::make('groom_photo')
                            ->live(onBlur: true)
                            ->image()
                            ->imageEditor()
                            ->panelAspectRatio('2:1')
                            ->label(__('translations.Groom photo'))
                            ->visible(function (Get $get) {
                                return $get('photo_type') == 'individual_photo';
                            })
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1
                            ])
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                            ]),
                        Textarea::make('bride_text')
                            ->live(onBlur: true)
                            ->placeholder(__('translations.Some words about the bride'))
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1
                            ])
                            ->visible(function (Get $get) {
                                return $get('photo_type') == 'individual_photo';
                            })
                            ->hiddenLabel(),
                        Textarea::make('groom_text')
                            ->live(onBlur: true)
                            ->placeholder(__('translations.Some words about the groom'))
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1
                            ])
                            ->visible(function (Get $get) {
                                return $get('photo_type') == 'individual_photo';
                            })
                            ->hiddenLabel(),
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                FileUpload::make('common_photo')
                                    ->live(onBlur: true)
                                    ->image()
                                    ->imageEditor()
                                    ->panelAspectRatio('2:1')
                                    ->label(__('translations.Common photo'))
                                    ->visible(function (Get $get) {
                                        return $get('photo_type') == 'common_photo';
                                    })
                                    ->extraAttributes([
                                        'class' => 'w-1/2 md:w-full ml-auto mr-auto'
                                    ])
                                    ->required()
                                    ->validationMessages([
                                        'required' => __('translations.This field is required.'),
                                    ]),
                                Textarea::make('common_text')
                                    ->live(onBlur: true)
                                    ->placeholder(__('translations.Some words about you'))
                                    ->visible(function (Get $get) {
                                        return $get('photo_type') == 'common_photo';
                                    })
                                    ->extraAttributes([
                                        'class' => 'w-1/2 md:w-full ml-auto mr-auto'
                                    ])
                                    ->hiddenLabel(),
                            ]),
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Placeholder::make('no_photo_placeholder')
                                    ->hiddenLabel()
                                    ->visible(function (Get $get) {
                                        return $get('photo_type') == 'no_photo';
                                    })
                                    ->content(function () {
                                        $title = __('translations.We will not display any photos in this section.');
                                        $subTitle = __("translations.If you want a photo of you together or a photo for each of you to appear, choose one of the options 'Individual photos' or 'Common photo'.");
                                        return new HtmlString('
                                <button
                                   class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 mx-auto dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" class="w-6 mx-auto mb-4 dark:text-gray-200">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5"/>
                                    </svg>

                                    <p class="mb-2 text-md font-bold tracking-tight text-gray-900 dark:text-white">' . $title . '</p>
                                    <p class="font-normal text-gray-700 dark:text-white">
                                        ' . $subTitle . '
                                    </p>
                                </button>
                            ');
                                    })
                                    ->extraAttributes([
                                        'style' => 'width: 100%; margin-left:auto; margin-right:auto;'
                                    ])
                            ]),
                        Textarea::make('godparents')
                            ->live(onBlur: true)
                            ->columnSpan(2)
                            ->label(__('translations.Names of the Godparents'))
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                            ])
                            ->placeholder('Ex. Mara ' . __('translations.and') . ' Daniel Popescu'),
                        Placeholder::make('')
                            ->hiddenLabel()
                            ->columnSpan(2)
                            ->content(function () {
                                return new HtmlString('<div class="border-b border-gray-200 mt-6 dark:border-gray-700"></div>');
                            }),
                        Textarea::make('parents')
                            ->live(onBlur: true)
                            ->columnSpan(2)
                            ->label(__('translations.Names of the parents'))
                            ->placeholder('Ex. Mara ' . __('translations.and') . ' Daniel Popescu'),
                    ])
                    ->hidden(fn() => $this->eventType !== 'wedding'),
                Section::make('')
                    ->columns(1)
                    ->schema([
                        Placeholder::make('title')
                            ->hiddenLabel()
                            ->columnSpan(2)
                            ->content(function () {
                                $title = __('translations.Details of the celebrants');

                                return new HtmlString('
                                    <div class="flex justify-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                             stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                                        </svg>
                                    </div>

                                    <h5 class="text-xl font-medium text-gray-900 text-center mb-6 dark:text-white">' . $title . '</h5>');
                            }),

                        Radio::make('nr_kids')
                            ->columnSpanFull()
                            ->live()
                            ->label(__('translations.Number of children'))
                            ->required()
                            ->options([
                                'single' => '1 ' . __('translations.Child'),
                                'twins' => '2 ' . __('translations.Children'),
                            ])
                            ->descriptions([
                                'single' => __('translations.Baptism of a single child'),
                                'twins' => __('translations.Baptism for twins'),
                            ]),

                        TextInput::make('child_name')
                            ->columnSpanFull()
                            ->live(onBlur: true)
                            ->label(__("translations.Child name"))
                            ->placeholder('Ex. Andrei')
                            ->required(),

                        TextInput::make('twin_name')
                            ->columnSpanFull()
                            ->live(onBlur: true)
                            ->label(__("translations.Brother/sister's name"))
                            ->placeholder('Ex. Bianca')
                            ->visible(function (Get $get) {
                                return $get('nr_kids') == 'twins';
                            }),
                        Textarea::make('kids_text')
                            ->columnSpanFull()
                            ->live(onBlur: true)
                            ->placeholder(__('translations.Some words about the children'))
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __("translations.This text will replace the description in the section dedicated to the child."))
                            ->hiddenLabel(),
                        Placeholder::make('')
                            ->hiddenLabel()
                            ->columnSpan(2)
                            ->content(function () {
                                return new HtmlString('<div class="border-b border-gray-200 mt-6 dark:border-gray-700"></div>');
                            }),
                        FileUpload::make('child_photo')
                            ->live(onBlur: true)
                            ->image()
                            ->imageEditor()
                            ->panelAspectRatio('2:1')
                            ->label(__('translations.Child photo'))
                            ->visible(function (Get $get) {
                                return $get('nr_kids') == 'single';
                            })
                            ->extraAttributes([
                                'class' => 'w-1/2 md:w-full ml-auto mr-auto'
                            ])
                            ->required(),
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                FileUpload::make('common_photo')
                                    ->label(__('translations.Children photo'))
                                    ->live(onBlur: true)
                                    ->image()
                                    ->imageEditor()
                                    ->panelAspectRatio('2:1')
                                    ->visible(function (Get $get) {
                                        return $get('nr_kids') == 'twins';
                                    })
                                    ->extraAttributes([
                                        'class' => 'w-1/2 md:w-full ml-auto mr-auto'
                                    ])
                                    ->required(),
                            ]),
                        Textarea::make('godparents')
                            ->live(onBlur: true)
                            ->columnSpan(2)
                            ->label(__('translations.Names of the Godparents'))
                            ->required()
                            ->validationMessages([
                                'required' => __('translations.This field is required.'),
                            ])
                            ->placeholder('Ex. Mara ' . __('translations.and') . ' Daniel Popescu'),
                        Placeholder::make('')
                            ->hiddenLabel()
                            ->columnSpan(2)
                            ->content(function () {
                                return new HtmlString('<div class="border-b border-gray-200 mt-6 dark:border-gray-700"></div>');
                            }),
                        Textarea::make('parents')
                            ->live(onBlur: true)
                            ->columnSpan(2)
                            ->label(__('translations.Names of the parents'))
                            ->placeholder('Ex. Mara ' . __('translations.and') . ' Daniel Popescu')
                            ->required(),
                    ])
                    ->hidden(fn() => $this->eventType !== 'baptism')
            ])
            ->statePath('data');
    }

    public function updatedData(): void
    {
        if ($invitation = Filament::getTenant()) {
            $data = $this->form->getState();

            if (isset($data['bride_photo']) && !str_contains($data['bride_photo'], 'invitationImages')) {
                $from = public_path('storage/' . $data['bride_photo']);
                $to = public_path('storage/invitationImages/' . $data['bride_photo']);

                if (File::exists($from)) {
                    File::move($from, $to);
                }

                $data['bride_photo'] = 'invitationImages/' . $data['bride_photo'];
                $this->data['bride_photo'] = [$data['bride_photo']];
            }

            if (isset($data['groom_photo']) && !str_contains($data['groom_photo'], 'invitationImages')) {
                $from = public_path('storage/' . $data['groom_photo']);
                $to = public_path('storage/invitationImages/' . $data['groom_photo']);

                if (File::exists($from)) {
                    File::move($from, $to);
                }

                $data['groom_photo'] = 'invitationImages/' . $data['groom_photo'];
                $this->data['groom_photo'] = [$data['groom_photo']];
            }

            $invitation->update([
                "bride_first_name" => $data['bride_first_name'] ?? null,
                "bride_last_name" => $data['bride_last_name'] ?? null,
                "groom_first_name" => $data['groom_first_name'] ?? null,
                "groom_last_name" => $data['groom_last_name'] ?? null,
                "bride_photo" => $data['bride_photo'] ?? null,
                "groom_photo" => $data['groom_photo'] ?? null,
                "bride_text" => $data['bride_text'] ?? null,
                "groom_text" => $data['groom_text'] ?? null,
                "godparents" => $data['godparents'] ?? null,
                "parents" => $data['parents'] ?? null
            ]);
        } else {
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

    #[On('validateData')]
    public function validateData(): void
    {
        $this->form->getState();
        $this->dispatch('nextPage', afterValidation: true)->to(Index::class);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.customize-template.details-of-celebrants');
    }
}
