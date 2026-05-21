<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function statistics(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'total_users'       => User::count(),
                'total_teachers'    => User::where('role', Role::Teacher)->count(),
                'total_students'    => User::where('role', Role::Student)->count(),
                'total_courses'     => Course::count(),
                'total_enrollments' => Enrollment::count(),
            ],
        ]);
    }
}
