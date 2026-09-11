<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest:admin')->group(function () {
    Route::livewire('/admin/login', 'pages::admin.login')->name('admin.login');
});

Route::middleware('auth:admin')->group(function () {
    Route::livewire('/admin/dashboard', 'pages::admin.dashboard')->name('admin.dashboard');

    Route::livewire('/admin/profile', 'pages::admin.profile')->name('admin.profile');

    Route::middleware('permission:manage admins,admin')->group(function () {
        Route::livewire('/admin/admins', 'pages::admin.admins.index')->name('admin.admins.index');
    });

    Route::middleware('permission:manage roles,admin')->group(function () {
        Route::livewire('/admin/roles', 'pages::admin.roles.index')->name('admin.roles.index');
    });

    Route::middleware('permission:manage properties,admin')->group(function () {
        Route::livewire('/admin/properties', 'pages::admin.properties.index')->name('admin.properties.index');
        Route::livewire('/admin/properties/create', 'pages::admin.properties.form')->name('admin.properties.create');
        Route::livewire('/admin/properties/{property}/edit', 'pages::admin.properties.form')->name('admin.properties.edit');

        Route::livewire('/admin/categories', 'pages::admin.categories.index')->name('admin.categories.index');
        Route::livewire('/admin/cities', 'pages::admin.cities.index')->name('admin.cities.index');
        Route::livewire('/admin/property-types', 'pages::admin.property-types.index')->name('admin.property-types.index');
        Route::livewire('/admin/amenities', 'pages::admin.amenities.index')->name('admin.amenities.index');
        Route::livewire('/admin/highlights', 'pages::admin.highlights.index')->name('admin.highlights.index');
    });

    Route::middleware('permission:manage users,admin')->group(function () {
        Route::livewire('/admin/users', 'pages::admin.users.index')->name('admin.users.index');
    });

    Route::post('/admin/logout', function (Request $request) {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    })->name('admin.logout');
});
