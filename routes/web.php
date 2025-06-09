<?php

use App\Livewire\PeaceInvitation;
use App\Livewire\NaturalInvitation;
use App\Livewire\SuccessfullyRegistered;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/customize-template/{eventType}/{invitationTemplateId}', [\App\Livewire\CustomizeTemplate\Index::class, '__invoke'])
    ->name('customize-template');

Route::get('/success-order', [\App\Livewire\OrderPlaced::class, '__invoke'
])->name('success_order');

Route::get('/successfully-registered', SuccessfullyRegistered::class)
    ->name('successfully-registered')
    ->middleware(['auth']);

Route::get('/peace-invitation', PeaceInvitation::class)
    ->name('peace-invitation');

Route::get('/natural-invitation', NaturalInvitation::class)
    ->name('natural-invitation');
