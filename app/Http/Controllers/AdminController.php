<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Dashboard Administrator
     */
    public function dashboard()
    {
        $stats = [
            'total_equipments' => Equipment::count(),
            'total_stock' => Equipment::sum('stock'),
            'active_borrowings' => Borrowing::where('status', 'disetujui')->count(),
            'pending_borrowings' => Borrowing::where('status', 'menunggu')->count(),
            'total_students' => User::where('role', 'siswa')->count(),
        ];

        // Pengajuan yang butuh tindakan persetujuan / penolakan
        $pendingApprovals = Borrowing::with(['user', 'equipment.category'])
            ->where('status', 'menunggu')
            ->latest()
            ->take(8)
            ->get();

        // Peminjaman yang sedang berlangsung (aktif)
        $activeBorrowingsList = Borrowing::with(['user', 'equipment'])
            ->where('status', 'disetujui')
            ->latest()
            ->take(5)
            ->get();

        // Distribusi Kategori
        $categoryDistribution = Category::withCount('equipments')->get();

        return view('admin.dashboard', compact('stats', 'pendingApprovals', 'activeBorrowingsList', 'categoryDistribution'));
    }

    /**
     * Setujui Peminjaman (Soal No. 2: Admin setujui peminjaman)
     */
    public function approveBorrowing($id)
    {
        $borrowing = Borrowing::with('equipment')->findOrFail($id);

        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Status peminjaman ini sudah diproses sebelumnya.');
        }

        $equipment = $borrowing->equipment;

        // Validasi: Stok tidak boleh minus
        if ($equipment->stock < $borrowing->amount) {
            return back()->with('error', 'Persetujuan gagal! Stok alat saat ini (' . $equipment->stock . ' unit) tidak mencukupi untuk jumlah yang diajukan (' . $borrowing->amount . ' unit).');
        }

        DB::transaction(function () use ($borrowing, $equipment) {
            // Kurangi stok alat secara terstruktur
            $equipment->decrement('stock', $borrowing->amount);

            $borrowing->update([
                'status' => 'disetujui',
                'approved_at' => now(),
            ]);
        });

        return back()->with('success', 'Peminjaman #' . $borrowing->borrow_code . ' berhasil disetujui. Stok alat otomatis berkurang ' . $borrowing->amount . ' unit.');
    }

    /**
     * Tolak Peminjaman (Soal No. 2: Admin tolak peminjaman)
     */
    public function rejectBorrowing(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Status peminjaman ini sudah diproses sebelumnya.');
        }

        $borrowing->update([
            'status' => 'ditolak',
            'rejection_reason' => $request->rejection_reason ?? 'Pengajuan ditolak oleh laboran.',
        ]);

        return back()->with('success', 'Peminjaman #' . $borrowing->borrow_code . ' telah ditolak.');
    }

    /**
     * Konfirmasi Pengembalian Alat (Selesai -> Stok alat bertambah kembali)
     */
    public function returnBorrowing(Request $request, $id)
    {
        $borrowing = Borrowing::with('equipment')->findOrFail($id);

        if ($borrowing->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman berstatus Disetujui yang dapat diselesaikan.');
        }

        DB::transaction(function () use ($borrowing, $request) {
            // Kembalikan stok alat
            $borrowing->equipment->increment('stock', $borrowing->amount);

            $borrowing->update([
                'status' => 'selesai',
                'returned_at' => now(),
                'notes' => $request->notes ?? 'Alat dikembalikan dalam kondisi baik.',
            ]);
        });

        return back()->with('success', 'Alat berhasil dikembalikan! Stok alat ' . $borrowing->equipment->name . ' otomatis bertambah ' . $borrowing->amount . ' unit.');
    }

    /**
     * Daftar Peminjaman Seluruhnya
     */
    public function borrowings(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment.category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('borrow_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")->orWhere('nisn', 'like', "%{$search}%");
                  })
                  ->orWhereHas('equipment', function ($qe) use ($search) {
                      $qe->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $borrowings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.borrowings', compact('borrowings'));
    }

    /**
     * Daftar Siswa Terdaftar (Soal No. 2: Admin daftar siswa)
     */
    public function students(Request $request)
    {
        $query = User::where('role', 'siswa')->withCount('borrowings');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(12)->withQueryString();

        return view('admin.students', compact('students'));
    }
}

