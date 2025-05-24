<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\RegistrationResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportRedirects\Redirector;

class RegisterResponse extends RegistrationResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $data = Cache::get('eventify-cached-data');

        if ($data) {
            $paymentUrl = route('successfully-registered');
            if (isset($data['selected_plan']) && $data['selected_plan'] == 1) {
                Log::info('Basic Plan Selected');
                $paymentUrl = 'https://buy.stripe.com/test_5kA6rS1R21HY3bG7ss?prefilled_email=' . Auth::user()?->email;
            }


            if (isset($data['selected_plan']) && $data['selected_plan'] == 2) {
                Log::info('Pro Plan Selected');
                $paymentUrl = 'https://buy.stripe.com/test_8wM3fGanygCSbIc7st?prefilled_email=' . Auth::user()?->email;
            }

            if (isset($data['selected_plan']) && $data['selected_plan'] == 3) {
                Log::info('Premium Plan Selected');
                $paymentUrl = 'https://buy.stripe.com/test_6oEcQgeDOcmC9A46oq?prefilled_email=' . Auth::user()?->email;
            }

            return redirect()->to($paymentUrl);
        }
        return redirect()->to(route('successfully-registered'));
    }
}
