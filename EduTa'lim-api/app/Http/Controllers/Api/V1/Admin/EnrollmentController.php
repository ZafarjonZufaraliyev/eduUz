<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;

class EnrollmentController extends Controller
{
    public function index(): JsonResponse
    {
        $enrollments = Enrollment::with(['user:id,name,email', 'course:id,title,category_id'])
            ->orderByDesc('enrolled_at')
            ->get();

        return response()->json(['success' => true, 'data' => $enrollments]);
    }
}
