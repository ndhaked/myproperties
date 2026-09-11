<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Property;
use App\Models\Category;
use App\Models\City;
use App\Models\PropertyType;
use App\Models\Amenity;
use App\Models\Highlight;
use App\Models\PropertyImage;
use App\Models\PropertyDocument;
use App\Models\PropertyNearbyPlace;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

new #[Layout('layouts::admin')] class extends Component
{
    use WithFileUploads;

    public ?Property $property = null;

    public ?int $categoryId = null;
    public ?int $propertyTypeId = null;
    public string $title = '';

    public string $locationLine = '';
    public ?int $cityId = null;
    public string $state = '';
    public string $pincode = '';
    public ?float $lat = null;
    public ?float $lng = null;

    public ?float $priceAmount = null;
    public ?float $pricePerSqft = null;

    public ?int $plotAreaMin = null;
    public ?int $plotAreaMax = null;
    public string $plotAreaUnit = 'sq.ft';
    public string $plotSize = '';

    public string $facing = '';
    public bool $isCornerPlot = false;
    public string $propertyAge = '';
    public ?string $possessionDate = null;
    public string $roadWidth = '';

    public string $description = '';

    public string $badgeLabel = '';
    public string $badgeColor = '#8B5CF6';
    public string $offerText = '';
    public string $offerHighlight = '';
    public string $offerBgColor = '#F3E8FF';
    public string $offerTextColor = '#8B5CF6';

    public string $status = 'Active';

    public array $selectedHighlights = [];
    public array $selectedAmenities = [];

    public array $nearbyPlaces = [];

    public array $newImages = [];
    public array $newDocuments = [];

    public bool $showAdvanced = false;

    public function mount(?Property $property = null): void
    {
        if (! $property?->exists) {
            return;
        }

        $this->property = $property;

        $this->categoryId = $property->category_id;
        $this->propertyTypeId = $property->property_type_id;
        $this->title = $property->title;

        $this->locationLine = $property->location_line ?? '';
        $this->cityId = $property->city_id;
        $this->state = $property->state ?? '';
        $this->pincode = $property->pincode ?? '';
        $this->lat = $property->lat;
        $this->lng = $property->lng;

        $this->priceAmount = $property->price_amount;
        $this->pricePerSqft = $property->price_per_sqft;

        $this->plotAreaMin = $property->plot_area_min;
        $this->plotAreaMax = $property->plot_area_max;
        $this->plotAreaUnit = $property->plot_area_unit ?? 'sq.ft';
        $this->plotSize = $property->plot_size ?? '';

        $this->facing = $property->facing ?? '';
        $this->isCornerPlot = $property->is_corner_plot;
        $this->propertyAge = $property->property_age ?? '';
        $this->possessionDate = $property->possession_date?->format('Y-m-d');
        $this->roadWidth = $property->road_width ?? '';

        $this->description = $property->description ?? '';

        $this->badgeLabel = $property->badge_label ?? '';
        $this->badgeColor = $property->badge_color ?? '#8B5CF6';
        $this->offerText = $property->offer_text ?? '';
        $this->offerHighlight = $property->offer_highlight ?? '';
        $this->offerBgColor = $property->offer_bg_color ?? '#F3E8FF';
        $this->offerTextColor = $property->offer_text_color ?? '#8B5CF6';

        $this->status = $property->status;

        $this->selectedHighlights = $property->highlights->pluck('id')->toArray();
        $this->selectedAmenities = $property->amenities->pluck('id')->toArray();

        $this->nearbyPlaces = $property->nearbyPlaces->map(fn ($place) => [
            'name' => $place->name,
            'distance_km' => (string) $place->distance_km,
            'category' => $place->category,
        ])->toArray();
    }

    #[Computed]
    public function categories()
    {
        return Category::orderBy('title')->get();
    }

    #[Computed]
    public function propertyTypes()
    {
        return PropertyType::orderBy('name')->get();
    }

    #[Computed]
    public function cities()
    {
        return City::orderBy('name')->get();
    }

    #[Computed]
    public function amenities()
    {
        return Amenity::orderBy('name')->get();
    }

    #[Computed]
    public function highlights()
    {
        return Highlight::orderBy('name')->get();
    }

    #[Computed]
    public function existingImages()
    {
        return $this->property?->images ?? collect();
    }

    #[Computed]
    public function existingDocuments()
    {
        return $this->property?->documents ?? collect();
    }

    public function addNearbyPlace(): void
    {
        $this->nearbyPlaces[] = ['name' => '', 'distance_km' => '', 'category' => ''];
    }

    public function removeNearbyPlace(int $index): void
    {
        unset($this->nearbyPlaces[$index]);
        $this->nearbyPlaces = array_values($this->nearbyPlaces);
    }

    public function removeExistingImage(int $imageId): void
    {
        $image = PropertyImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->path);
        $image->delete();

        unset($this->existingImages);
    }

    public function removeExistingDocument(int $documentId): void
    {
        $document = PropertyDocument::findOrFail($documentId);
        Storage::disk('public')->delete($document->path);
        $document->delete();

        unset($this->existingDocuments);
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'categoryId' => ['nullable', 'exists:categories,id'],
            'propertyTypeId' => ['required', 'exists:property_types,id'],
            'locationLine' => ['nullable', 'string', 'max:255'],
            'cityId' => ['required', 'exists:cities,id'],
            'state' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'priceAmount' => ['required', 'numeric', 'min:0'],
            'pricePerSqft' => ['nullable', 'numeric', 'min:0'],
            'plotAreaMin' => ['nullable', 'integer', 'min:0'],
            'plotAreaMax' => ['nullable', 'integer', 'min:0'],
            'plotAreaUnit' => ['required', 'string', 'max:20'],
            'plotSize' => ['nullable', 'string', 'max:255'],
            'facing' => ['nullable', Rule::in(Property::FACING_OPTIONS)],
            'isCornerPlot' => ['boolean'],
            'propertyAge' => ['nullable', Rule::in(Property::PROPERTY_AGE_OPTIONS)],
            'possessionDate' => ['nullable', 'date'],
            'roadWidth' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'badgeLabel' => ['nullable', 'string', 'max:100'],
            'badgeColor' => ['nullable', 'string', 'max:20'],
            'offerText' => ['nullable', 'string', 'max:100'],
            'offerHighlight' => ['nullable', 'string', 'max:100'],
            'offerBgColor' => ['nullable', 'string', 'max:20'],
            'offerTextColor' => ['nullable', 'string', 'max:20'],
            'status' => ['required', Rule::in(Property::STATUS_OPTIONS)],
            'selectedHighlights' => ['array'],
            'selectedHighlights.*' => ['exists:highlights,id'],
            'selectedAmenities' => ['array'],
            'selectedAmenities.*' => ['exists:amenities,id'],
            'nearbyPlaces' => ['array'],
            'nearbyPlaces.*.name' => ['required', 'string', 'max:255'],
            'nearbyPlaces.*.distance_km' => ['required', 'numeric', 'min:0'],
            'nearbyPlaces.*.category' => ['nullable', 'string', 'max:100'],
            'newImages.*' => ['nullable', 'image', 'max:5120'],
            'newDocuments.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $property = Property::updateOrCreate(
            ['id' => $this->property?->id],
            [
                'title' => $data['title'],
                'category_id' => $data['categoryId'],
                'property_type_id' => $data['propertyTypeId'],
                'location_line' => $data['locationLine'],
                'city_id' => $data['cityId'],
                'state' => $data['state'],
                'pincode' => $data['pincode'],
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'price_amount' => $data['priceAmount'],
                'price_per_sqft' => $data['pricePerSqft'],
                'plot_area_min' => $data['plotAreaMin'],
                'plot_area_max' => $data['plotAreaMax'],
                'plot_area_unit' => $data['plotAreaUnit'],
                'plot_size' => $data['plotSize'],
                'facing' => $data['facing'] ?: null,
                'is_corner_plot' => $data['isCornerPlot'],
                'property_age' => $data['propertyAge'] ?: null,
                'possession_date' => $data['possessionDate'],
                'road_width' => $data['roadWidth'],
                'description' => $data['description'],
                'badge_label' => $data['badgeLabel'],
                'badge_color' => $data['badgeColor'],
                'offer_text' => $data['offerText'],
                'offer_highlight' => $data['offerHighlight'],
                'offer_bg_color' => $data['offerBgColor'],
                'offer_text_color' => $data['offerTextColor'],
                'status' => $data['status'],
            ]
        );

        $property->highlights()->sync($data['selectedHighlights']);
        $property->amenities()->sync($data['selectedAmenities']);

        $property->nearbyPlaces()->delete();
        foreach ($data['nearbyPlaces'] as $place) {
            $property->nearbyPlaces()->create($place);
        }

        foreach ($this->newImages as $index => $image) {
            $path = $image->store("properties/{$property->id}/images", 'public');

            PropertyImage::create([
                'property_id' => $property->id,
                'path' => $path,
                'sort_order' => $property->images()->count() + $index,
            ]);
        }

        foreach ($this->newDocuments as $document) {
            $path = $document->store("properties/{$property->id}/documents", 'public');

            PropertyDocument::create([
                'property_id' => $property->id,
                'name' => $document->getClientOriginalName(),
                'path' => $path,
                'type' => $document->getClientOriginalExtension(),
                'size_mb' => round($document->getSize() / 1048576, 2),
            ]);
        }

        session()->flash('status', 'Property saved successfully.');

        $this->redirect(route('admin.properties.index'), navigate: false);
    }
}
?>

