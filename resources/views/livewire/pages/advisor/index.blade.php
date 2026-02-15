<div class="space-y-6">
    <div>
        <flux:heading>{{ __('AI Advisor') }}</flux:heading>
        <flux:subheading>{{ __('Get tailored recommendations based on your income, budgets, and expenses for :month.', ['month' => $this->monthLabel]) }}</flux:subheading>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
        <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('The AI Advisor reviews your current month totals and category budgets to suggest ways to improve your plan. No changes are applied automatically.') }}
        </flux:text>
    </div>

    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Current month') }}
                </flux:text>
                <flux:text class="text-base font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $this->monthLabel }}
                </flux:text>
            </div>

            <flux:button
                variant="primary"
                wire:click="requestRecommendation"
                wire:loading.attr="disabled"
                wire:target="requestRecommendation"
                :disabled="$isGenerating"
            >
                {{ $isGenerating ? __('Analyzing...') : __('Improve My Plan (This Month)') }}
            </flux:button>
        </div>

        @if ($isGenerating)
            <div class="mt-3 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Gathering this month\'s data and asking the advisor...') }}
            </div>
        @endif
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        <div class="border-b border-zinc-200 dark:border-zinc-700 px-4 py-3">
            <flux:heading size="sm">{{ __('Latest recommendation') }}</flux:heading>
            <flux:subheading class="text-sm">
                {{ __('Saved for :month.', ['month' => $this->monthLabel]) }}
            </flux:subheading>
        </div>

        <div class="p-4">
            @if ($this->currentRecommendation)
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('Updated :time.', ['time' => $this->currentRecommendation->updated_at->format('M j, Y g:i A')]) }}
                </flux:text>
                <div class="mt-3 whitespace-pre-wrap text-sm text-zinc-700 dark:text-zinc-200">
                    {{ $this->currentRecommendation->response_text }}
                </div>
            @else
                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('No recommendation saved for this month yet. Click the button above to generate one.') }}
                </div>
            @endif
        </div>
    </div>

    @once
        @push('scripts')
            <script src="https://js.puter.com/v2/"></script>
            <script>
                document.addEventListener('livewire:init', () => {
                    window.addEventListener('advisor:generate', async (event) => {
                        const detail = event.detail ?? {};
                        const prompt = detail.prompt ?? '';
                        const component = @this;

                        if (!prompt) {
                            component.call('handleAdvisorError', 'Unable to build the advisor prompt. Please try again.');
                            return;
                        }

                        if (!window.puter || !window.puter.ai || typeof window.puter.ai.chat !== 'function') {
                            component.call('handleAdvisorError', 'Puter.js is not available. Please sign in to Puter and refresh.');
                            return;
                        }

                        try {
                            if (window.puter.auth && typeof window.puter.auth.isSignedIn === 'function') {
                                const signedIn = await window.puter.auth.isSignedIn();
                                if (!signedIn) {
                                    component.call('handleAdvisorError', 'Please sign in to Puter to use the AI advisor.');
                                    return;
                                }
                            }

                            const response = await window.puter.ai.chat(prompt, { model: 'gpt-5-nano' });
                            let content = response?.message?.content ?? response?.content ?? response?.text ?? response?.message ?? '';

                            if (!content && typeof response === 'string') {
                                content = response;
                            }

                            if (!content) {
                                content = JSON.stringify(response);
                            }

                            content = String(content).trim();

                            if (!content) {
                                component.call('handleAdvisorError', 'The AI response was empty. Please try again.');
                                return;
                            }

                            component.call('saveRecommendation', content, prompt);
                        } catch (error) {
                            component.call('handleAdvisorError', 'Puter AI request failed. Please try again.');
                        }
                    });
                });
            </script>
        @endpush
    @endonce
</div>
