<?php

namespace App\Livewire\Pages\Advisor;

use App\Models\AiRecommendation;
use App\Models\Category;
use App\Models\CategoryBudget;
use App\Models\Expense;
use App\Models\IncomeSource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public int $month;
    public int $year;
    public bool $isGenerating = false;

    public function mount(): void
    {
        $now = now();
        $this->month = (int) $now->format('n');
        $this->year = (int) $now->format('Y');
    }

    public function requestRecommendation(): void
    {
        $this->resetErrorBag();
        session()->forget(['status', 'error']);

        if ($this->isRateLimited()) {
            session()->flash('error', __('Please wait :seconds seconds before requesting new recommendations.', [
                'seconds' => $this->secondsUntilNextRequest(),
            ]));
            return;
        }

        $snapshot = $this->buildSnapshot();
        $prompt = $this->buildPrompt($snapshot);

        $this->isGenerating = true;
        $this->markRateLimit();

        $this->dispatch('advisor:generate', prompt: $prompt);
    }

    public function saveRecommendation(string $responseText, ?string $promptSnapshot = null): void
    {
        $this->isGenerating = false;

        $responseText = trim($responseText);

        if ($responseText === '') {
            session()->flash('error', __('The AI response was empty. Please try again.'));
            return;
        }

        AiRecommendation::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'month' => $this->month,
                'year' => $this->year,
            ],
            [
                'prompt_snapshot' => $promptSnapshot,
                'response_text' => $responseText,
            ]
        );

        session()->flash('status', __('AI recommendations updated for :month.', ['month' => $this->monthLabel]));
    }

    public function handleAdvisorError(string $message): void
    {
        $this->isGenerating = false;
        session()->flash('error', __($message));
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

    #[Computed]
    public function monthLabel(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F Y');
    }

    public function render()
    {
        return view('livewire.pages.advisor.index')
            ->layout('layouts.app', ['title' => __('AI Advisor')]);
    }

    protected function buildSnapshot(): array
    {
        $userId = auth()->id();
        $start = Carbon::create($this->year, $this->month, 1)->startOfMonth()->toDateString();
        $end = Carbon::create($this->year, $this->month, 1)->endOfMonth()->toDateString();

        $incomeSources = IncomeSource::query()
            ->where('user_id', $userId)
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->orderByDesc('created_at')
            ->get(['source', 'amount']);

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $budgets = CategoryBudget::query()
            ->where('user_id', $userId)
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->get()
            ->keyBy('category_id');

        $expensesByCategory = Expense::query()
            ->where('user_id', $userId)
            ->whereBetween('expense_date', [$start, $end])
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $categoryDetails = $categories->map(function ($category) use ($budgets, $expensesByCategory) {
            $expected = (float) ($budgets->get($category->id)?->expected_amount ?? 0);
            $spent = (float) ($expensesByCategory[$category->id] ?? 0);

            return [
                'name' => $category->name,
                'expected' => $expected,
                'spent' => $spent,
                'remaining' => $expected - $spent,
            ];
        })->values();

        $totalIncome = (float) $incomeSources->sum('amount');
        $totalExpected = (float) $budgets->sum('expected_amount');
        $totalSpent = (float) Expense::query()
            ->where('user_id', $userId)
            ->whereBetween('expense_date', [$start, $end])
            ->sum('amount');

        return [
            'month' => $this->month,
            'year' => $this->year,
            'income_sources' => $incomeSources->map(function ($income) {
                return [
                    'source' => $income->source,
                    'amount' => (float) $income->amount,
                ];
            })->values(),
            'total_income' => $totalIncome,
            'categories' => $categoryDetails,
            'totals' => [
                'expected' => $totalExpected,
                'spent' => $totalSpent,
                'remaining' => $totalExpected - $totalSpent,
            ],
        ];
    }

    protected function buildPrompt(array $snapshot): string
    {
        $payload = json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);

        if ($payload === false) {
            $payload = '{}';
        }

        return implode("\n", [
            'You are an AI budgeting advisor for a personal expense app.',
            'Provide 5 to 10 actionable recommendations for the current month.',
            'Highlight overspending categories, suggest budget rebalancing, and keep tips simple.',
            'Use short sections with clear headings and bullet points.',
            '',
            'Monthly data (current month only):',
            $payload,
        ]);
    }

    protected function isRateLimited(): bool
    {
        $lastRequest = Cache::get($this->rateLimitKey());

        if (! $lastRequest) {
            return false;
        }

        return now()->timestamp - $lastRequest < 30;
    }

    protected function secondsUntilNextRequest(): int
    {
        $lastRequest = Cache::get($this->rateLimitKey());

        if (! $lastRequest) {
            return 0;
        }

        $elapsed = now()->timestamp - $lastRequest;

        return max(0, 30 - $elapsed);
    }

    protected function markRateLimit(): void
    {
        Cache::put($this->rateLimitKey(), now()->timestamp, 30);
    }

    protected function rateLimitKey(): string
    {
        return 'advisor:last-request:' . auth()->id();
    }
}
