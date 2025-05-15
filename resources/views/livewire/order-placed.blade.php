<div class="flex justify-center items-center h-screen bg-gray-100">
    <div class="max-w-sm p-6 bg-white border border-gray-300 rounded-2xl shadow-lg text-center">
        <!-- Checkmark in a separate row -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 flex items-center justify-center bg-green-100 rounded-full shadow-md">
                <svg class="w-8 h-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ __('translations.Order Completed') }}</h5>

        <!-- Message -->
        <p class="mb-4 text-gray-600">{{ __('translations.Your order was successful! You can now go to your account to complete the invitation.') }}</p>

        <!-- Button with Arrow -->
        <a wire:click="saveData" class="inline-flex items-center px-5 py-3 text-sm font-semibold text-white bg-[#ebca7e] rounded-lg shadow-md hover:bg-yellow-500 transition-all focus:ring-4 focus:outline-none focus:ring-yellow-300">
            {{ __('translations.Enter Account') }}
            <svg class="w-5 h-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>
</div>
