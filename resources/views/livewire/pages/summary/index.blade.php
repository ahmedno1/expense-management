<div class="space-y-6">
    <div>
        <flux:heading>{{ __('Summary') }}</flux:heading>
        <flux:subheading>{{ __('Overview for :month.', ['month' => $this->monthLabel]) }}</flux:subheading>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Total income') }}
            </flux:text>
            <flux:text class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                {{ number_format($this->totalIncome, 2) }}
            </flux:text>
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Total expected') }}
            </flux:text>
            <flux:text class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                {{ number_format($this->totalExpected, 2) }}
            </flux:text>
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Total spent') }}
            </flux:text>
            <flux:text class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                {{ number_format($this->totalSpent, 2) }}
            </flux:text>
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Total remaining') }}
            </flux:text>
            <flux:text class="text-lg font-semibold {{ $this->totalRemaining < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                {{ number_format($this->totalRemaining, 2) }}
            </flux:text>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($this->summaryRows as $row)
                <div class="grid gap-4 px-4 py-4 md:grid-cols-4 md:items-center">
                    <flux:text class="text-base font-medium text-zinc-900 dark:text-zinc-100">
                        {{ $row['name'] }}
                    </flux:text>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('Expected: :amount', ['amount' => number_format($row['expected'], 2)]) }}
                    </flux:text>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('Spent: :amount', ['amount' => number_format($row['spent'], 2)]) }}
                    </flux:text>
                    <flux:text class="text-sm font-medium {{ $row['remaining'] < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                        {{ __('Remaining: :amount', ['amount' => number_format($row['remaining'], 2)]) }}
                    </flux:text>
                </div>
            @empty
                <div class="px-4 py-6 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('No categories yet. Add categories to see a summary.') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
