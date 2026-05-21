<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();

        $already = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($already) {
            return response()->json([
                'success' => false,
                'message' => 'Already enrolled.',
            ], 409);
        }

        $enrollment = Enrollment::create([
            'user_id'     => $user->id,
            'course_id'   => $course->id,
            'enrolled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $enrollment,
            'message' => 'Enrolled successfully.',
        ], 201);
    }

    public function unenroll(Request $request, Course $course): JsonResponse
    {
        $deleted = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Unenrolled successfully.',
        ]);
    }

    public function myCourses(Request $request): JsonResponse
    {
        $courses = $request->user()
            ->enrolledCourses()
            ->with(['category', 'teacher'])
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $courses,
        ]);
    }
}
