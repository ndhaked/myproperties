<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\AppNotification;
use App\Models\Property;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VisitController extends Controller
{
    public function store(Request $request, Property $property)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required', 'string'],
            'visitorName' => ['nullable', 'string', 'max:255'],
            'visitorPhone' => ['nullable', 'string', 'max:20'],
        ]);

        $visit = Visit::create([
            'property_id' => $property->id,
            'buyer_id' => $request->user()->id,
            'date' => $data['date'],
            'time' => $data['time'],
            'visitor_name' => $data['visitorName'] ?? $request->user()->name,
            'visitor_phone' => $data['visitorPhone'] ?? null,
            'status' => 'Upcoming',
        ]);

        if ($property->owner_id) {
            AppNotification::create([
                'user_id' => $property->owner_id,
                'title' => 'New site visit booked',
                'body' => "A visit was booked for \"{$property->title}\" on {$visit->date->format('Y-m-d')}.",
            ]);
        }

        return ApiResponse::success($this->visitPayload($visit));
    }

    public function index(Request $request)
    {
        $data = $request->validate([
            'role' => ['nullable', 'in:buyer,seller'],
            'status' => ['nullable', Rule::in(Visit::STATUS_OPTIONS)],
        ]);

        $user = $request->user();
        $role = $data['role'] ?? ($user->role === 'Seller' ? 'seller' : 'buyer');

        $query = Visit::query()->with(['property', 'buyer']);

        $query = $role === 'seller'
            ? $query->whereHas('property', fn ($q) => $q->where('owner_id', $user->id))
            : $query->where('buyer_id', $user->id);

        $visits = $query->when($data['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate((int) $request->input('limit', 10));

        return ApiResponse::success(ApiResponse::paginated($visits, fn ($visit) => $this->visitPayload($visit)));
    }

    public function update(Request $request, Visit $visit)
    {
        $user = $request->user();
        $isBuyer = $visit->buyer_id === $user->id;
        $isSeller = $visit->property->owner_id === $user->id;

        if (! $isBuyer && ! $isSeller) {
            return ApiResponse::error('FORBIDDEN', 'You cannot manage this visit.', 403);
        }

        $data = $request->validate([
            'status' => ['nullable', Rule::in(Visit::STATUS_OPTIONS)],
            'date' => ['nullable', 'date'],
            'time' => ['nullable', 'string'],
        ]);

        $visit->update(array_filter($data, fn ($value) => $value !== null));

        if ($isSeller && isset($data['status'])) {
            AppNotification::create([
                'user_id' => $visit->buyer_id,
                'title' => 'Your site visit status changed',
                'body' => "Your visit for \"{$visit->property->title}\" is now {$visit->status}.",
            ]);
        }

        return ApiResponse::success($this->visitPayload($visit->fresh(['property', 'buyer'])));
    }

    protected function visitPayload(Visit $visit): array
    {
        return [
            'id' => 'visit_' . $visit->id,
            'property' => [
                'id' => 'prop_' . $visit->property->id,
                'title' => $visit->property->title,
                'image' => optional($visit->property->images->first())->path,
            ],
            'visitor' => [
                'id' => 'user_' . $visit->buyer->id,
                'name' => $visit->visitor_name ?? $visit->buyer->name,
                'phone' => $visit->visitor_phone ?? ($visit->buyer->phone ? $visit->buyer->country_code . $visit->buyer->phone : null),
                'avatar' => $visit->buyer->avatar,
            ],
            'date' => $visit->date->format('Y-m-d'),
            'time' => $visit->time,
            'status' => $visit->status,
        ];
    }
}
