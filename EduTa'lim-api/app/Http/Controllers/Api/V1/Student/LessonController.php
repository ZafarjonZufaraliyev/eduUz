<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        abort_if($lesson->course_id !== $course->id, 404, 'Lesson not found.');

        // Optionally resolve authenticated user (no middleware needed)
        $user = Auth::guard('sanctum')->user();

        $hasAccess = $lesson->is_free
            || ($user && (
                $user->isAdmin()
                || $user->isTeacher()
                || Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists()
            ));

        if (! $hasAccess) {
            return response()->json([
                'success' => false,
                'message' => "Bu darsni ko'rish uchun kursga yoziling.",
                'locked'  => true,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => $lesson,
        ]);
    }
}
