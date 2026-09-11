<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return ApiResponse::success($this->userPayload($request));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'fullName' => ['nullable', 'string', 'max:255'],
            'notificationsEnabled' => ['nullable', 'boolean'],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $data['fullName'] ?? $user->name,
            'notifications_enabled' => array_key_exists('notificationsEnabled', $data) ? $data['notificationsEnabled'] : $user->notifications_enabled,
            'email' => $data['email'] ?? $user->email,
        ]);

        return ApiResponse::success($this->userPayload($request));
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return ApiResponse::success(['avatar' => Storage::url($path)]);
    }

    public function savedProperties(Request $request)
    {
        $properties = $request->user()->savedProperties()
            ->with(['category', 'propertyType', 'city', 'images', 'amenities', 'highlights', 'nearbyPlaces', 'documents', 'owner'])
            ->paginate((int) $request->input('limit', 10));

        return ApiResponse::success(ApiResponse::paginated($properties, fn ($property) => new PropertyResource($property)));
    }

    protected function userPayload(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => 'user_' . $user->id,
            'fullName' => $user->name,
            'phone' => $user->phone ? $user->country_code . $user->phone : null,
            'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
            'role' => $user->role,
            'isVerified' => (bool) $user->is_verified,
            'notificationsEnabled' => (bool) $user->notifications_enabled,
            'stats' => [
                'savedProperties' => $user->savedProperties()->count(),
                'bookedVisits' => $user->visits()->count(),
                'myListings' => $user->listings()->count(),
            ],
        ];
    }
}
