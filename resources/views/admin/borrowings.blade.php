@extends('layouts.admin')

@section('title', 'Persetujuan & Transaksi Peminjaman')
@section('page_title', 'Persetujuan Peminjaman')

@section('admin_content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Transaksi Peminjaman</h1>
        <p class="text-xs text-slate-500 mt-1">Setujui pengajuan baru, tolak peminjaman bermasalah, atau konfirmasi pengembalian alat.</p>
    </div>
</div>

<!-- Filter & Search Toolbar -->
<div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
    <!-- Status Filter Tabs -->
    <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-semibold w-full md:w-auto">
        <a href="{{ route('admin.borrowings.index') }}" 
           class="px-3.5 py-2 rounded-xl transition {{ !request('status') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            Semua
        </a>
        <a href="{{ route('admin.borrowings.index', ['status' => 'menunggu']) }}" 
           class="px-3.5 py-2 rounded-xl transition {{ request('status') === 'menunggu' ? 'bg-amber-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            Menunggu
        </a>
        <a href="{{ route('admin.borrowings.index', ['status' => 'disetujui']) }}" 
           class="px-3.5 py-2 rounded-xl transition {{ request('status') === 'disetujui' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            Sedang Dipinjam
        </a>
        <a href="{{ route('admin.borrowings.index', ['status' => 'selesai']) }}" 
           class="px-3.5 py-2 rounded-xl transition {{ request('status') === 'selesai' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            Selesai
        </a>
        <a href="{{ route('admin.borrowings.index', ['status' => 'ditolak']) }}" 
           class="px-3.5 py-2 rounded-xl transition {{ request('status') === 'ditolak' ? 'bg-red-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            Ditolak
        </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('admin.borrowings.index') }}" method="GET" class="flex gap-2 w-full md:w-80">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / siswa / alat..."
            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="px-4 py-2 bg-slate-800 text-white font-bold text-xs rounded-xl">
            Cari
        </button>
    </form>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
    @if($borrowings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Kode Pinjam</th>
                        <th class="py-3.5 px-5">Peminjam (Siswa)</th>
                        <th class="py-3.5 px-5">Alat yang Dipinjam</th>
                        <th class="py-3.5 px-5">Jadwal Praktikum</th>
                        <th class="py-3.5 px-5 text-center">Jumlah</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-center">Tindakan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($borrowings as $b)
                        @php
                            $statusBadge = match($b->status) {
                                'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-slate-100 text-slate-700'
                            };

                            $statusLabel = match($b->status) {
                                'menunggu' => 'Menunggu Persetujuan',
                                'disetujui' => 'Sedang Dipinjam',
                                'selesai' => 'Selesai / Kembali',
                                'ditolak' => 'Ditolak',
                                default => ucfirst($b->status)
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Kode -->
                            <td class="py-3.5 px-5 font-bold text-slate-900 whitespace-nowrap">
                                <div>#{{ $b->borrow_code }}</div>
                                <div class="text-[10px] text-slate-400 font-normal">{{ $b->created_at->format('d/m/Y H:i') }}</div>
                            </td>

                            <!-- Siswa -->
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-800 text-sm">{{ $b->user->name ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $b->user->kelas ?? '-' }} • NISN: {{ $b->user->nisn ?? '-' }}</div>
                            </td>

                            <!-- Alat -->
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-800">{{ $b->equipment->name ?? 'Alat Dihapus' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $b->equipment->category->name ?? '-' }} (Stok saat ini: {{ $b->equipment->stock ?? 0 }})</div>
                                @if($b->is_damaged)
                                    <div class="mt-1.5 p-2 bg-red-50 border border-red-200 rounded-lg text-[11px] text-red-700 max-w-xs">
                                        <div class="font-bold">Laporan Kerusakan Siswa:</div>
                                        <div class="mt-0.5 italic">"{{ $b->damage_description }}"</div>
                                        @if($b->damage_photo)
                                            <a href="{{ asset('storage/' . $b->damage_photo) }}" target="_blank" class="text-blue-600 underline font-semibold mt-1 inline-block">
                                                Lihat Foto Bukti
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <!-- Jadwal -->
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <div class="font-medium text-slate-700">
                                    {{ $b->borrow_date->format('d M Y') }} s/d {{ $b->return_date->format('d M Y') }}
                                </div>
                                <div class="text-[10px] text-blue-600 font-semibold">
                                    Durasi: {{ $b->borrow_date->diffInDays($b->return_date) + 1 }} Hari
                                </div>
                            </td>

                            <!-- Jumlah -->
                            <td class="py-3.5 px-5 text-center font-bold text-slate-900">
                                {{ $b->amount }} Unit
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $statusBadge }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <!-- Tindakan Admin (Soal No. 2: Setujui atau Tolak Peminjaman) -->
                            <td class="py-3.5 px-5 whitespace-nowrap text-center">
                                @if($b->status === 'menunggu')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Setujui -->
                                        <form action="{{ route('admin.borrowings.approve', $b->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Setujui peminjaman alat ini? Stok akan otomatis berkurang.')"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-xs transition">
                                                Setujui
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak -->
                                        <button type="button" 
                                            onclick="openRejectModal('{{ $b->id }}', '{{ $b->user->name ?? '' }}', '{{ $b->equipment->name ?? '' }}')"
                                            class="px-3 py-1.5 bg-white border border-red-300 text-red-600 hover:bg-red-50 font-bold text-xs rounded-lg transition">
                                            Tolak
                                        </button>
                                    </div>
                                @elseif($b->status === 'disetujui')
                                    <!-- Konfirmasi Pengembalian Alat (Stok kembali bertambah) -->
                                    <form action="{{ route('admin.borrowings.return', $b->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa alat telah dikembalikan dalam kondisi baik? Stok akan bertambah kembali.')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-xs transition">
                                            Konfirmasi Pengembalian
                                        </button>
                                    </form>
                                @elseif($b->status === 'selesai')
                                    <span class="text-emerald-700 font-bold text-[11px]">
                                        Selesai dikembalikan
                                    </span>
                                @elseif($b->status === 'ditolak')
                                    <span class="text-red-600 text-[11px]">
                                        Alasan: {{ $b->rejection_reason ?? 'Ditolak laboran.' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $borrowings->links() }}
        </div>
    @else
        <div class="py-12 text-center text-slate-400 text-xs">
            Tidak ada transaksi peminjaman ditemukan.
        </div>
    @endif
</div>

<!-- Modal Tolak Peminjaman -->
<div id="rejectModal" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
        <h3 class="text-base font-bold text-slate-900 mb-1">Tolak Pengajuan Peminjaman</h3>
        <p id="rejectModalSubtext" class="text-xs text-slate-500 mb-4"></p>

        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Penolakan</label>
                <textarea name="rejection_reason" rows="3" required placeholder="Contoh: Alat sedang dalam proses perbaikan/kalibrasi..."
                    class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold">
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id, studentName, toolName) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        const subtext = document.getElementById('rejectModalSubtext');
        
        form.action = `/admin/peminjaman/${id}/tolak`;
        subtext.textContent = `Menolak pengajuan dari ${studentName} untuk alat ${toolName}.`;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection

