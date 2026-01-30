<?php

use App\Livewire\Pages\Advisor\Index as AdvisorIndex;
use App\Livewire\Pages\Budgets\Index as BudgetsIndex;
use App\Livewire\Pages\Categories\Index as CategoriesIndex;
use App\Livewire\Pages\Expenses\Index as ExpensesIndex;
use App\Livewire\Pages\Income\Index as IncomeIndex;
use App\Livewire\Pages\Summary\Index as SummaryIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/summary', SummaryIndex::class)
        ->name('summary.index');
    Route::get('/income', IncomeIndex::class)
        ->name('income.index');
    Route::get('/budgets', BudgetsIndex::class)
        ->name('budgets.index');
    Route::get('/expenses', ExpensesIndex::class)
        ->name('expenses.index');
    Route::get('/categories', CategoriesIndex::class)
        ->name('categories.index');
    Route::get('/advisor', AdvisorIndex::class)
        ->name('advisor.index');
});

require __DIR__.'/settings.php';
