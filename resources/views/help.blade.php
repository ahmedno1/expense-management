<x-layouts::app :title="__('Help')">
    <div class="space-y-6">
        <div>
            <flux:heading>{{ __('Help') }}</flux:heading>
            <flux:subheading>{{ __('A quick guide to get the most out of :app.', ['app' => config('app.name')]) }}</flux:subheading>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-start gap-3">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-700 dark:text-emerald-300">
                        <flux:icon.check-badge class="size-5" />
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Get started') }}</div>
                        <div class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('Create categories, set monthly budgets, add income sources, then log expenses daily.') }}
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="{{ route('categories.index') }}" wire:navigate class="text-sm font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-4 hover:decoration-zinc-500 dark:text-zinc-100">
                                {{ __('Categories') }}
                            </a>
                            <a href="{{ route('budgets.index') }}" wire:navigate class="text-sm font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-4 hover:decoration-zinc-500 dark:text-zinc-100">
                                {{ __('Budgets') }}
                            </a>
                            <a href="{{ route('income.index') }}" wire:navigate class="text-sm font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-4 hover:decoration-zinc-500 dark:text-zinc-100">
                                {{ __('Income') }}
                            </a>
                            <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-4 hover:decoration-zinc-500 dark:text-zinc-100">
                                {{ __('Expenses') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-start gap-3">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-blue-500/10 text-blue-700 dark:text-blue-300">
                        <flux:icon.chart-bar class="size-5" />
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Understand your month') }}</div>
                        <div class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('Use Summary to spot overspending early and keep budgets realistic.') }}
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('summary.index') }}" wire:navigate class="text-sm font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-4 hover:decoration-zinc-500 dark:text-zinc-100">
                                {{ __('Open Summary') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-start gap-3">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-fuchsia-500/10 text-fuchsia-700 dark:text-fuchsia-300">
                        <flux:icon.sparkles class="size-5" />
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('AI Advisor') }}</div>
                        <div class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('Generate actionable recommendations based on this month only. Nothing changes automatically.') }}
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('advisor.index') }}" wire:navigate class="text-sm font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-4 hover:decoration-zinc-500 dark:text-zinc-100">
                                {{ __('Open AI Advisor') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Dashboard tips') }}</div>
                    <div class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('If “Budget usage” hits ~85%, consider moving money between categories before the month ends.') }}
                    </div>
                </div>
                <a
                    href="{{ route('dashboard') }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                >
                    <flux:icon.layout-grid class="size-4" />
                    {{ __('Back to dashboard') }}
                </a>
            </div>
        </div>
    </div>
</x-layouts::app>