<div>
    <x-slot:header>
        <h2 class="text-lg font-semibold text-gray-900">{{ $property ? 'Edit Property' : 'Add Property' }}</h2>
    </x-slot:header>

    <form wire:submit="save" class="space-y-6 max-w-4xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Basic Details</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input wire:model="title" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Property Type</label>
                    <select wire:model="propertyTypeId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select type</option>
                        @foreach ($this->propertyTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('propertyTypeId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select wire:model="categoryId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">None</option>
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                    @error('categoryId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @foreach (\App\Models\Property::STATUS_OPTIONS as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-6">
                    <input wire:model="isCornerPlot" type="checkbox" id="isCornerPlot" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="isCornerPlot" class="text-sm text-gray-700">Corner Plot</label>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Location</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Address Line</label>
                    <input wire:model="locationLine" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">City</label>
                    <select wire:model="cityId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select city</option>
                        @foreach ($this->cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('cityId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">State</label>
                    <input wire:model="state" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Pincode</label>
                    <input wire:model="pincode" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Road Width</label>
                    <input wire:model="roadWidth" type="text" placeholder="e.g. 30 Ft" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input wire:model="lat" type="number" step="any" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input wire:model="lng" type="number" step="any" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Price &amp; Plot</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price (₹)</label>
                    <input wire:model="priceAmount" type="number" step="any" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('priceAmount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Price per sq.ft (₹)</label>
                    <input wire:model="pricePerSqft" type="number" step="any" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Plot Area Min</label>
                    <input wire:model="plotAreaMin" type="number" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Plot Area Max</label>
                    <input wire:model="plotAreaMax" type="number" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Plot Area Unit</label>
                    <input wire:model="plotAreaUnit" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Plot Size</label>
                    <input wire:model="plotSize" type="text" placeholder="e.g. 30 X 40, 40 X 60" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Facing</label>
                    <select wire:model="facing" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Any</option>
                        @foreach (\App\Models\Property::FACING_OPTIONS as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Property Age</label>
                    <select wire:model="propertyAge" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select</option>
                        @foreach (\App\Models\Property::PROPERTY_AGE_OPTIONS as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Possession Date</label>
                    <input wire:model="possessionDate" type="date" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Description</h3>
            <textarea wire:model="description" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Highlights</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach ($this->highlights as $highlight)
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" wire:model="selectedHighlights" value="{{ $highlight->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        {{ $highlight->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Amenities</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach ($this->amenities as $amenity)
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" wire:model="selectedAmenities" value="{{ $amenity->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        {{ $amenity->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Nearby Places</h3>
                <button type="button" wire:click="addNearbyPlace" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Add Place</button>
            </div>

            @foreach ($nearbyPlaces as $index => $place)
                <div class="grid grid-cols-1 sm:grid-cols-[2fr_1fr_1fr_auto] gap-2 items-start" wire:key="nearby-{{ $index }}">
                    <div>
                        <input wire:model="nearbyPlaces.{{ $index }}.name" type="text" placeholder="Place name" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error("nearbyPlaces.{$index}.name") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input wire:model="nearbyPlaces.{{ $index }}.distance_km" type="number" step="any" placeholder="Distance (km)" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <input wire:model="nearbyPlaces.{{ $index }}.category" type="text" placeholder="Category (school, hospital...)" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <button type="button" wire:click="removeNearbyPlace({{ $index }})" class="text-red-600 hover:text-red-800 mt-1.5">
                        <x-icon name="trash" class="w-5 h-5" />
                    </button>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Images</h3>

            @if ($this->existingImages->isNotEmpty())
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                    @foreach ($this->existingImages as $image)
                        <div class="relative" wire:key="existing-image-{{ $image->id }}">
                            <img src="{{ Storage::url($image->path) }}" class="h-20 w-full object-cover rounded-lg border border-gray-200">
                            <button type="button" wire:click="removeExistingImage({{ $image->id }})" wire:confirm="Remove this image?"
                                class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">&times;</button>
                        </div>
                    @endforeach
                </div>
            @endif

            <input wire:model="newImages" type="file" multiple accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-indigo-700 file:font-medium">
            @error('newImages.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

            @if ($newImages)
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                    @foreach ($newImages as $image)
                        <img src="{{ $image->temporaryUrl() }}" class="h-20 w-full object-cover rounded-lg border border-gray-200">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Documents</h3>

            @if ($this->existingDocuments->isNotEmpty())
                <ul class="space-y-2">
                    @foreach ($this->existingDocuments as $document)
                        <li class="flex items-center justify-between text-sm bg-gray-50 rounded-lg px-3 py-2" wire:key="existing-doc-{{ $document->id }}">
                            <a href="{{ Storage::url($document->path) }}" target="_blank" class="text-indigo-600 hover:underline">{{ $document->name }}</a>
                            <span class="text-gray-400">{{ $document->size_mb }} MB</span>
                            <button type="button" wire:click="removeExistingDocument({{ $document->id }})" wire:confirm="Remove this document?" class="text-red-600 hover:text-red-800">Remove</button>
                        </li>
                    @endforeach
                </ul>
            @endif

            <input wire:model="newDocuments" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-indigo-700 file:font-medium">
            @error('newDocuments.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <button type="button" wire:click="$toggle('showAdvanced')" class="text-sm font-semibold text-gray-900 flex items-center gap-1">
                Badge &amp; Offer (optional)
                <x-icon name="chevron-down" class="w-4 h-4 transition-transform {{ $showAdvanced ? 'rotate-180' : '' }}" />
            </button>

            @if ($showAdvanced)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Badge Label</label>
                        <input wire:model="badgeLabel" type="text" placeholder="e.g. Pre-Launch" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Badge Color</label>
                        <input wire:model="badgeColor" type="color" class="mt-1 block w-full h-10 rounded-lg border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Offer Text</label>
                        <input wire:model="offerText" type="text" placeholder="e.g. Pre-Launch Offer" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Offer Highlight</label>
                        <input wire:model="offerHighlight" type="text" placeholder="e.g. Save up to 15%" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Offer Background Color</label>
                        <input wire:model="offerBgColor" type="color" class="mt-1 block w-full h-10 rounded-lg border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Offer Text Color</label>
                        <input wire:model="offerTextColor" type="color" class="mt-1 block w-full h-10 rounded-lg border-gray-300 shadow-sm">
                    </div>
                </div>
            @endif
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.properties.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Save Property</button>
        </div>
    </form>
</div>
