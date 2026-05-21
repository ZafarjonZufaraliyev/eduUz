<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Course $course): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $course->lessons,
        ]);
    }

    public function store(Request $request, Course $course): JsonResponse
    {
        $this->authorizeCourse($request, $course);

        $validated = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'content'   => ['nullable', 'string'],
            'order'     => ['sometimes', 'integer', 'min:0'],
        ]);

        $validated['course_id'] = $course->id;

        $lesson = Lesson::create($validated);

        return response()->json([
            'success' => true,
            'data'    => $lesson,
            'message' => 'Lesson created.',
        ], 201);
    }

    public function show(Course $course, Lesson $lesson): JsonResponse
    {
        $this->ensureBelongsToCourse($course, $lesson);

        return response()->json([
            'success' => true,
            'data'    => $lesson,
        ]);
    }

    public function update(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        $this->authorizeCourse($request, $course);
        $this->ensureBelongsToCourse($course, $lesson);

        $validated = $request->validate([
            'title'     => ['sometimes', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'content'   => ['nullable', 'string'],
            'order'     => ['sometimes', 'integer', 'min:0'],
        ]);

        $lesson->update($validated);

        return response()->json([
            'success' => true,
            'data'    => $lesson,
            'message' => 'Lesson updated.',
        ]);
    }

    public function destroy(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        $this->authorizeCourse($request, $course);
        $this->ensureBelongsToCourse($course, $lesson);

        $lesson->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lesson deleted.',
        ]);
    }

    private function authorizeCourse(Request $request, Course $course): void
    {
        if ($request->user()->isAdmin()) {
            return;
        }

        abort_if($course->teacher_id !== $request->user()->id, 403, 'Forbidden.');
    }

    private function ensureBelongsToCourse(Course $course, Lesson $lesson): void
    {
        abort_if($lesson->course_id !== $course->id, 404, 'Lesson not found.');
    }
}
