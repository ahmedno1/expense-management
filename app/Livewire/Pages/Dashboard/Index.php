<?php

namespace App\Livewire\Pages\Dashboard;

use App\Models\AiRecommendation;
use App\Models\Category;
use App\Models\CategoryBudget;
use App\Models\Expense;
use App\Models\IncomeSource;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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
    public function monthLabel(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F Y');
    }

    #[Computed]
    public function totals(): array
    {
        [$startDate, $endDate] = $this->monthDateRange();

        $income = (float) IncomeSource::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->sum('amount');

        $expected = (float) CategoryBudget::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->sum('expected_amount');

        $spent = (float) Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        $remaining = $expected - $spent;

        $daysIntoMonth = max(1, (int) Carbon::now()->day);
        $daysInMonth = (int) Carbon::create($this->year, $this->month, 1)->daysInMonth;
        $dailySpend = $spent / $daysIntoMonth;
        $projected = $dailySpend * $daysInMonth;

        return [
            'income' => $income,
            'expected' => $expected,
            'spent' => $spent,
            'remaining' => $remaining,
            'daily_spend' => $dailySpend,
            'projected_spent' => $projected,
        ];
    }

    #[Computed]
    public function setupChecklist(): array
    {
        [$startDate, $endDate] = $this->monthDateRange();

        $hasCategories = Category::query()->exists();

        $hasBudgets = CategoryBudget::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->exists();

        $hasIncome = IncomeSource::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->exists();

        $hasExpenses = Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->exists();

        $hasRecommendation = AiRecommendation::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->exists();

        return [
            [
                'done' => $hasCategories,
                'title' => __('Create categories'),
                'description' => __('Organize your spending into buckets.'),
                'route' => route('categories.index', absolute: false),
                'icon' => 'layout-grid',
            ],
            [
                'done' => $hasBudgets,
                'title' => __('Set budgets'),
                'description' => __('Plan what you expect to spend this month.'),
                'route' => route('budgets.index', absolute: false),
                'icon' => 'clipboard-document-list',
            ],
            [
                'done' => $hasIncome,
                'title' => __('Add income sources'),
                'description' => __('Track what comes in before you spend.'),
                'route' => route('income.index', absolute: false),
                'icon' => 'banknotes',
            ],
            [
                'done' => $hasExpenses,
                'title' => __('Log your first expense'),
                'description' => __('Capture purchases as you go.'),
                'route' => route('expenses.index', absolute: false),
                'icon' => 'receipt-percent',
            ],
            [
                'done' => $hasRecommendation,
                'title' => __('Get AI insights'),
                'description' => __('Ask the advisor to improve your plan.'),
                'route' => route('advisor.index', absolute: false),
                'icon' => 'sparkles',
            ],
        ];
    }

    #[Computed]
    public function recentExpenses()
    {
        [$startDate, $endDate] = $this->monthDateRange();

        return Expense::query()
            ->with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->orderByDesc('expense_date')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function budgetRows(): Collection
    {
        [$startDate, $endDate] = $this->monthDateRange();

        $budgets = CategoryBudget::query()
            ->with('category')
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->get();

        if ($budgets->isEmpty()) {
            return collect();
        }

        $expensesByCategory = Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        return $budgets
            ->map(function (CategoryBudget $budget) use ($expensesByCategory) {
                $expected = (float) $budget->expected_amount;
                $spent = (float) ($expensesByCategory[$budget->category_id] ?? 0);
                $remaining = $expected - $spent;
                $ratio = $expected > 0 ? min(1.25, $spent / $expected) : 0;

                return [
                    'category' => $budget->category?->name ?? __('Unknown'),
                    'expected' => $expected,
                    'spent' => $spent,
                    'remaining' => $remaining,
                    'ratio' => $ratio,
                ];
            })
            ->sort(function (array $a, array $b) {
                $aOver = $a['remaining'] < 0;
                $bOver = $b['remaining'] < 0;

                if ($aOver !== $bOver) {
                    return $aOver ? -1 : 1;
                }

                if ($a['ratio'] === $b['ratio']) {
                    return $b['spent'] <=> $a['spent'];
                }

                return $b['ratio'] <=> $a['ratio'];
            })
            ->values()
            ->take(8);
    }

    #[Computed]
    public function currentRecommendation(): ?AiRecommendation
    {
        return AiRecommendation::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->first();
    }

    public function render()
    {
        return view('livewire.pages.dashboard.index')
            ->layout('layouts.app', ['title' => __('Dashboard')]);
    }

    protected function monthDateRange(): array
    {
        $start = Carbon::create($this->year, $this->month, 1)->startOfMonth()->toDateString();
        $end = Carbon::create($this->year, $this->month, 1)->endOfMonth()->toDateString();

        return [$start, $end];
    }
}

