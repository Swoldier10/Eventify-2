<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/customize-template', [\App\Livewire\CustomizeTemplate\Index::class, '__invoke'])
    ->name('customize-template');

Route::get('/success-order', [\App\Livewire\OrderPlaced::class, '__invoke'
])->name('success_order');
