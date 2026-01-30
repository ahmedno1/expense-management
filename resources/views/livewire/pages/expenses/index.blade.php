<div class="space-y-6">
    <div>
        <flux:heading>{{ __('Expenses') }}</flux:heading>
        <flux:subheading>{{ __('Log expenses for :month.', ['month' => $this->monthLabel]) }}</flux:subheading>
    </div>

    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
        <form wire:submit="create" class="flex flex-col gap-4">
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <flux:select wire:model="categoryId" :label="__('Category')" placeholder="{{ __('Select a category') }}" required>
                        @foreach ($this->categories as $category)
                            <flux:select.option value="{{ $category->id }}">
                                {{ $category->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    @error('categoryId')
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

                <div>
                    <flux:input
                        wire:model="expenseDate"
                        :label="__('Expense date')"
                        type="date"
                        required
                    />

                    @error('expenseDate')
                        <flux:text class="mt-2 text-red-600">{{ $message }}</flux:text>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <flux:button variant="primary" type="submit" :disabled="$this->categories->isEmpty()">
                    {{ __('Add expense') }}
                </flux:button>
            </div>

            @if ($this->categories->isEmpty())
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Add categories before logging expenses.') }}
                </flux:text>
            @endif
        </form>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
        <div class="flex items-center justify-between">
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Total spent') }}
            </flux:text>
            <flux:text class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                {{ number_format($this->totalSpent, 2) }}
            </flux:text>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($this->expenses as $expense)
                <div class="flex flex-col gap-4 px-4 py-4 md:flex-row md:items-center md:justify-between">
                    @if ($editingId === $expense->id)
                        <div class="flex-1 grid gap-4 md:grid-cols-3">
                            <div>
                                <flux:select wire:model="editingCategoryId" :label="__('Category')" required>
                                    @foreach ($this->categories as $category)
                                        <flux:select.option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>

                                @error('editingCategoryId')
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

                            <div>
                                <flux:input
                                    wire:model="editingExpenseDate"
                                    :label="__('Expense date')"
                                    type="date"
                                    required
                                />

                                @error('editingExpenseDate')
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
                                {{ $expense->category?->name ?? __('Uncategorized') }}
                            </flux:text>
                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $expense->expense_date->format('M j, Y') }} - {{ number_format($expense->amount, 2) }}
                            </flux:text>
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:button variant="outline" wire:click="edit({{ $expense->id }})">
                                {{ __('Edit') }}
                            </flux:button>
                            <flux:button
                                variant="danger"
                                wire:click="delete({{ $expense->id }})"
                                onclick="return confirm('{{ __('Delete this expense?') }}')"
                            >
                                {{ __('Delete') }}
                            </flux:button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-4 py-6 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('No expenses yet for this month.') }}
                </div>
            @endforelse
        </div>
    </div>
</div>

