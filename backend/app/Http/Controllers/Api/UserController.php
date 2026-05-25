<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['category', 'schoolClass']);

        if ($request->role) $query->where('role', $request->role);
        if ($request->category_slug) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category_slug));
        }
        if ($request->class_id) $query->where('class_id', $request->class_id);

        $users = $query->orderBy('full_name')->paginate(50);
        return response()->json(['data' => $users->items(), 'total' => $users->total()]);
    }

    public function show(User $user)
    {
        return response()->json($user->load(['category', 'schoolClass']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'    => 'required|string|max:255',
            'username'     => 'required|string|unique:users',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:admin,guard,member',
            'category_id'  => 'nullable|exists:categories,id',
            'class_id'     => 'nullable|exists:classes,id',
            'phone'        => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->generateQrCodes();

        return response()->json($user->load(['category', 'schoolClass']), 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'full_name'    => 'sometimes|string|max:255',
            'username'     => ['sometimes', 'string', Rule::unique('users')->ignore($user->id)],
            'role'         => 'sometimes|in:admin,guard,member',
            'category_id'  => 'nullable|exists:categories,id',
            'class_id'     => 'nullable|exists:classes,id',
            'phone'        => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'is_active'    => 'sometimes|boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return response()->json($user->load(['category', 'schoolClass']));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    public function qr(User $user)
    {
        return response()->json([
            'qr_checkin'  => $user->qr_checkin,
            'qr_checkout' => $user->qr_checkout,
        ]);
    }

    public function regenerateQr(User $user)
    {
        $user->generateQrCodes();
        return response()->json([
            'qr_checkin'  => $user->qr_checkin,
            'qr_checkout' => $user->qr_checkout,
        ]);
    }
}
