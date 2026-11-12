<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Halaman Katalog Alat Laboratorium (Siswa / Publik)
     */
    public function catalog(Request $request)
    {
        $query = Equipment::with('category');

        // Pencarian nama alat / kode seri
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter Status Stok
        if ($request->filled('status_stok')) {
            if ($request->status_stok === 'ready') {
                $query->where('stock', '>', 0);
            } elseif ($request->status_stok === 'empty') {
                $query->where('stock', '<=', 0);
            }
        }

        $equipments = $query->latest()->paginate(9)->withQueryString();
        $categories = Category::withCount('equipments')->get();

        return view('student.catalog', compact('equipments', 'categories'));
    }

    /**
     * Formulir Pengajuan Peminjaman Alat
     */
    public function showBorrowForm(Request $request, $equipmentId = null)
    {
        $selectedEquipment = null;
        if ($equipmentId) {
            $selectedEquipment = Equipment::findOrFail($equipmentId);
        }

        $equipments = Equipment::where('stock', '>', 0)->get();

        return view('student.borrow-form', compact('selectedEquipment', 'equipments'));
    }

    /**
     * Simpan Pengajuan Peminjaman
     */
    public function submitBorrow(Request $request)
    {
        // Validasi Soal No. 4:
        // - Stok tidak boleh minus (jumlah tidak boleh melebihi stok yang tersedia dan minimal 1)
        // - Tanggal kembali tidak boleh sebelum tanggal pinjam
        $equipment = Equipment::findOrFail($request->equipment_id);

        $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'amount' => 'required|integer|min:1|max:' . max(1, $equipment->stock),
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'purpose' => 'required|string|max:1000',
            'supervisor' => 'nullable|string|max:255',
        ], [
            'equipment_id.required' => 'Pilih alat laboratorium yang ingin dipinjam.',
            'amount.required' => 'Jumlah unit alat wajib diisi.',
            'amount.min' => 'Jumlah pinjam minimal 1 unit (stok tidak boleh minus).',
            'amount.max' => 'Jumlah pinjam melebihi stok yang tersedia (' . $equipment->stock . ' unit).',
            'borrow_date.required' => 'Tanggal pinjam wajib diisi.',
            'return_date.required' => 'Tanggal pengembalian wajib diisi.',
            'return_date.after_or_equal' => 'Validasi gagal: Tanggal kembali tidak boleh sebelum tanggal pinjam.',
            'purpose.required' => 'Tujuan praktikum wajib diisi.',
        ]);

        if ($equipment->stock < $request->amount) {
            return back()->withInput()->withErrors([
                'amount' => 'Stok alat tidak mencukupi untuk jumlah yang diminta.',
            ]);
        }

        // Generate Kode Peminjaman Unik (PMJ-Tahun-XXXX)
        $latestBorrow = Borrowing::latest('id')->first();
        $nextNumber = $latestBorrow ? ($latestBorrow->id + 1) : 1;
        $borrowCode = 'PMJ-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Borrowing::create([
            'borrow_code' => $borrowCode,
            'user_id' => Auth::id(),
            'equipment_id' => $request->equipment_id,
            'amount' => $request->amount,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'purpose' => $request->purpose,
            'supervisor' => $request->supervisor,
            'status' => 'menunggu',
        ]);

        return redirect()->route('student.history')->with('success', 'Pengajuan peminjaman berhasil dikirim! Menunggu persetujuan laboran.');
    }

    /**
     * Riwayat Peminjaman Siswa
     */
    public function history(Request $request)
    {
        $userId = Auth::id();
        $query = Borrowing::with('equipment.category')->where('user_id', $userId);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        // Hitung statistik status untuk siswa ini
        $stats = [
            'total' => Borrowing::where('user_id', $userId)->count(),
            'menunggu' => Borrowing::where('user_id', $userId)->where('status', 'menunggu')->count(),
            'disetujui' => Borrowing::where('user_id', $userId)->where('status', 'disetujui')->count(),
            'selesai' => Borrowing::where('user_id', $userId)->where('status', 'selesai')->count(),
            'ditolak' => Borrowing::where('user_id', $userId)->where('status', 'ditolak')->count(),
        ];

        return view('student.history', compact('borrowings', 'stats'));
    }

    /**
     * Laporan Kerusakan Barang yang Sedang Dipinjam
     */
    public function reportDamage(Request $request, $id)
    {
        $borrowing = Borrowing::with('equipment')->where('user_id', Auth::id())->findOrFail($id);

        if ($borrowing->status !== 'disetujui') {
            return back()->with('error', 'Laporan kerusakan hanya dapat diajukan untuk peminjaman yang berstatus Disetujui/Sedang Dipinjam.');
        }

        $request->validate([
            'damage_description' => 'required|string|min:5|max:1000',
            'damage_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'damage_description.required' => 'Deskripsi kronologi kerusakan wajib diisi.',
            'damage_description.min' => 'Deskripsi kerusakan minimal 5 karakter.',
            'damage_photo.image' => 'File bukti harus berupa gambar.',
            'damage_photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $photoPath = $borrowing->damage_photo;
        if ($request->hasFile('damage_photo')) {
            $photoPath = $request->file('damage_photo')->store('damage_reports', 'public');
        }

        $borrowing->update([
            'is_damaged' => true,
            'damage_description' => $request->damage_description,
            'damage_photo' => $photoPath,
            'damage_reported_at' => now(),
        ]);

        // Perbarui kondisi alat menjadi Perlu Perbaikan
        if ($borrowing->equipment) {
            $borrowing->equipment->update([
                'condition' => 'Perlu Perbaikan',
            ]);
        }

        return back()->with('success', 'Laporan kerusakan barang #' . $borrowing->borrow_code . ' berhasil dikirim ke pihak laboran.');
    }
}

