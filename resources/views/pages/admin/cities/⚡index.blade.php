<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\City;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::admin')] class extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public bool $isPopular = true;
    public int $sortOrder = 0;
    public $image = null;
    public ?string $existingImage = null;

    #[Computed]
    public function cities()
    {
        return City::withCount('properties')->orderBy('sort_order')->orderBy('name')->get();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'sortOrder', 'image', 'existingImage']);
        $this->isPopular = true;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $city = City::findOrFail($id);

        $this->editingId = $city->id;
        $this->name = $city->name;
        $this->isPopular = $city->is_popular;
        $this->sortOrder = $city->sort_order;
        $this->existingImage = $city->image;
        $this->image = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('cities', 'name')->ignore($this->editingId)],
            'isPopular' => ['boolean'],
            'sortOrder' => ['integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = $this->existingImage;

        if ($this->image) {
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }

            $imagePath = $this->image->store('cities', 'public');
        }

        City::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $data['name'],
                'is_popular' => $data['isPopular'],
                'sort_order' => $data['sortOrder'],
                'image' => $imagePath,
            ]
        );

        unset($this->cities);

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        $city = City::withCount('properties')->findOrFail($id);

        if ($city->properties_count > 0) {
            $this->addError('delete', 'Cannot delete a city that has properties.');
            return;
        }

        if ($city->image) {
            Storage::disk('public')->delete($city->image);
        }

        $city->delete();

        unset($this->cities);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }
}
?>

<div>
    <x-slot:header>
        <h2 class="text-lg font-semibold text-gray-900">Cities</h2>
    </x-slot:header>

    <div class="flex justify-end mb-4">
        <button wire:click="create" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            + Add City
        </button>
    </div>

    @error('delete')
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Image</th>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Popular</th>
                    <th class="px-4 py-3 font-medium">Properties</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($this->cities as $city)
                    <tr wire:key="city-{{ $city->id }}">
                        <td class="px-4 py-3">
                            <div class="h-10 w-10 rounded-lg bg-gray-100 overflow-hidden">
                                @if ($city->image)
                                    <img src="{{ Storage::url($city->image) }}" class="h-full w-full object-cover">
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $city->name }}</td>
                        <td class="px-4 py-3">
                            @if ($city->is_popular)
                                <span class="inline-flex text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">Yes</span>
                            @else
                                <span class="text-gray-400 text-xs">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $city->properties_count }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="edit({{ $city->id }})" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</button>
                            <button
                                wire:click="delete({{ $city->id }})"
                                wire:confirm="Are you sure you want to delete this city?"
                                class="text-red-600 hover:text-red-800 font-medium"
                            >Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">No cities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/30 px-4" wire:key="city-modal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $editingId ? 'Edit City' : 'Add City' }}</h3>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image</label>
                        @if ($existingImage && ! $image)
                            <img src="{{ Storage::url($existingImage) }}" class="h-16 w-16 object-cover rounded-lg mt-1 mb-2">
                        @endif
                        <input wire:model="image" type="file" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-indigo-700 file:font-medium">
                        @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input wire:model="sortOrder" type="number" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="flex items-center gap-2">
                        <input wire:model="isPopular" type="checkbox" id="isPopular" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label for="isPopular" class="text-sm text-gray-700">Show in popular cities</label>
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
