<?php

namespace App\Livewire\Pages\Income;

use App\Models\IncomeSource;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public string $source = '';
    public string $amount = '';

    public ?int $editingId = null;
    public string $editingSource = '';
    public string $editingAmount = '';

    public int $month;
    public int $year;

    public function mount(): void
    {
        $now = now();
        $this->month = (int) $now->format('n');
        $this->year = (int) $now->format('Y');
    }

    public function create(): void
    {
        $validated = $this->validate([
            'source' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        IncomeSource::create([
            'user_id' => auth()->id(),
            'month' => $this->month,
            'year' => $this->year,
            'source' => $validated['source'],
            'amount' => $validated['amount'],
        ]);

        $this->reset('source', 'amount');

        session()->flash('status', __('Income source added.'));
    }

    public function edit(int $incomeId): void
    {
        $income = IncomeSource::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->findOrFail($incomeId);

        $this->editingId = $income->id;
        $this->editingSource = $income->source;
        $this->editingAmount = (string) $income->amount;

        $this->resetErrorBag();
    }

    public function update(): void
    {
        if ($this->editingId === null) {
            return;
        }

        $validated = $this->validate([
            'editingSource' => ['required', 'string', 'max:100'],
            'editingAmount' => ['required', 'numeric', 'min:0'],
        ]);

        $income = IncomeSource::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->findOrFail($this->editingId);

        $income->update([
            'source' => $validated['editingSource'],
            'amount' => $validated['editingAmount'],
        ]);

        $this->cancelEdit();

        session()->flash('status', __('Income source updated.'));
    }

    public function cancelEdit(): void
    {
        $this->reset('editingId', 'editingSource', 'editingAmount');
        $this->resetErrorBag();
    }

    public function delete(int $incomeId): void
    {
        $income = IncomeSource::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->findOrFail($incomeId);

        $income->delete();

        if ($this->editingId === $incomeId) {
            $this->cancelEdit();
        }

        session()->flash('status', __('Income source deleted.'));
    }

    #[Computed]
    public function incomeSources()
    {
        return IncomeSource::query()
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->orderByDesc('created_at')
            ->get();
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
    public function monthLabel(): string
    {
        return now()->format('F Y');
    }

    public function render()
    {
        return view('livewire.pages.income.index')
            ->layout('layouts.app', ['title' => __('Income Sources')]);
    }
}
