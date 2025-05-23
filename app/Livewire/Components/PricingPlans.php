<?php

namespace App\Livewire\Components;

use App\Livewire\CustomizeTemplate\Index;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;

class PricingPlans extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.components.pricing-plans');
    }

    public $selectedPlanIndex = 0;
    public bool $shouldDisplayErrorMsg = false;
    public array $plans = [];

    public function mount(array $plans): void
    {
        $cachedData = Cache::get('eventify-cached-data');

        if (!isset($cachedData['selected_plan']) || $cachedData['selected_plan'] == null) {
            $cachedData['selected_plan'] = $this->selectedPlanIndex;
            Cache::put('eventify-cached-data', $cachedData);
        } else {
            $this->selectedPlanIndex = $cachedData['selected_plan'];
        }

        $this->plans = $plans;
    }

    public function selectPlan($index): void
    {
        $this->selectedPlanIndex = $index;

        $cachedData = Cache::get('eventify-cached-data');
        $cachedData['selected_plan'] = $index;
        Cache::put('eventify-cached-data', $cachedData);
    }

    #[On('validateData')]
    public function validateData(): void
    {
        if ($this->selectedPlanIndex == 0) {
            $this->shouldDisplayErrorMsg = true;
        } else {
            $this->shouldDisplayErrorMsg = false;
            $this->dispatch('nextPage', afterValidation: true)->to(Index::class);
        }
    }
}
