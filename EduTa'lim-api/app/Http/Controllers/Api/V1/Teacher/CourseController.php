<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Enums\CourseLevel;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
        $courses = Course::with(['category', 'teacher'])->get();

        return response()->json([
            'success' => true,
            'data'    => $courses,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['sometimes', 'string', 'unique:courses,slug'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'level'       => ['required', new Enum(CourseLevel::class)],
            'thumbnail'   => ['nullable', 'string', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $validated['teacher_id'] = $request->user()->id;

        $course = Course::create($validated);
        $course->load(['category', 'teacher']);

        return response()->json([
            'success' => true,
            'data'    => $course,
            'message' => 'Course created.',
        ], 201);
    }

    public function show(Request $request, Course $course): JsonResponse
    {
        $course->load(['category', 'teacher', 'lessons']);

        return response()->json([
            'success' => true,
            'data'    => $course,
        ]);
    }

    public function update(Request $request, Course $course): JsonResponse
    {
        $this->authorizeTeacher($request, $course);

        $validated = $request->validate([
            'title'       => ['sometimes', 'string', 'max:255'],
            'slug'        => ['sometimes', 'string', 'unique:courses,slug,' . $course->id],
            'description' => ['nullable', 'string'],
            'price'       => ['sometimes', 'numeric', 'min:0'],
            'level'       => ['sometimes', new Enum(CourseLevel::class)],
            'thumbnail'   => ['nullable', 'string', 'max:500'],
            'category_id' => ['sometimes', 'exists:categories,id'],
        ]);

        if (isset($validated['title']) && !isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $course->update($validated);
        $course->load(['category', 'teacher']);

        return response()->json([
            'success' => true,
            'data'    => $course,
            'message' => 'Course updated.',
        ]);
    }

    public function destroy(Request $request, Course $course): JsonResponse
    {
        $this->authorizeTeacher($request, $course);

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted.',
        ]);
    }

    private function authorizeTeacher(Request $request, Course $course): void
    {
        if ($request->user()->isAdmin()) {
            return;
        }

        abort_if($course->teacher_id !== $request->user()->id, 403, 'Forbidden.');
    }
}
