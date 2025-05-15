<?php

namespace App\Livewire\CustomizeTemplate;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class InvitationSettings extends Component implements HasForms
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

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.customize-template.invitation-settings');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Placeholder::make('title')
                            ->hiddenLabel()
                            ->content(function () {
                                $title = __('translations.Guest settings');
                                $description = __('translations.Last settings for your invitation');

                                return new HtmlString('
                                 <div class="flex justify-center mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
                                </div>

                                <h5 class="text-xl font-medium text-gray-900 text-center mb-1">' . $title . '</h5>
                                <p class="text-gray-400 text-center block mb-6">' . $description . '</p>'
                                );
                            }),
                        Section::make('')
                            ->schema([
                                Placeholder::make('')
                                    ->content(function () {
                                        return new HtmlString('<h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900">' . __('translations.Choose the invitation link') . '</h5>');
                                    }),
                                TextInput::make('invitation_link')
                                    ->live(onBlur: true)
                                    ->hiddenLabel()
                                    ->prefix('www.eventify.ro/i/')
                                    ->placeholder(function (){
                                        if ($this->eventType == 'wedding')
                                        {
                                            return 'Ex. nunta-ana-si-mircea';
                                        }
                                        else if($this->eventType == 'baptism')
                                        {
                                            return 'Ex. botez-maria';
                                        }
                                    })
                                    ->suffixActions([
                                        \Filament\Forms\Components\Actions\Action::make('verify_availability')
                                            ->iconButton()
                                            ->icon('icon-magnifying-glass')
                                            ->color(Color::Green)
                                            ->action(function () {
                                                dd('aaa');
                                            })
                                    ]),
                                Placeholder::make('')
                                    ->content(function () {
                                        return new HtmlString('<p class="mb-3 font-normal text-gray-500 mt-6">' . __('translations.Attention') . ':</p>
                            <ul class="text-sm text-gray-500">
                                <li>' . __('translations.The link ') . '
                                    <strong>' . __('translations.DOES NOT') . ' </strong>' . __('translations. contain spaces or special characters.') .
                                            '</li>
                                <li>' . __("translations.The link contains only letters, numbers, the characters '-' or '_'.") . '</li>
                                <li>' . __("translations.The link will expire in 5 days if the invitation is ") . '
                                    <strong>' . __('translations.NOT') . ' </strong>' . __('translations. purchased.') .
                                            '</li>
                            </ul>');
                                    }),
                            ]),
                        Section::make('')
                            ->schema([
                                Placeholder::make('')
                                    ->content(function () {
                                        return new HtmlString('<h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900">' . __('translations.Guest action settings') . '</h5>');
                                    }),
                                CheckboxList::make('options')
                                    ->live(onBlur: true)
                                    ->hiddenLabel()
                                    ->options([
                                        'offer_confirmation_possibility' => __('translations.Provides the possibility for confirmation'),
                                        'limit_to_one_confirmation' => __('translations.Limit to a single confirmation')
                                    ])
                                    ->descriptions([
                                        'offer_confirmation_possibility' => __('translations.Select this option if you want guests to confirm their attendance at the event. If not, the confirmation form will not be visible in the invitation.'),
                                        'limit_to_one_confirmation' => __("translations.Select this option to ensure that you will not receive multiple responses from the same person. We will ask for the guest's email address in the confirmation form.")
                                    ]),
                                TextInput::make('password')
                                    ->live(onBlur: true)
                                    ->label(__('translations.Access password (optional)'))
                                    ->helperText(__("translations.You can set a password to restrict access to the invitation, so only those who know it can view it. If you don’t want to use a password, leave the field empty."))
                            ])
                    ]),
            ])
            ->statePath('data');
    }

    public function updatedData(): void
    {
        $cachedData = Cache::get('eventify-cached-data');

        foreach ($this->data ?? [] as $key => $value) {
            if (is_array($this->data[$key]) && head($this->data[$key]) instanceof TemporaryUploadedFile){
                $uploaded = head($this->data[$key]);
                $filename = now()->timestamp . '_' . $uploaded->getClientOriginalName();

                $cachedData[$key] = $uploaded
                    ->storeAs('invitationImages', $filename, 'public');
            }else{
                $cachedData[$key] = $value;
            }
        }

        Cache::put('eventify-cached-data', $cachedData);
    }
}
