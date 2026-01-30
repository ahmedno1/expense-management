<?php

namespace App\Livewire\Pages\Summary;

use App\Models\Category;
use App\Models\CategoryBudget;
use App\Models\Expense;
use App\Models\IncomeSource;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public int $month;
    public int $year;

    public function mount(): void
    {
        $now = now();
        $this->month = (int) $now->format('n');
        $this->year = (int) $now->format('Y');
    }

    #[Computed]
    public function summaryRows()
    {
        [$startDate, $endDate] = $this->monthDateRange();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $budgets = CategoryBudget::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->get()
            ->keyBy('category_id');

        $expenses = Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        return $categories->map(function ($category) use ($budgets, $expenses) {
            $expected = (float) ($budgets->get($category->id)?->expected_amount ?? 0);
            $spent = (float) ($expenses[$category->id] ?? 0);

            return [
                'id' => $category->id,
                'name' => $category->name,
                'expected' => $expected,
                'spent' => $spent,
                'remaining' => $expected - $spent,
            ];
        });
    }

    #[Computed]
    public function totalIncome()
    {
        return IncomeSource::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->sum('amount');
    }

    #[Computed]
    public function totalExpected()
    {
        return CategoryBudget::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->sum('expected_amount');
    }

    #[Computed]
    public function totalSpent()
    {
        [$startDate, $endDate] = $this->monthDateRange();

        return Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
    }

    #[Computed]
    public function totalRemaining(): float
    {
        return (float) $this->totalExpected - (float) $this->totalSpent;
    }

    #[Computed]
    public function monthLabel(): string
    {
        return now()->format('F Y');
    }

    public function render()
    {
        return view('livewire.pages.summary.index')
            ->layout('layouts.app', ['title' => __('Summary')]);
    }

    protected function monthDateRange(): array
    {
        $start = now()->startOfMonth()->toDateString();
        $end = now()->endOfMonth()->toDateString();

        return [$start, $end];
    }
}
