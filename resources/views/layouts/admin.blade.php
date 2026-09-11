<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Admin' }} - {{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">

            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="h-16 flex items-center gap-2.5 px-6 border-b border-gray-200 shrink-0">
                    <span class="h-9 w-9 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(config('app.name'), 0, 1)) }}
                    </span>
                    <span class="text-base font-semibold tracking-tight text-gray-900 leading-tight">{{ config('app.name') }}</span>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 text-sm">
                    <x-panel.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        <x-slot name="icon"><x-icon name="home" class="w-5 h-5" /></x-slot>
                        Dashboard
                    </x-panel.nav-link>

                    @if (auth('admin')->user()->can('manage properties'))
                        <p class="px-3 pt-5 pb-1.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Property Management</p>

                        <x-panel.nav-link :href="route('admin.properties.index')" :active="request()->routeIs('admin.properties.*')">
                            <x-slot name="icon"><x-icon name="building" class="w-5 h-5" /></x-slot>
                            Properties
                        </x-panel.nav-link>

                        <x-panel.nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                            <x-slot name="icon"><x-icon name="tag" class="w-5 h-5" /></x-slot>
                            Categories
                        </x-panel.nav-link>

                        <x-panel.nav-link :href="route('admin.cities.index')" :active="request()->routeIs('admin.cities.*')">
                            <x-slot name="icon"><x-icon name="map-pin" class="w-5 h-5" /></x-slot>
                            Cities
                        </x-panel.nav-link>

                        <x-panel.nav-link :href="route('admin.property-types.index')" :active="request()->routeIs('admin.property-types.*')">
                            <x-slot name="icon"><x-icon name="squares-plus" class="w-5 h-5" /></x-slot>
                            Property Types
                        </x-panel.nav-link>

                        <x-panel.nav-link :href="route('admin.amenities.index')" :active="request()->routeIs('admin.amenities.*')">
                            <x-slot name="icon"><x-icon name="sparkles" class="w-5 h-5" /></x-slot>
                            Amenities
                        </x-panel.nav-link>

                        <x-panel.nav-link :href="route('admin.highlights.index')" :active="request()->routeIs('admin.highlights.*')">
                            <x-slot name="icon"><x-icon name="list-bullet" class="w-5 h-5" /></x-slot>
                            Highlights
                        </x-panel.nav-link>
                    @endif

                    @if (auth('admin')->user()->can('manage users'))
                        <p class="px-3 pt-5 pb-1.5 text-xs font-semibold uppercase tracking-wider text-gray-400">App Users</p>

                        <x-panel.nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            <x-slot name="icon"><x-icon name="users" class="w-5 h-5" /></x-slot>
                            Buyers &amp; Sellers
                        </x-panel.nav-link>
                    @endif

                    @if (auth('admin')->user()->canAny(['manage admins', 'manage roles']))
                        <p class="px-3 pt-5 pb-1.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Admin Management</p>

                        @if (auth('admin')->user()->can('manage admins'))
                            <x-panel.nav-link :href="route('admin.admins.index')" :active="request()->routeIs('admin.admins.*')">
                                <x-slot name="icon"><x-icon name="user-group" class="w-5 h-5" /></x-slot>
                                Admins
                            </x-panel.nav-link>
                        @endif

                        @if (auth('admin')->user()->can('manage roles'))
                            <x-panel.nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')">
                                <x-slot name="icon"><x-icon name="cog" class="w-5 h-5" /></x-slot>
                                Roles &amp; Permissions
                            </x-panel.nav-link>
                        @endif
                    @endif
                </nav>
            </aside>

            <!-- Overlay for mobile -->
            <div
                x-show="sidebarOpen"
                x-cloak
                @click="sidebarOpen = false"
                class="fixed inset-0 z-20 bg-black/30 lg:hidden"
            ></div>

            <!-- Main column -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Topbar -->
                <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-10">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <x-icon name="menu" class="h-6 w-6" />
                        </button>

                        @isset($header)
                            {{ $header }}
                        @endisset
                    </div>

                    <div class="flex items-center gap-3" x-data="{ open: false }">
                        <span class="hidden sm:inline-flex text-xs font-medium px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700">
                            {{ auth('admin')->user()->roles->pluck('name')->join(', ') ?: 'Admin' }}
                        </span>

                        <div class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-gray-50">
                                <span class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ auth('admin')->user()->name }}</span>
                                <x-icon name="chevron-down" class="h-4 w-4 text-gray-400" />
                            </button>

                            <div
                                x-show="open"
                                x-cloak
                                @click.outside="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 ring-1 ring-black/5"
                            >
                                <a href="{{ route('admin.profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        Log out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
