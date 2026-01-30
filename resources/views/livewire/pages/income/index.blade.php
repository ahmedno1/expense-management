<div class="space-y-6">
    <div>
        <flux:heading>{{ __('Income Sources') }}</flux:heading>
        <flux:subheading>{{ __('Track income sources for :month.', ['month' => $this->monthLabel]) }}</flux:subheading>
    </div>

    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
        <form wire:submit="create" class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1 grid gap-4 md:grid-cols-2">
                <div>
                    <flux:input
                        wire:model="source"
                        :label="__('Source')"
                        type="text"
                        required
                        autocomplete="off"
                        placeholder="e.g. Salary"
                    />

                    @error('source')
                        <flux:text class="mt-2 text-red-600">{{ $message }}</flux:text>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="amount"
                        :label="__('Amount')"
                        type="number"
                        step="0.01"
                        min="0"
                        required
                        autocomplete="off"
                        placeholder="0.00"
                    />

                    @error('amount')
                        <flux:text class="mt-2 text-red-600">{{ $message }}</flux:text>
                    @enderror
                </div>
            </div>

            <flux:button variant="primary" type="submit">
                {{ __('Add income') }}
            </flux:button>
        </form>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
        <div class="flex items-center justify-between">
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Total income') }}
            </flux:text>
            <flux:text class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                {{ number_format($this->totalIncome, 2) }}
            </flux:text>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($this->incomeSources as $income)
                <div class="flex flex-col gap-4 px-4 py-4 md:flex-row md:items-center md:justify-between">
                    @if ($editingId === $income->id)
                        <div class="flex-1 grid gap-4 md:grid-cols-2">
                            <div>
                                <flux:input
                                    wire:model="editingSource"
                                    :label="__('Source')"
                                    type="text"
                                    required
                                    autocomplete="off"
                                />

                                @error('editingSource')
                                    <flux:text class="mt-2 text-red-600">{{ $message }}</flux:text>
                                @enderror
                            </div>

                            <div>
                                <flux:input
                                    wire:model="editingAmount"
                                    :label="__('Amount')"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    required
                                    autocomplete="off"
                                />

                                @error('editingAmount')
                                    <flux:text class="mt-2 text-red-600">{{ $message }}</flux:text>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:button variant="primary" wire:click="update">
                                {{ __('Save') }}
                            </flux:button>
                            <flux:button variant="outline" wire:click="cancelEdit">
                                {{ __('Cancel') }}
                            </flux:button>
                        </div>
                    @else
                        <div class="flex-1">
                            <flux:text class="text-base font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $income->source }}
                            </flux:text>
                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ number_format($income->amount, 2) }}
                            </flux:text>
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:button variant="outline" wire:click="edit({{ $income->id }})">
                                {{ __('Edit') }}
                            </flux:button>
                            <flux:button
                                variant="danger"
                                wire:click="delete({{ $income->id }})"
                                onclick="return confirm('{{ __('Delete this income source?') }}')"
                            >
                                {{ __('Delete') }}
                            </flux:button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-4 py-6 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('No income sources yet for this month.') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
