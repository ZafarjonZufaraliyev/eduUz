<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => Category::all(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:categories,name'],
            'slug'  => ['sometimes', 'string', 'unique:categories,slug'],
            'image' => ['nullable', 'string', 'max:500'],
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'data'    => $category,
            'message' => 'Category created.',
        ], 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $category,
        ]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name'  => ['sometimes', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'slug'  => ['sometimes', 'string', 'unique:categories,slug,' . $category->id],
            'image' => ['nullable', 'string', 'max:500'],
        ]);

        if (isset($validated['name']) && !isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return response()->json([
            'success' => true,
            'data'    => $category,
            'message' => 'Category updated.',
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted.',
        ]);
    }
}
