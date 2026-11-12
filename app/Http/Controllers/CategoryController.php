<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Tampilkan data kategori
     */
    public function index()
    {
        $categories = Category::withCount('equipments')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Tambah kategori baru (Soal No. 2)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah ada.',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Ubah kategori (Soal No. 2)
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori (Soal No. 2)
     */
    public function destroy($id)
    {
        $category = Category::withCount('equipments')->findOrFail($id);

        if ($category->equipments_count > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $category->equipments_count . ' alat laboratorium.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}

