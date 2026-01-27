<div class="space-y-6">
    <div>
        <flux:heading>{{ __('Categories') }}</flux:heading>
        <flux:subheading>{{ __('Manage the global spending categories used throughout the system.') }}</flux:subheading>
    </div>

    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
        <form wire:submit="create" class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <flux:input
                    wire:model="name"
                    :label="__('Category name')"
                    type="text"
                    required
                    autocomplete="off"
                    placeholder="e.g. Food"
                />

                @error('name')
                    <flux:text class="mt-2 text-red-600">{{ $message }}</flux:text>
                @enderror
            </div>

            <flux:button variant="primary" type="submit">
                {{ __('Add category') }}
            </flux:button>
        </form>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($this->categories as $category)
                <div class="flex flex-col gap-4 px-4 py-4 md:flex-row md:items-center md:justify-between">
                    @if ($editingId === $category->id)
                        <div class="flex-1">
                            <flux:input
                                wire:model="editingName"
                                :label="__('Edit category')"
                                type="text"
                                required
                                autocomplete="off"
                            />

                            @error('editingName')
                                <flux:text class="mt-2 text-red-600">{{ $message }}</flux:text>
                            @enderror
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
                                {{ $category->name }}
                            </flux:text>
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:button variant="outline" wire:click="edit({{ $category->id }})">
                                {{ __('Edit') }}
                            </flux:button>
                            <flux:button
                                variant="danger"
                                wire:click="delete({{ $category->id }})"
                                onclick="return confirm('{{ __('Delete this category?') }}')"
                            >
                                {{ __('Delete') }}
                            </flux:button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-4 py-6 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('No categories yet. Add your first category above.') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
