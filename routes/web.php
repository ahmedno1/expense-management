<?php

use App\Livewire\Pages\Categories\Index as CategoriesIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/categories', CategoriesIndex::class)
        ->name('categories.index');
});

require __DIR__.'/settings.php';
