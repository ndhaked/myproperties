<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\PropertyDocument;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function images(Request $request)
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'max:5120'],
        ]);

        $userId = $request->user()->id;
        $urls = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store("uploads/{$userId}/images", 'public');

            PropertyImage::create([
                'owner_id' => $userId,
                'path' => $path,
                'sort_order' => 0,
            ]);

            $urls[] = Storage::url($path);
        }

        return ApiResponse::success(['urls' => $urls]);
    }

    public function documents(Request $request)
    {
        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ]);

        $userId = $request->user()->id;
        $file = $request->file('document');
        $path = $file->store("uploads/{$userId}/documents", 'public');

        $document = PropertyDocument::create([
            'owner_id' => $userId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $file->getClientOriginalExtension(),
            'size_mb' => round($file->getSize() / 1048576, 2),
        ]);

        return ApiResponse::success([
            'id' => 'doc_' . $document->id,
            'name' => $document->name,
            'sizeMb' => (float) $document->size_mb,
            'url' => Storage::url($document->path),
        ]);
    }
}
