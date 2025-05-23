<div>
    @include('components.layouts.extras.sidebar')
    <div class="p-4 sm:ml-64 mt-12">
        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 my-10">
            <div class="bg-[#ebca7e] text-xs font-medium text-black text-center p-0.5 leading-none rounded-full"
                 style="width: {{ ceil($selectedPageIndex * 16.6) }}%"> {{ ceil($selectedPageIndex * 16.6) }}%
            </div>
        </div>
        @switch($selectedPageIndex)
            @case(0)
                <livewire:customize-template.general-info :eventType="$eventType"/>
                @break
            @case(1)
                <livewire:customize-template.details-of-celebrants :eventType="$eventType"/>
                @break
            @case(2)
                <livewire:customize-template.locations :eventType="$eventType"/>
                @break
            @case(3)
                <livewire:customize-template.advanced-customization :eventType="$eventType"/>
                @break
            @case(4)
                <livewire:customize-template.invitation-type :eventType="$eventType"/>
                @break
            @case(5)
                <livewire:customize-template.invitation-settings :eventType="$eventType"/>
                @break
            @case(6)
                <livewire:customize-template.pay/>
        @endswitch
        <div class="mt-4">
            <button type="button"
                    wire:click="prevPage"
                    class="{{ $selectedPageIndex == 0 ? 'hidden' : '' }} float-left flex gap-2 items-center font-bold focus:outline-none text-white bg-[#ebca7e] hover:bg-yellow-500 focus:ring-4 focus:ring-[#d2ad57] rounded-lg text-sm px-5 py-2 mb-2 dark:focus:ring-yellow-900">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                @lang('translations.Back')
            </button>
            <button type="button"
                    wire:click="nextPage"
                    class="{{ $selectedPageIndex == 6 ? 'hidden' : '' }} flex gap-2 float-right items-center font-bold focus:outline-none text-white bg-[#ebca7e] hover:bg-yellow-500 focus:ring-4 focus:ring-[#d2ad57] rounded-lg text-sm px-5 py-2 mb-2 dark:focus:ring-yellow-900">
                @lang('translations.Next step')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </button>
        </div>
    </div>

    <x-filament-actions::modals/>
</div>
