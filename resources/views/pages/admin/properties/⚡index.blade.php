<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use App\Models\Property;
use App\Models\City;
use App\Models\PropertyType;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $city = '';

    #[Url]
    public string $propertyType = '';

    public function updating($property): void
    {
        if (in_array($property, ['search', 'status', 'city', 'propertyType'])) {
            $this->resetPage();
        }
    }

    public function updateStatus(int $id, string $status): void
    {
        if (! in_array($status, Property::STATUS_OPTIONS)) {
            return;
        }

        Property::whereKey($id)->update(['status' => $status]);
    }

    public function toggleFeatured(int $id): void
    {
        $property = Property::findOrFail($id);
        $property->update(['is_featured' => ! $property->is_featured]);
    }

    public function delete(int $id): void
    {
        $property = Property::with(['images', 'documents'])->findOrFail($id);

        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        foreach ($property->documents as $document) {
            Storage::disk('public')->delete($document->path);
        }

        $property->delete();
    }

    public function with(): array
    {
        $properties = Property::query()
            ->with(['city', 'propertyType', 'category'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->city, fn ($q) => $q->where('city_id', $this->city))
            ->when($this->propertyType, fn ($q) => $q->where('property_type_id', $this->propertyType))
            ->latest()
            ->paginate(10);

        return [
            'properties' => $properties,
            'cities' => City::orderBy('name')->get(),
            'propertyTypes' => PropertyType::orderBy('name')->get(),
        ];
    }
}
?>

<div>
    <x-slot:header>
        <h2 class="text-lg font-semibold text-gray-900">Properties</h2>
    </x-slot:header>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div class="flex flex-wrap items-center gap-2">
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="Search by title..."
                class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

            <select wire:model.live="status" class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                @foreach (\App\Models\Property::STATUS_OPTIONS as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>

            <select wire:model.live="city" class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Cities</option>
                @foreach ($cities as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="propertyType" class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Types</option>
                @foreach ($propertyTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('admin.properties.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 text-center">
            + Add Property
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Property</th>
                    <th class="px-4 py-3 font-medium">Type</th>
                    <th class="px-4 py-3 font-medium">City</th>
                    <th class="px-4 py-3 font-medium">Price</th>
                    <th class="px-4 py-3 font-medium">Views</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Featured</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($properties as $property)
                    <tr wire:key="property-{{ $property->id }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                                    @if ($property->images->first())
                                        <img src="{{ Storage::url($property->images->first()->path) }}" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $property->title }}</p>
                                    <p class="text-xs text-gray-400">{{ $property->category?->title }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $property->propertyType->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $property->city->name }}</td>
                        <td class="px-4 py-3 text-gray-600">₹{{ number_format($property->price_amount) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $property->views }}</td>
                        <td class="px-4 py-3">
                            <select
                                wire:change="updateStatus({{ $property->id }}, $event.target.value)"
                                class="rounded-full text-xs font-medium border-0 py-1 pl-2 pr-7 focus:ring-2 focus:ring-indigo-500
                                    @class([
                                        'bg-emerald-50 text-emerald-700' => $property->status === 'Active',
                                        'bg-gray-100 text-gray-600' => $property->status === 'Inactive',
                                        'bg-amber-50 text-amber-700' => $property->status === 'Sold',
                                    ])"
                            >
                                @foreach (\App\Models\Property::STATUS_OPTIONS as $option)
                                    <option value="{{ $option }}" @selected($property->status === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <button
                                wire:click="toggleFeatured({{ $property->id }})"
                                class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full
                                    @class([
                                        'bg-indigo-50 text-indigo-700' => $property->is_featured,
                                        'bg-gray-100 text-gray-400' => ! $property->is_featured,
                                    ])"
                            >{{ $property->is_featured ? 'Featured' : 'Not featured' }}</button>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.properties.edit', $property) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                            <button
                                wire:click="delete({{ $property->id }})"
                                wire:confirm="Are you sure you want to delete this property? This cannot be undone."
                                class="text-red-600 hover:text-red-800 font-medium"
                            >Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">No properties found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $properties->links() }}
    </div>
</div>
