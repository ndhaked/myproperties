<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Property;
use App\Models\City;
use App\Models\Category;

new #[Layout('layouts::admin')] class extends Component
{
    public function with(): array
    {
        return [
            'totalProperties' => Property::count(),
            'activeProperties' => Property::where('status', 'Active')->count(),
            'totalCities' => City::count(),
            'totalCategories' => Category::count(),
        ];
    }
}
?>

<div>
    <x-slot:header>
        <h2 class="text-lg font-semibold text-gray-900">Dashboard</h2>
    </x-slot:header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <span class="w-11 h-11 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <x-icon name="building" class="w-6 h-6" />
            </span>
            <div>
                <p class="text-sm text-gray-500">Total Properties</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalProperties }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <span class="w-11 h-11 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <x-icon name="chart" class="w-6 h-6" />
            </span>
            <div>
                <p class="text-sm text-gray-500">Active Properties</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $activeProperties }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <span class="w-11 h-11 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <x-icon name="map-pin" class="w-6 h-6" />
            </span>
            <div>
                <p class="text-sm text-gray-500">Cities</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalCities }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <span class="w-11 h-11 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                <x-icon name="tag" class="w-6 h-6" />
            </span>
            <div>
                <p class="text-sm text-gray-500">Categories</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalCategories }}</p>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-sm text-gray-600">
            Welcome back, <span class="font-medium text-gray-900">{{ auth('admin')->user()->name }}</span>.
            Use the sidebar to manage {{ config('app.name') }}.
        </p>
    </div>
</div>
