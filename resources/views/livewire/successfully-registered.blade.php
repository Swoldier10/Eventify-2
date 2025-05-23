<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <!-- Success animation container -->
        <div class="relative mx-auto h-24 w-24 mb-8">
            <!-- Outer ring animation -->
            <div class="absolute inset-0 rounded-full bg-green-200 dark:bg-green-900 animate-ping opacity-25"></div>
            <!-- Inner circle -->
            <div class="absolute inset-0 flex items-center justify-center rounded-full bg-white dark:bg-gray-800 border-2 border-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Title with subtle animation -->
        <h2 class="text-center text-4xl font-extrabold text-gray-900 dark:text-white" style="animation: fadeIn 1s ease-out;">
            @lang('translations.Welcome to Eventify')
        </h2>

        <!-- Subtitle with user name -->
        <p class="mt-3 text-center text-xl text-gray-600 dark:text-gray-300">
            @lang('translations.Your account has been successfully created')
        </p>

        <!-- Main card -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden">
            <!-- Top colored banner -->
            <div class="bg-[#ebca7e] dark:bg-[#ebca7e] px-6 py-4">
                <div class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-white">@lang('translations.Next Steps')</h3>
                </div>
            </div>

            <!-- Card content -->
            <div class="px-6 py-8">
                <div class="text-center mb-8">
                    <p class="text-gray-700 dark:text-gray-300 text-lg">
                        Hi <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $userName }}</span>,
                        <br>
                        @lang('translations.Your account is now set up and ready to go!')
                        <br>
                        @lang('translations.Press the button below to create your first invitation')
                    </p>
                </div>

                <!-- Primary action button -->
                <div>
                    <a href="{{ \Filament\Facades\Filament::getLoginUrl() }}"
                       class="w-1/2 mx-auto flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-[#ebca7e] hover:bg-[#ebca7e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                        @lang('translations.Choose an invitation')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} Eventify. All rights reserved.
            </p>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</div>
