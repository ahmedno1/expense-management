<div class="space-y-6">
    <div>
        <flux:heading>{{ __('Budgets') }}</flux:heading>
        <flux:subheading>{{ __('Set expected category budgets for :month.', ['month' => $this->monthLabel]) }}</flux:subheading>
    </div>

    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        <form wire:submit="save" class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($this->categories as $category)
                <div class="flex flex-col gap-4 px-4 py-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex-1">
                        <flux:text class="text-base font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $category->name }}
                        </flux:text>
                    </div>

                    <div class="w-full md:w-56">
                        <flux:input
                            wire:model.defer="expectedAmounts.{{ $category->id }}"
                            :label="__('Expected amount')"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                            autocomplete="off"
                            placeholder="0.00"
                        />

                        @error('expectedAmounts.' . $category->id)
                            <flux:text class="mt-2 text-red-600">{{ $message }}</flux:text>
                        @enderror
                    </div>
                </div>
            @empty
                <div class="px-4 py-6 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('No categories yet. Add categories first.') }}
                </div>
            @endforelse

            @if ($this->categories->isNotEmpty())
                <div class="flex justify-end px-4 py-4">
                    <flux:button variant="primary" type="submit">
                        {{ __('Save budgets') }}
                    </flux:button>
                </div>
            @endif
        </form>
    </div>
</div>
