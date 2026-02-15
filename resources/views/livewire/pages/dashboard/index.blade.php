<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <flux:heading>{{ __('Dashboard') }}</flux:heading>
            <flux:subheading>{{ __('Your money at a glance for :month.', ['month' => $this->monthLabel]) }}</flux:subheading>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a
                href="{{ route('expenses.index') }}"
                wire:navigate
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
            >
                <flux:icon.receipt-percent class="size-4" />
                {{ __('Log expense') }}
            </a>
            <a
                href="{{ route('income.index') }}"
                wire:navigate
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-900 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
            >
                <flux:icon.banknotes class="size-4" />
                {{ __('Add income') }}
            </a>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-12">
        <div class="relative overflow-hidden rounded-2xl border border-zinc-200/70 bg-white dark:border-zinc-700 dark:bg-zinc-950 lg:col-span-8">
            <div class="absolute inset-0 bg-[radial-gradient(80rem_20rem_at_-10%_-10%,rgba(16,185,129,0.18),transparent_60%),radial-gradient(60rem_20rem_at_120%_10%,rgba(59,130,246,0.16),transparent_55%),radial-gradient(60rem_30rem_at_50%_120%,rgba(244,63,94,0.10),transparent_55%)]"></div>

            <div class="relative p-5">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div class="max-w-xl">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('Welcome back, :name.', ['name' => auth()->user()->name]) }}
                        </div>
                        <div class="mt-2 text-balance text-xl font-semibold text-zinc-900 dark:text-white md:text-2xl">
                            {{ __('Stay on top of budgets, spot overspending early, and keep your month on track.') }}
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <a
                                href="{{ route('summary.index') }}"
                                wire:navigate
                                class="group inline-flex items-center gap-2 rounded-lg bg-zinc-900/90 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-900 dark:bg-white/95 dark:text-zinc-900 dark:hover:bg-white"
                            >
                                <flux:icon.chart-bar class="size-4 opacity-90 transition group-hover:opacity-100" />
                                {{ __('View summary') }}
                            </a>
                            <a
                                href="{{ route('budgets.index') }}"
                                wire:navigate
                                class="group inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white/70 px-3 py-2 text-sm font-medium text-zinc-900 shadow-sm backdrop-blur transition hover:bg-white dark:border-zinc-700 dark:bg-zinc-900/60 dark:text-zinc-100 dark:hover:bg-zinc-900"
                            >
                                <flux:icon.clipboard-document-list class="size-4 opacity-80 transition group-hover:opacity-100" />
                                {{ __('Adjust budgets') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid w-full max-w-sm grid-cols-2 gap-3 md:w-auto">
                        <div class="rounded-xl border border-zinc-200/70 bg-white/70 p-3 backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
                            <div class="text-xs text-zinc-600 dark:text-zinc-400">{{ __('Income') }}</div>
                            <div class="mt-1 text-lg font-semibold text-zinc-900 dark:text-white">
                                {{ number_format($this->totals['income'], 2) }}
                            </div>
                        </div>
                        <div class="rounded-xl border border-zinc-200/70 bg-white/70 p-3 backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
                            <div class="text-xs text-zinc-600 dark:text-zinc-400">{{ __('Spent') }}</div>
                            <div class="mt-1 text-lg font-semibold text-zinc-900 dark:text-white">
                                {{ number_format($this->totals['spent'], 2) }}
                            </div>
                        </div>
                        <div class="rounded-xl border border-zinc-200/70 bg-white/70 p-3 backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
                            <div class="text-xs text-zinc-600 dark:text-zinc-400">{{ __('Budgeted') }}</div>
                            <div class="mt-1 text-lg font-semibold text-zinc-900 dark:text-white">
                                {{ number_format($this->totals['expected'], 2) }}
                            </div>
                        </div>
                        <div class="rounded-xl border border-zinc-200/70 bg-white/70 p-3 backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
                            <div class="text-xs text-zinc-600 dark:text-zinc-400">{{ __('Remaining') }}</div>
                            <div class="mt-1 text-lg font-semibold {{ $this->totals['remaining'] < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-700 dark:text-emerald-300' }}">
                                {{ number_format($this->totals['remaining'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid gap-3 md:grid-cols-3">
                    <div class="rounded-xl border border-zinc-200/70 bg-white/70 p-4 backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ __('Spending pace') }}
                            </div>
                            <flux:icon.arrow-trending-up class="size-4 text-zinc-500 dark:text-zinc-400" />
                        </div>
                        <div class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('~:amount / day', ['amount' => number_format($this->totals['daily_spend'], 2)]) }}
                        </div>
                        <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-500">
                            {{ __('Projected :amount by month-end.', ['amount' => number_format($this->totals['projected_spent'], 2)]) }}
                        </div>
                    </div>

                    <div class="rounded-xl border border-zinc-200/70 bg-white/70 p-4 backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60 md:col-span-2">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ __('Budget usage') }}
                                </div>
                                @php
                                    $usage = $this->totals['expected'] > 0 ? ($this->totals['spent'] / $this->totals['expected']) : 0;
                                    $usagePct = (int) round(min(125, max(0, $usage * 100)));
                                @endphp
                                <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-500">
                                    {{ __(':pct% of planned spend used.', ['pct' => $usagePct]) }}
                                </div>
                            </div>
                            <a
                                href="{{ route('expenses.index') }}"
                                wire:navigate
                                class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-900 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                            >
                                <flux:icon.plus class="size-4" />
                                {{ __('Add expense') }}
                            </a>
                        </div>

                        <div class="mt-3">
                            <div class="h-2 w-full overflow-hidden rounded-full bg-zinc-200/80 dark:bg-zinc-800">
                                @php
                                    $barClass = $usagePct >= 100 ? 'bg-rose-500' : ($usagePct >= 85 ? 'bg-amber-500' : 'bg-emerald-500');
                                @endphp
                                <div class="h-full {{ $barClass }}" style="width: {{ min(100, $usagePct) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900 lg:col-span-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <flux:heading size="sm">{{ __('Setup checklist') }}</flux:heading>
                    <flux:subheading class="text-sm">{{ __('Finish these to get the most value.') }}</flux:subheading>
                </div>
                <flux:icon.check-badge class="size-5 text-emerald-600 dark:text-emerald-400" />
            </div>

            <div class="mt-4 space-y-2">
                @foreach ($this->setupChecklist as $item)
                    <a
                        href="{{ $item['route'] }}"
                        wire:navigate
                        class="group flex items-start gap-3 rounded-xl border border-zinc-200 bg-white p-3 transition hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-950/40 dark:hover:border-zinc-600 dark:hover:bg-zinc-950/60"
                    >
                        <div class="mt-0.5">
                            @if ($item['done'])
                                <div class="flex size-7 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">
                                    <flux:icon.check class="size-4" />
                                </div>
                            @else
                                <div class="flex size-7 items-center justify-center rounded-full bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                    @switch($item['icon'])
                                        @case('layout-grid')
                                            <flux:icon.layout-grid class="size-4 opacity-90" />
                                            @break
                                        @case('clipboard-document-list')
                                            <flux:icon.clipboard-document-list class="size-4 opacity-90" />
                                            @break
                                        @case('banknotes')
                                            <flux:icon.banknotes class="size-4 opacity-90" />
                                            @break
                                        @case('receipt-percent')
                                            <flux:icon.receipt-percent class="size-4 opacity-90" />
                                            @break
                                        @default
                                            <flux:icon.sparkles class="size-4 opacity-90" />
                                    @endswitch
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-3">
                                <div class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $item['title'] }}
                                </div>
                                <div class="shrink-0 text-xs {{ $item['done'] ? 'text-emerald-700 dark:text-emerald-300' : 'text-zinc-500 dark:text-zinc-400' }}">
                                    {{ $item['done'] ? __('Done') : __('Next') }}
                                </div>
                            </div>
                            <div class="mt-0.5 text-xs text-zinc-600 dark:text-zinc-400">
                                {{ $item['description'] }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-12">
        <div class="space-y-4 lg:col-span-8">
            <div class="rounded-2xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-4 dark:border-zinc-700">
                    <flux:heading size="sm">{{ __('Budgets at risk') }}</flux:heading>
                    <a
                        href="{{ route('summary.index') }}"
                        wire:navigate
                        class="text-sm font-medium text-zinc-700 transition hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white"
                    >
                        {{ __('Open summary') }}
                    </a>
                </div>

                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($this->budgetRows as $row)
                        @php
                            $pct = (int) round(min(125, max(0, $row['ratio'] * 100)));
                            $pctWidth = min(100, $pct);
                            $danger = $row['remaining'] < 0;
                            $warn = ! $danger && $pct >= 85;
                            $bar = $danger ? 'bg-rose-500' : ($warn ? 'bg-amber-500' : 'bg-emerald-500');
                        @endphp
                        <div class="px-5 py-4">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $row['category'] }}
                                    </div>
                                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-zinc-600 dark:text-zinc-400">
                                        <span>{{ __('Spent :spent', ['spent' => number_format($row['spent'], 2)]) }}</span>
                                        <span class="text-zinc-300 dark:text-zinc-700">/</span>
                                        <span>{{ __('Budget :expected', ['expected' => number_format($row['expected'], 2)]) }}</span>
                                        <span class="text-zinc-300 dark:text-zinc-700">/</span>
                                        <span class="{{ $danger ? 'text-rose-700 dark:text-rose-300' : 'text-emerald-700 dark:text-emerald-300' }}">
                                            {{ __('Remaining :remaining', ['remaining' => number_format($row['remaining'], 2)]) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="shrink-0 text-xs font-medium {{ $danger ? 'text-rose-700 dark:text-rose-300' : ($warn ? 'text-amber-700 dark:text-amber-300' : 'text-zinc-600 dark:text-zinc-300') }}">
                                    {{ $pct }}%
                                </div>
                            </div>

                            <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-800">
                                <div class="h-full {{ $bar }}" style="width: {{ $pctWidth }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('No budgets yet for this month. Add budgets to track progress here.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-4 lg:col-span-4">
            <div class="rounded-2xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-4 dark:border-zinc-700">
                    <flux:heading size="sm">{{ __('Recent expenses') }}</flux:heading>
                    <a
                        href="{{ route('expenses.index') }}"
                        wire:navigate
                        class="text-sm font-medium text-zinc-700 transition hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white"
                    >
                        {{ __('View all') }}
                    </a>
                </div>

                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($this->recentExpenses as $expense)
                        <div class="flex items-center justify-between gap-3 px-5 py-4">
                            <div class="min-w-0">
                                <div class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $expense->category?->name ?? __('Uncategorized') }}
                                </div>
                                <div class="mt-0.5 text-xs text-zinc-600 dark:text-zinc-400">
                                    {{ $expense->expense_date->format('M j') }}
                                </div>
                            </div>
                            <div class="shrink-0 text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ number_format($expense->amount, 2) }}
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('No expenses yet for this month. Log one to start building insights.') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-4 dark:border-zinc-700">
                    <div class="flex items-center gap-2">
                        <flux:icon.sparkles class="size-4 text-zinc-700 dark:text-zinc-200" />
                        <flux:heading size="sm">{{ __('AI insight') }}</flux:heading>
                    </div>
                    <a
                        href="{{ route('advisor.index') }}"
                        wire:navigate
                        class="text-sm font-medium text-zinc-700 transition hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white"
                    >
                        {{ __('Open') }}
                    </a>
                </div>

                <div class="px-5 py-4">
                    @if ($this->currentRecommendation)
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('Updated :time.', ['time' => $this->currentRecommendation->updated_at->format('M j, Y g:i A')]) }}
                        </div>
                        <div class="mt-3 line-clamp-6 whitespace-pre-wrap text-sm text-zinc-700 dark:text-zinc-200">
                            {{ $this->currentRecommendation->response_text }}
                        </div>
                    @else
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('No saved advice yet for this month. Generate recommendations to get next steps.') }}
                        </div>
                        <a
                            href="{{ route('advisor.index') }}"
                            wire:navigate
                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            <flux:icon.sparkles class="size-4" />
                            {{ __('Generate advice') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
