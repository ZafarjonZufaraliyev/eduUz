<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'slug'  => 'required|string|unique:categories|max:50',
            'color' => 'nullable|string|max:20',
        ]);
        return response()->json(Category::create($data), 201);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'  => 'sometimes|string|max:100',
            'color' => 'nullable|string|max:20',
        ]);
        $category->update($data);
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
