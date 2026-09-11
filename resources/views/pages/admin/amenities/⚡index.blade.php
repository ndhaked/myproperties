<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Amenity;
use Illuminate\Validation\Rule;

new #[Layout('layouts::admin')] class extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $icon = '';

    #[Computed]
    public function amenities()
    {
        return Amenity::withCount('properties')->orderBy('name')->get();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'icon']);
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $amenity = Amenity::findOrFail($id);

        $this->editingId = $amenity->id;
        $this->name = $amenity->name;
        $this->icon = $amenity->icon ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('amenities', 'name')->ignore($this->editingId)],
            'icon' => ['nullable', 'string', 'max:100'],
        ]);

        Amenity::updateOrCreate(['id' => $this->editingId], $data);

        unset($this->amenities);

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        Amenity::findOrFail($id)->delete();

        unset($this->amenities);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }
}
?>

<div>
    <x-slot:header>
        <h2 class="text-lg font-semibold text-gray-900">Amenities</h2>
    </x-slot:header>

    <div class="flex justify-end mb-4">
        <button wire:click="create" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            + Add Amenity
        </button>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Icon</th>
                    <th class="px-4 py-3 font-medium">Properties</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($this->amenities as $amenity)
                    <tr wire:key="amenity-{{ $amenity->id }}">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $amenity->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $amenity->icon }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $amenity->properties_count }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="edit({{ $amenity->id }})" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</button>
                            <button
                                wire:click="delete({{ $amenity->id }})"
                                wire:confirm="Are you sure you want to delete this amenity?"
                                class="text-red-600 hover:text-red-800 font-medium"
                            >Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">No amenities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/30 px-4" wire:key="amenity-modal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $editingId ? 'Edit Amenity' : 'Add Amenity' }}</h3>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Icon <span class="text-gray-400 font-normal">(icon key, e.g. water_drop)</span></label>
                        <input wire:model="icon" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('icon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
