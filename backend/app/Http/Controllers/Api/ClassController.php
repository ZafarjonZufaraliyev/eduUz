<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::with('category');
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
        ]);
        return response()->json(SchoolClass::create($data)->load('category'), 201);
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate(['name' => 'sometimes|string|max:100']);
        $class->update($data);
        return response()->json($class->load('category'));
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();
        return response()->json(null, 204);
    }
}
