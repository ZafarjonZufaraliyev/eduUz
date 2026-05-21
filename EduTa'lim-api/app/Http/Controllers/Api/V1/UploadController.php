<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function thumbnail(Request $request, Course $course): JsonResponse
    {
        $request->validate([
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $path = $request->file('thumbnail')->store('thumbnails', 'public');

        $course->update(['thumbnail' => $path]);

        return response()->json([
            'success' => true,
            'data'    => [
                'path' => $path,
                'url'  => Storage::disk('public')->url($path),
            ],
            'message' => 'Thumbnail uploaded.',
        ]);
    }

    public function avatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ]);

        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        return response()->json([
            'success' => true,
            'data'    => [
                'path' => $path,
                'url'  => Storage::disk('public')->url($path),
            ],
            'message' => 'Avatar uploaded.',
        ]);
    }
}
