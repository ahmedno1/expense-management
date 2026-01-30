<?php

namespace App\Livewire\Pages\Budgets;

use App\Models\Category;
use App\Models\CategoryBudget;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public array $expectedAmounts = [];

    public int $month;
    public int $year;

    public function mount(): void
    {
        $now = now();
        $this->month = (int) $now->format('n');
        $this->year = (int) $now->format('Y');

        $budgets = CategoryBudget::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->get()
            ->keyBy('category_id');

        foreach (Category::query()->orderBy('name')->get() as $category) {
            $budget = $budgets->get($category->id);
            $this->expectedAmounts[$category->id] = (string) ($budget?->expected_amount ?? 0);
        }
    }

    public function save(): void
    {
        $this->validate([
            'expectedAmounts.*' => ['required', 'numeric', 'min:0'],
        ]);

        $categories = Category::query()->orderBy('name')->get();

        foreach ($categories as $category) {
            $amount = $this->expectedAmounts[$category->id] ?? 0;

            CategoryBudget::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'category_id' => $category->id,
                    'month' => $this->month,
                    'year' => $this->year,
                ],
                ['expected_amount' => $amount]
            );
        }

        session()->flash('status', __('Budgets saved.'));
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function monthLabel(): string
    {
        return now()->format('F Y');
    }

    public function render()
    {
        return view('livewire.pages.budgets.index')
            ->layout('layouts.app', ['title' => __('Budgets')]);
    }
}
