<?php

namespace App\Livewire;

use Livewire\Component;

class SeatsLayout extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.seats-layout');
    }
}
