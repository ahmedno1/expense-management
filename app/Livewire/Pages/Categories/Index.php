<?php

namespace App\Livewire\Pages\Categories;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public string $name = '';

    public ?int $editingId = null;
    public string $editingName = '';

    public function create(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ]);

        Category::create($validated);

        $this->reset('name');

        session()->flash('status', __('Category created.'));
    }

    public function edit(int $categoryId): void
    {
        $category = Category::findOrFail($categoryId);

        $this->editingId = $category->id;
        $this->editingName = $category->name;

        $this->resetErrorBag();
    }

    public function update(): void
    {
        if ($this->editingId === null) {
            return;
        }

        $validated = $this->validate([
            'editingName' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($this->editingId),
            ],
        ]);

        $category = Category::findOrFail($this->editingId);
        $category->update(['name' => $validated['editingName']]);

        $this->cancelEdit();

        session()->flash('status', __('Category updated.'));
    }

    public function cancelEdit(): void
    {
        $this->reset('editingId', 'editingName');
        $this->resetErrorBag();
    }

    public function delete(int $categoryId): void
    {
        $category = Category::findOrFail($categoryId);
        $category->delete();

        if ($this->editingId === $categoryId) {
            $this->cancelEdit();
        }

        session()->flash('status', __('Category deleted.'));
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.categories.index')
            ->layout('layouts.app', ['title' => __('Categories')]);
    }
}
