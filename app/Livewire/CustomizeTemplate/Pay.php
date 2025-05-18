<?php

namespace App\Livewire\CustomizeTemplate;

use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class Pay extends Component implements HasForms
{
    use InteractsWithForms;

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.customize-template.pay');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('title')
                    ->columnSpan(2)
                    ->hiddenLabel()
                    ->content(function () {
                        $title = __('translations.Payment');
                        $description = __("translations.The last step: the summary and finalizing the payment for your invitation.");
                        return new HtmlString('
                                        <div class="flex justify-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                            </svg>
                                        </div>

                                        <h5 class="text-xl font-medium text-gray-900 text-center mb-1 dark:text-white">' . $title . '</h5>
                                        <p class="text-gray-400 text-center block dark:text-gray-300">' . $description . '</p>');
                    }),
                Section::make('')
                    ->schema([
                        Placeholder::make('icon')
                            ->hiddenLabel()
                            ->content(function () {
                                return new HtmlString('
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 mx-auto">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                            ');
                            }),
                        Placeholder::make('text')
                            ->hiddenLabel()
                            ->content(function () {
                                return new HtmlString('<span class="text-base flex items-center justify-center">' . __("translations.Please log in or create an account if you don't have one already, to complete the payment for your invitation.") . '</span>');
                            }),
                        Action::make('create_account')
                            ->label(__('translations.Create account'))
                            ->icon('icon-arrow-right')
                            ->iconPosition(IconPosition::After)
                            ->url(fn() => Filament::getRegistrationUrl())
                            ->color(Color::hex('#ebca7e'))
                            ->extraAttributes([
                                'style' => 'left:50%; transform: translate(-50%, -50%);margin-top: 15px',
                            ])
                            ->toFormComponent()
                    ])
            ]);
    }
}
