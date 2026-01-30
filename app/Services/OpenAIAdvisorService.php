<?php

namespace App\Services;

use App\Models\Category;
use App\Models\CategoryBudget;
use App\Models\Expense;
use App\Models\IncomeSource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Throwable;

class OpenAIAdvisorService
{
    public function hasApiKey(): bool
    {
        return (bool) $this->apiKey();
    }

    public function buildSnapshot(int $userId, int $month, int $year): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $end = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();

        $incomeSources = IncomeSource::query()
            ->where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->orderByDesc('created_at')
            ->get(['source', 'amount']);

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $budgets = CategoryBudget::query()
            ->where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
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
            'month' => $month,
            'year' => $year,
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

    public function generateRecommendations(array $snapshot): array
    {
        $apiKey = $this->apiKey();

        if (! $apiKey) {
            return ['error' => 'OpenAI API key is missing. Add OPENAI_API_KEY to your .env file.'];
        }

        $prompt = $this->buildPrompt($snapshot);

        try {
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model(),
                    'temperature' => 0.3,
                    'max_tokens' => 700,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an AI budgeting advisor for a personal expense app. Provide 5 to 10 actionable recommendations. Highlight overspending categories, suggest budget rebalancing, keep tips simple, and use short sections with bullet points. Use clear section headings such as Overview, Overspending, and Recommendations.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                ]);
        } catch (Throwable $exception) {
            return ['error' => 'OpenAI request failed. Please try again shortly.'];
        }

        if ($response->status() === 401) {
            return ['error' => 'OpenAI rejected the API key. Please verify OPENAI_API_KEY.'];
        }

        if ($response->status() === 429) {
            return ['error' => 'OpenAI rate limit reached. Please wait and try again.'];
        }

        if ($response->failed()) {
            return ['error' => 'OpenAI returned an unexpected error. Please try again.'];
        }

        $content = trim((string) $response->json('choices.0.message.content'));

        if ($content === '') {
            return ['error' => 'OpenAI response was empty. Please try again.'];
        }

        return [
            'content' => $content,
            'prompt' => $prompt,
        ];
    }

    protected function buildPrompt(array $snapshot): string
    {
        $payload = json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return "Monthly data (current month only). Use the data to craft actionable advice:\n\n{$payload}";
    }

    protected function apiKey(): ?string
    {
        return env('OPENAI_API_KEY');
    }

    protected function model(): string
    {
        return env('OPENAI_MODEL', 'gpt-4o-mini');
    }
}
