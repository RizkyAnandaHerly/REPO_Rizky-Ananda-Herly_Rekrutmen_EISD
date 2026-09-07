<?php

namespace App\Http\Controllers;

use App\Models\WasteCategory;
use Illuminate\Http\Request;

class WasteCategoryController extends Controller
{
    /**
     * Display a listing of waste categories.
     */
    public function index()
    {
        $categories = WasteCategory::latest()->get();

        return view('admin.waste-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.waste-categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'points_per_unit' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        WasteCategory::create($validated);

        return redirect('/admin/waste-categories')->with('success', 'Kategori sampah berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(WasteCategory $category)
    {
        return view('admin.waste-categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, WasteCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'points_per_unit' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect('/admin/waste-categories')->with('success', 'Kategori sampah berhasil diperbarui.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(WasteCategory $category)
    {
        $category->delete();

        return redirect('/admin/waste-categories')->with('success', 'Kategori sampah berhasil dihapus.');
    }
}
