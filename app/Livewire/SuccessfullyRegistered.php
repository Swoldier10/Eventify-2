<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SuccessfullyRegistered extends Component
{
    public ?string $userName = null;

    public function mount(): void
    {
        $this->userName = Auth::user()->name ?? 'User';
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.successfully-registered');
    }
} 