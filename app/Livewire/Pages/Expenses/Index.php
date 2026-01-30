<?php

namespace App\Livewire\Pages\Expenses;

use App\Models\Category;
use App\Models\Expense;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public ?int $categoryId = null;
    public string $amount = '';
    public string $expenseDate = '';

    public ?int $editingId = null;
    public ?int $editingCategoryId = null;
    public string $editingAmount = '';
    public string $editingExpenseDate = '';

    public int $month;
    public int $year;

    public function mount(): void
    {
        $now = now();
        $this->month = (int) $now->format('n');
        $this->year = (int) $now->format('Y');
        $this->expenseDate = $now->toDateString();
    }

    public function create(): void
    {
        [$startDate, $endDate] = $this->monthDateRange();

        $validated = $this->validate([
            'categoryId' => ['required', 'integer', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'expenseDate' => ['required', 'date', 'after_or_equal:'.$startDate, 'before_or_equal:'.$endDate],
        ]);

        Expense::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['categoryId'],
            'expense_date' => $validated['expenseDate'],
            'amount' => $validated['amount'],
        ]);

        $this->reset('categoryId', 'amount');
        $this->expenseDate = now()->toDateString();

        session()->flash('status', __('Expense added.'));
    }

    public function edit(int $expenseId): void
    {
        [$startDate, $endDate] = $this->monthDateRange();

        $expense = Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->findOrFail($expenseId);

        $this->editingId = $expense->id;
        $this->editingCategoryId = $expense->category_id;
        $this->editingAmount = (string) $expense->amount;
        $this->editingExpenseDate = $expense->expense_date->toDateString();

        $this->resetErrorBag();
    }

    public function update(): void
    {
        if ($this->editingId === null) {
            return;
        }

        [$startDate, $endDate] = $this->monthDateRange();

        $validated = $this->validate([
            'editingCategoryId' => ['required', 'integer', 'exists:categories,id'],
            'editingAmount' => ['required', 'numeric', 'min:0'],
            'editingExpenseDate' => ['required', 'date', 'after_or_equal:'.$startDate, 'before_or_equal:'.$endDate],
        ]);

        $expense = Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->findOrFail($this->editingId);

        $expense->update([
            'category_id' => $validated['editingCategoryId'],
            'amount' => $validated['editingAmount'],
            'expense_date' => $validated['editingExpenseDate'],
        ]);

        $this->cancelEdit();

        session()->flash('status', __('Expense updated.'));
    }

    public function cancelEdit(): void
    {
        $this->reset('editingId', 'editingCategoryId', 'editingAmount', 'editingExpenseDate');
        $this->resetErrorBag();
    }

    public function delete(int $expenseId): void
    {
        [$startDate, $endDate] = $this->monthDateRange();

        $expense = Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->findOrFail($expenseId);

        $expense->delete();

        if ($this->editingId === $expenseId) {
            $this->cancelEdit();
        }

        session()->flash('status', __('Expense deleted.'));
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function expenses()
    {
        [$startDate, $endDate] = $this->monthDateRange();

        return Expense::query()
            ->with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->orderByDesc('expense_date')
            ->orderByDesc('created_at')
            ->get();
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
    public function monthLabel(): string
    {
        return now()->format('F Y');
    }

    public function render()
    {
        return view('livewire.pages.expenses.index')
            ->layout('layouts.app', ['title' => __('Expenses')]);
    }

    protected function monthDateRange(): array
    {
        $start = now()->startOfMonth()->toDateString();
        $end = now()->endOfMonth()->toDateString();

        return [$start, $end];
    }
}
