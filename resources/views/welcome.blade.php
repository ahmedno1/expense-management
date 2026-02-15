<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-zinc-950">
        <div class="relative isolate overflow-hidden">
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(80rem_28rem_at_10%_0%,rgba(16,185,129,0.22),transparent_60%),radial-gradient(70rem_24rem_at_90%_20%,rgba(59,130,246,0.18),transparent_55%),radial-gradient(60rem_28rem_at_50%_120%,rgba(244,63,94,0.10),transparent_55%)]"></div>

            <div class="mx-auto max-w-6xl px-6 py-10 md:py-14">
                <header class="flex items-center justify-between gap-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3" wire:navigate>
                        <span class="flex size-10 items-center justify-center rounded-xl bg-zinc-900 text-white shadow-sm dark:bg-white dark:text-zinc-900">
                            <x-app-logo-icon class="size-6" />
                        </span>
                        <span class="text-sm font-semibold text-zinc-900 dark:text-white">
                            {{ config('app.name') }}
                        </span>
                    </a>

                    <div class="flex items-center gap-2">
                        @auth
                            <a
                                href="{{ route('dashboard') }}"
                                wire:navigate
                                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                            >
                                <flux:icon.layout-grid class="size-4" />
                                {{ __('Open dashboard') }}
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-900 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                            >
                                {{ __('Log in') }}
                            </a>
                            <a
                                href="{{ route('register') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                            >
                                {{ __('Create account') }}
                            </a>
                        @endauth
                    </div>
                </header>

                <main class="mt-12 grid gap-10 lg:grid-cols-12 lg:items-center">
                    <div class="lg:col-span-7">
                        <div class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white/60 px-3 py-1 text-xs font-medium text-zinc-700 shadow-sm backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/50 dark:text-zinc-200">
                            <flux:icon.sparkles class="size-4" />
                            {{ __('Budget smarter this month') }}
                        </div>

                        <h1 class="mt-4 text-balance text-4xl font-semibold tracking-tight text-zinc-900 dark:text-white md:text-5xl">
                            {{ __('Track expenses, plan budgets, and fix overspending before it happens.') }}
                        </h1>
                        <p class="mt-4 max-w-xl text-pretty text-base text-zinc-600 dark:text-zinc-300">
                            {{ __('Add income sources, set category budgets, log expenses daily, then get clear insights and AI recommendations for the current month.') }}
                        </p>

                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            @auth
                                <a
                                    href="{{ route('dashboard') }}"
                                    wire:navigate
                                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                                >
                                    <flux:icon.layout-grid class="size-4" />
                                    {{ __('Go to dashboard') }}
                                </a>
                                <a
                                    href="{{ route('expenses.index') }}"
                                    wire:navigate
                                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-medium text-zinc-900 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                                >
                                    <flux:icon.receipt-percent class="size-4" />
                                    {{ __('Log an expense') }}
                                </a>
                            @else
                                <a
                                    href="{{ route('register') }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                                >
                                    {{ __('Get started') }}
                                </a>
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-medium text-zinc-900 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                                >
                                    {{ __('Sign in') }}
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="rounded-2xl border border-zinc-200 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/60">
                            <div class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('What you can do') }}</div>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-start gap-3 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-950/30">
                                    <div class="flex size-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-700 dark:text-emerald-300">
                                        <flux:icon.receipt-percent class="size-5" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Expenses') }}</div>
                                        <div class="mt-0.5 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Log spending and keep a clean history.') }}</div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-950/30">
                                    <div class="flex size-10 items-center justify-center rounded-xl bg-blue-500/10 text-blue-700 dark:text-blue-300">
                                        <flux:icon.clipboard-document-list class="size-5" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Budgets') }}</div>
                                        <div class="mt-0.5 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Plan expected spend per category and stay on track.') }}</div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-950/30">
                                    <div class="flex size-10 items-center justify-center rounded-xl bg-fuchsia-500/10 text-fuchsia-700 dark:text-fuchsia-300">
                                        <flux:icon.sparkles class="size-5" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('AI Advisor') }}</div>
                                        <div class="mt-0.5 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Get recommendations for the current month.') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="mt-14 flex flex-col gap-3 border-t border-zinc-200 pt-6 text-sm text-zinc-600 dark:border-zinc-800 dark:text-zinc-400 md:flex-row md:items-center md:justify-between">
                    <div>
                        {{ config('app.name') }} &copy; {{ now()->year }}
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('help') }}" wire:navigate class="font-medium text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white">
                                {{ __('Help') }}
                            </a>
                        @endauth
                    </div>
                </footer>
            </div>
        </div>

        @fluxScripts
    </body>
</html>

