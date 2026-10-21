<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    private const MAX_VIDEO_KB = 102400;

    public function store(Request $request): JsonResponse
    {
        $request->validate(['file' => ['required', 'file']]);

        $isVideo = str_starts_with((string) $request->file('file')->getMimeType(), 'video/');

        // Videos are only embedded in post content; the server's PHP/nginx upload limits
        // (upload_max_filesize, post_max_size, client_max_body_size) must allow this size.
        $request->validate([
            'file' => $isVideo
                ? ['mimetypes:video/mp4,video/webm,video/quicktime', 'max:'.self::MAX_VIDEO_KB]
                : ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        $path = $request->file('file')->store('uploads/'.date('Y/m'), 'public');

        return response()->json([
            'data' => [
                'url' => Storage::disk('public')->url($path),
                'type' => $isVideo ? 'video' : 'image',
            ],
        ], 201);
    }
}
