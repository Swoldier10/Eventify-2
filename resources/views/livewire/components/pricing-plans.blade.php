<div>
    <div class="grid gap-4 lg:grid-cols-3">
        @foreach($plans ?? [] as $index => $plan)
            <div class=" {{ $selectedPlanIndex == $index ? 'border-8' : 'border-gray-200' }} w-full p-4 bg-white border rounded-lg shadow-sm sm:p-8 flex flex-col justify-between h-full">
                <h5 class="mb-4 text-xl font-medium text-gray-500">{{ $plan['name'] }}</h5>
                <div class="flex items-baseline text-gray-900">
                    <span class="text-4xl font-extrabold tracking-tight">{{ $plan['price'] }}</span>
                    <span class="text-2xl font-semibold ms-3">RON</span>
                    <span class="ms-1 text-sm font-normal text-gray-500">/{{__('translations.event')}}</span>
                </div>
                <ul role="list" class="space-y-5 my-7">
                    @foreach($plan['specs'] ?? [] as $spec)
                        <li class="flex items-center">
                            <svg class="shrink-0 w-4 h-4 text-[#ebca7e]" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                            </svg>
                            <span
                                class="text-base font-normal leading-tight text-gray-500 ms-3">{{ $spec }}</span>
                        </li>
                    @endforeach
                </ul>

                <button wire:click="selectPlan({{ $index }})" type="button"
                        class="{{ $selectedPlanIndex == $index ? 'bg-gray-600' : ''}}
                            text-white bg-[#ebca7e] hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center items-center space-x-2 w-full text-center mt-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                    </svg>

                    {{__('translations.Choose plan')}}
                </button>

            </div>
        @endforeach
    </div>
    @if($shouldDisplayErrorMsg)
        <div class="text-red-500 mt-2">*Please choose a plan</div>
    @endif
</div>
