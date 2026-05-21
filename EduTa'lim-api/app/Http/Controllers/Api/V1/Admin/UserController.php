<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role', 'avatar', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $users]);
    }
}
