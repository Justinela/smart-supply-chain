<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'code' => 'required|string|max:50|unique:categories,code',
            'description' => 'nullable|string|max:500',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
        ]);

        AuditLoggerService::log('CREATE_CATEGORY', 'ProductCatalog', null, $category->toArray());

        return redirect()->route('categories.index')->with('success', "Category '{$category->name}' created successfully!");
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'code' => ['required', 'string', 'max:50', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string|max:500',
        ]);

        $old = $category->toArray();

        $category->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
        ]);

        AuditLoggerService::log('UPDATE_CATEGORY', 'ProductCatalog', $old, $category->toArray());

        return redirect()->route('categories.index')->with('success', "Category '{$category->name}' updated successfully!");
    }
}
