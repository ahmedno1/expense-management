<?php

namespace App\Livewire\Pages\Advisor;

use App\Models\AiRecommendation;
use App\Services\OpenAIAdvisorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
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

    public function improvePlan(OpenAIAdvisorService $advisor): void
    {
        $this->resetErrorBag();
        session()->forget(['status', 'error']);

        if (! $advisor->hasApiKey()) {
            session()->flash('error', __('OpenAI API key is missing. Add OPENAI_API_KEY to your .env file.'));
            return;
        }

        if ($this->isRateLimited()) {
            session()->flash('error', __('Please wait :seconds seconds before requesting new recommendations.', [
                'seconds' => $this->secondsUntilNextRequest(),
            ]));
            return;
        }

        $this->markRateLimit();

        $snapshot = $advisor->buildSnapshot(auth()->id(), $this->month, $this->year);
        $result = $advisor->generateRecommendations($snapshot);

        if (isset($result['error'])) {
            session()->flash('error', __($result['error']));
            return;
        }

        AiRecommendation::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'month' => $this->month,
                'year' => $this->year,
            ],
            [
                'prompt_snapshot' => $result['prompt'] ?? null,
                'response_text' => $result['content'],
            ]
        );

        session()->flash('status', __('AI recommendations updated for :month.', ['month' => $this->monthLabel]));
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
