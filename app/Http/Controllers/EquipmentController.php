<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    /**
     * Tampilkan data kelola alat (Admin)
     */
    public function index(Request $request)
    {
        $query = Equipment::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $equipments = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.equipments.index', compact('equipments', 'categories'));
    }

    /**
     * Tambah data alat baru (Soal No. 2 & No. 4)
     */
    public function store(Request $request)
    {
        // Validasi: Stok tidak boleh minus (min:0)
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:equipments,code',
            'stock' => 'required|integer|min:0', // Validasi stok tidak boleh minus
            'location' => 'nullable|string|max:255',
            'condition' => 'required|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'category_id.required' => 'Kategori alat wajib dipilih.',
            'name.required' => 'Nama alat wajib diisi.',
            'code.required' => 'Kode seri alat wajib diisi.',
            'code.unique' => 'Kode seri alat sudah terdaftar.',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Validasi gagal: Stok tidak boleh minus (minimal 0).',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('equipments', 'public');
        }

        Equipment::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'stock' => $request->stock,
            'location' => $request->location,
            'condition' => $request->condition,
            'description' => $request->description,
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.equipments.index')->with('success', 'Data alat baru berhasil ditambahkan.');
    }

    /**
     * Ubah data alat (Soal No. 2 & No. 4)
     */
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:equipments,code,' . $equipment->id,
            'stock' => 'required|integer|min:0', // Validasi: Stok tidak boleh minus
            'location' => 'nullable|string|max:255',
            'condition' => 'required|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'stock.min' => 'Validasi gagal: Stok tidak boleh minus (minimal 0).',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'stock' => $request->stock,
            'location' => $request->location,
            'condition' => $request->condition,
            'description' => $request->description,
        ];

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($equipment->photo && Storage::disk('public')->exists($equipment->photo)) {
                Storage::disk('public')->delete($equipment->photo);
            }
            $data['photo'] = $request->file('photo')->store('equipments', 'public');
        }

        $equipment->update($data);

        return redirect()->route('admin.equipments.index')->with('success', 'Data alat berhasil diperbarui.');
    }

    /**
     * Hapus data alat (Soal No. 2)
     */
    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);

        if ($equipment->photo && Storage::disk('public')->exists($equipment->photo)) {
            Storage::disk('public')->delete($equipment->photo);
        }

        $equipment->delete();

        return redirect()->route('admin.equipments.index')->with('success', 'Data alat berhasil dihapus dari sistem.');
    }
}

