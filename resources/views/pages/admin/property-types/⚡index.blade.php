<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\PropertyType;
use Illuminate\Validation\Rule;

new #[Layout('layouts::admin')] class extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public int $sortOrder = 0;

    #[Computed]
    public function propertyTypes()
    {
        return PropertyType::withCount('properties')->orderBy('sort_order')->orderBy('name')->get();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'sortOrder']);
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $type = PropertyType::findOrFail($id);

        $this->editingId = $type->id;
        $this->name = $type->name;
        $this->sortOrder = $type->sort_order;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('property_types', 'name')->ignore($this->editingId)],
            'sortOrder' => ['integer', 'min:0'],
        ]);

        PropertyType::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $data['name'], 'sort_order' => $data['sortOrder']]
        );

        unset($this->propertyTypes);

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        $type = PropertyType::withCount('properties')->findOrFail($id);

        if ($type->properties_count > 0) {
            $this->addError('delete', 'Cannot delete a property type that is in use.');
            return;
        }

        $type->delete();

        unset($this->propertyTypes);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }
}
?>

<div>
    <x-slot:header>
        <h2 class="text-lg font-semibold text-gray-900">Property Types</h2>
    </x-slot:header>

    <div class="flex justify-end mb-4">
        <button wire:click="create" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            + Add Property Type
        </button>
    </div>

    @error('delete')
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Properties</th>
                    <th class="px-4 py-3 font-medium">Sort Order</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($this->propertyTypes as $type)
                    <tr wire:key="type-{{ $type->id }}">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $type->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $type->properties_count }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $type->sort_order }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="edit({{ $type->id }})" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</button>
                            <button
                                wire:click="delete({{ $type->id }})"
                                wire:confirm="Are you sure you want to delete this property type?"
                                class="text-red-600 hover:text-red-800 font-medium"
                            >Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">No property types found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/30 px-4" wire:key="type-modal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $editingId ? 'Edit Property Type' : 'Add Property Type' }}</h3>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input wire:model="sortOrder" type="number" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
