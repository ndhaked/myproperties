<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use App\Models\User;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $role = '';

    public ?int $editingId = null;
    public bool $showModal = false;

    public string $name = '';
    public string $editRole = 'Buyer';
    public bool $isVerified = false;

    public function updating($property): void
    {
        if (in_array($property, ['search', 'role'])) {
            $this->resetPage();
        }
    }

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->editRole = $user->role ?? 'Buyer';
        $this->isVerified = (bool) $user->is_verified;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'editRole' => ['required', 'in:Buyer,Seller'],
            'isVerified' => ['boolean'],
        ]);

        User::findOrFail($this->editingId)->update([
            'name' => $data['name'],
            'role' => $data['editRole'],
            'is_verified' => $data['isVerified'],
        ]);

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        User::findOrFail($id)->delete();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function with(): array
    {
        $users = User::query()
            ->whereNotNull('role')
            ->withCount(['listings', 'savedProperties', 'visits'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->when($this->role, fn ($q) => $q->where('role', $this->role))
            ->latest()
            ->paginate(10);

        return ['users' => $users];
    }
}
?>

<div>
    <x-slot:header>
        <h2 class="text-lg font-semibold text-gray-900">Users</h2>
    </x-slot:header>

    <div class="flex flex-wrap items-center gap-2 mb-4">
        <input wire:model.live.debounce.400ms="search" type="text" placeholder="Search by name or phone..."
            class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

        <select wire:model.live="role" class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">All Roles</option>
            <option value="Buyer">Buyer</option>
            <option value="Seller">Seller</option>
        </select>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Phone</th>
                    <th class="px-4 py-3 font-medium">Role</th>
                    <th class="px-4 py-3 font-medium">Verified</th>
                    <th class="px-4 py-3 font-medium">Listings</th>
                    <th class="px-4 py-3 font-medium">Saved</th>
                    <th class="px-4 py-3 font-medium">Visits</th>
                    <th class="px-4 py-3 font-medium">Joined</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->phone ? $user->country_code . $user->phone : '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex text-xs font-medium px-2 py-0.5 rounded-full
                                @class([
                                    'bg-sky-50 text-sky-700' => $user->role === 'Buyer',
                                    'bg-purple-50 text-purple-700' => $user->role === 'Seller',
                                ])"
                            >{{ $user->role }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($user->is_verified)
                                <span class="inline-flex text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">Verified</span>
                            @else
                                <span class="text-gray-400 text-xs">Unverified</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->listings_count }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->saved_properties_count }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->visits_count }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</button>
                            <button
                                wire:click="delete({{ $user->id }})"
                                wire:confirm="Are you sure you want to delete this user?"
                                class="text-red-600 hover:text-red-800 font-medium"
                            >Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-400">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/30 px-4" wire:key="user-modal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit User</h3>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select wire:model="editRole" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="Buyer">Buyer</option>
                            <option value="Seller">Seller</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input wire:model="isVerified" type="checkbox" id="isVerified" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label for="isVerified" class="text-sm text-gray-700">Verified</label>
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
