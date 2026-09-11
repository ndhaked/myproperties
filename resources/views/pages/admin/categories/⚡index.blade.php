<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Category;
use Illuminate\Validation\Rule;

new #[Layout('layouts::admin')] class extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $subtitle = '';
    public string $icon = '';
    public string $color = '#EEF2FF';
    public string $iconColor = '#6366F1';
    public int $sortOrder = 0;
    public bool $isActive = true;

    #[Computed]
    public function categories()
    {
        return Category::withCount('properties')->orderBy('sort_order')->orderBy('title')->get();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'title', 'subtitle', 'icon', 'sortOrder']);
        $this->color = '#EEF2FF';
        $this->iconColor = '#6366F1';
        $this->isActive = true;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->editingId = $category->id;
        $this->title = $category->title;
        $this->subtitle = $category->subtitle ?? '';
        $this->icon = $category->icon ?? '';
        $this->color = $category->color ?? '#EEF2FF';
        $this->iconColor = $category->icon_color ?? '#6366F1';
        $this->sortOrder = $category->sort_order;
        $this->isActive = $category->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('categories', 'title')->ignore($this->editingId)],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'iconColor' => ['nullable', 'string', 'max:20'],
            'sortOrder' => ['integer', 'min:0'],
            'isActive' => ['boolean'],
        ]);

        Category::updateOrCreate(
            ['id' => $this->editingId],
            [
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'icon' => $data['icon'],
                'color' => $data['color'],
                'icon_color' => $data['iconColor'],
                'sort_order' => $data['sortOrder'],
                'is_active' => $data['isActive'],
            ]
        );

        unset($this->categories);

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        $category = Category::withCount('properties')->findOrFail($id);

        if ($category->properties_count > 0) {
            $this->addError('delete', 'Cannot delete a category that has properties.');
            return;
        }

        $category->delete();

        unset($this->categories);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }
}
?>

<div>
    <x-slot:header>
        <h2 class="text-lg font-semibold text-gray-900">Categories</h2>
    </x-slot:header>

    <div class="flex justify-end mb-4">
        <button wire:click="create" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            + Add Category
        </button>
    </div>

    @error('delete')
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Title</th>
                    <th class="px-4 py-3 font-medium">Subtitle</th>
                    <th class="px-4 py-3 font-medium">Colors</th>
                    <th class="px-4 py-3 font-medium">Properties</th>
                    <th class="px-4 py-3 font-medium">Active</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($this->categories as $category)
                    <tr wire:key="category-{{ $category->id }}">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $category->title }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $category->subtitle }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full" style="background-color: {{ $category->color }}; color: {{ $category->icon_color }};">
                                {{ $category->icon }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $category->properties_count }}</td>
                        <td class="px-4 py-3">
                            @if ($category->is_active)
                                <span class="inline-flex text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">Active</span>
                            @else
                                <span class="text-gray-400 text-xs">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="edit({{ $category->id }})" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</button>
                            <button
                                wire:click="delete({{ $category->id }})"
                                wire:confirm="Are you sure you want to delete this category?"
                                class="text-red-600 hover:text-red-800 font-medium"
                            >Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/30 px-4" wire:key="category-modal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $editingId ? 'Edit Category' : 'Add Category' }}</h3>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input wire:model="title" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle</label>
                        <input wire:model="subtitle" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Icon <span class="text-gray-400 font-normal">(icon key, e.g. rocket)</span></label>
                        <input wire:model="icon" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Background Color</label>
                            <input wire:model="color" type="color" class="mt-1 block w-full h-10 rounded-lg border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Icon Color</label>
                            <input wire:model="iconColor" type="color" class="mt-1 block w-full h-10 rounded-lg border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input wire:model="sortOrder" type="number" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="flex items-center gap-2">
                        <input wire:model="isActive" type="checkbox" id="isActive" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label for="isActive" class="text-sm text-gray-700">Active</label>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="closeModal" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
