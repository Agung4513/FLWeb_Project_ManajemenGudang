<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Index bisa dilihat Admin & Manager
    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) abort(403);

        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) abort(403);
        return view('categories.create');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) abort(403);

        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500'
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) abort(403);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) abort(403);

        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500'
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) abort(403);

        // Cegah hapus kategori yang dipakai
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Kategori ini masih memiliki produk.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori dihapus.');
    }
}
