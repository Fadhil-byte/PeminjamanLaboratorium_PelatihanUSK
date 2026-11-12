@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Siswa')

@section('content')
<!-- Header -->
<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat & Status Peminjaman Saya</h1>
    <p class="text-sm text-slate-500 mt-1">Pantau proses persetujuan laboran dan status pengembalian alat praktikum Anda.</p>
</div>

<!-- 4 Stat Cards (Identik dengan Figma Mockup 4) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1: Total Pengajuan -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <span class="block text-[11px] font-bold text-slate-400 tracking-wider">TOTAL PENGAJUAN</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ $stats['total'] }} Kali</span>
        </div>
    </div>

    <!-- Card 2: Menunggu Persetujuan -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <span class="block text-[11px] font-bold text-amber-600 tracking-wider">MENUNGGU VERIFIKASI</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ $stats['menunggu'] }} Alat</span>
        </div>
    </div>

    <!-- Card 3: Sedang Dipinjam -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
        </div>
        <div>
            <span class="block text-[11px] font-bold text-blue-600 tracking-wider">SEDANG DIPINJAM</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ $stats['disetujui'] }} Alat</span>
        </div>
    </div>

    <!-- Card 4: Selesai / Dikembalikan -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <span class="block text-[11px] font-bold text-emerald-600 tracking-wider">SELESAI / KEMBALI</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ $stats['selesai'] }} Alat</span>
        </div>
    </div>
</div>

<!-- Table Card Container -->
<div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
    <!-- Header Toolbar with Filter Tabs -->
    <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h3 class="text-base font-bold text-slate-900">Daftar Peminjaman Saya</h3>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-semibold">
            <a href="{{ route('student.history', ['status' => 'all']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ !request('status') || request('status') === 'all' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Semua ({{ $stats['total'] }})
            </a>
            <a href="{{ route('student.history', ['status' => 'menunggu']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('status') === 'menunggu' ? 'bg-amber-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Menunggu ({{ $stats['menunggu'] }})
            </a>
            <a href="{{ route('student.history', ['status' => 'disetujui']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('status') === 'disetujui' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Dipinjam ({{ $stats['disetujui'] }})
            </a>
            <a href="{{ route('student.history', ['status' => 'selesai']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('status') === 'selesai' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Selesai ({{ $stats['selesai'] }})
            </a>
            @if($stats['ditolak'] > 0)
                <a href="{{ route('student.history', ['status' => 'ditolak']) }}" 
                   class="px-3 py-1.5 rounded-lg transition {{ request('status') === 'ditolak' ? 'bg-red-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    Ditolak ({{ $stats['ditolak'] }})
                </a>
            @endif
        </div>
    </div>

    <!-- Table -->
    @if($borrowings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Kode Pinjam</th>
                        <th class="py-3.5 px-5">Nama Alat & Kategori</th>
                        <th class="py-3.5 px-5">Periode Pinjam - Kembali</th>
                        <th class="py-3.5 px-5 text-center">Jumlah</th>
                        <th class="py-3.5 px-5">Status Pengajuan</th>
                        <th class="py-3.5 px-5">Catatan / Keterangan</th>
                        <th class="py-3.5 px-5 text-center">Aksi</th>
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
                                'disetujui' => 'Disetujui / Sedang Dipinjam',
                                'selesai' => 'Selesai / Dikembalikan',
                                'ditolak' => 'Pengajuan Ditolak',
                                default => ucfirst($b->status)
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Kode Pinjam -->
                            <td class="py-4 px-5 font-bold text-slate-900 whitespace-nowrap">
                                <div>#{{ $b->borrow_code }}</div>
                                <div class="text-[10px] text-slate-400 font-normal">Diajukan: {{ $b->created_at->format('d M Y, H:i') }}</div>
                            </td>

                            <!-- Nama Alat & Kategori -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-800 text-sm">{{ $b->equipment->name ?? 'Alat Dihapus' }}</div>
                                <div class="text-slate-500 text-[11px]">{{ $b->equipment->category->name ?? '-' }} • Seri: {{ $b->equipment->code ?? '-' }}</div>
                            </td>

                            <!-- Periode Pinjam & Durasi -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="text-slate-800 font-medium">
                                    {{ $b->borrow_date->format('d M Y') }} s/d {{ $b->return_date->format('d M Y') }}
                                </div>
                                <div class="text-blue-600 text-[11px] font-semibold">
                                    Durasi: {{ $b->borrow_date->diffInDays($b->return_date) + 1 }} Hari
                                </div>
                            </td>

                            <!-- Jumlah Unit -->
                            <td class="py-4 px-5 text-center font-bold text-slate-800">
                                {{ $b->amount }} Unit
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold border {{ $statusBadge }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $b->status === 'menunggu' ? 'bg-amber-500' : ($b->status === 'disetujui' ? 'bg-blue-600' : ($b->status === 'selesai' ? 'bg-emerald-600' : 'bg-red-600')) }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <!-- Catatan -->
                            <td class="py-4 px-5 text-slate-500 text-xs max-w-xs">
                                @if($b->status === 'ditolak')
                                    <span class="text-red-600 font-medium">Alasan: {{ $b->rejection_reason ?? 'Tidak memenuhi syarat.' }}</span>
                                @elseif($b->status === 'selesai')
                                    <span class="text-emerald-700 font-medium">Dikembalikan pada {{ $b->returned_at ? $b->returned_at->format('d M Y') : '-' }}</span>
                                @elseif($b->status === 'disetujui')
                                    @if($b->is_damaged)
                                        <span class="text-red-700 font-semibold block">Laporan kerusakan: "{{ Str::limit($b->damage_description, 40) }}"</span>
                                    @else
                                        <span class="text-blue-700 font-medium">Silakan ambil alat di laboratorium sekolah.</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">Sedang ditinjau oleh Pak Budi (Laboran).</span>
                                @endif
                            </td>

                            <!-- Aksi User -->
                            <td class="py-4 px-5 text-center whitespace-nowrap">
                                @if($b->status === 'disetujui')
                                    @if($b->is_damaged)
                                        <span class="inline-block px-2.5 py-1 bg-red-50 text-red-700 border border-red-200 rounded-lg text-[11px] font-bold">
                                            Kerusakan Dilaporkan
                                        </span>
                                    @else
                                        <button type="button" 
                                            onclick="openDamageModal('{{ $b->id }}', '{{ $b->borrow_code }}', '{{ $b->equipment->name ?? '' }}')" 
                                            class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-lg text-xs font-bold transition">
                                            Lapor Rusak
                                        </button>
                                    @endif
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
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
        <div class="py-12 text-center text-slate-400">
            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-700">Belum Ada Riwayat Peminjaman</p>
            <p class="text-xs text-slate-400 mt-1">Anda belum pernah mengajukan peminjaman alat pada laboratorium.</p>
            <a href="{{ route('student.catalog') }}" class="inline-block mt-4 text-xs font-bold text-blue-600 hover:underline">
                Buka Katalog & Pinjam Sekarang &rarr;
            </a>
        </div>
    @endif
</div>

<!-- ================= MODAL FORM LAPORAN BARANG RUSAK ================= -->
<div id="damageModal" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-red-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <div>
                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full border border-red-200 uppercase tracking-wider">
                    Laporan Kerusakan
                </span>
                <h3 class="text-base font-extrabold text-slate-900 mt-1">Form Laporan Barang Rusak</h3>
            </div>
            <button type="button" onclick="closeDamageModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>

        <form id="damageForm" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Info Alat -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                <div class="text-slate-500">Kode Peminjaman: <strong id="modalBorrowCode" class="text-slate-800"></strong></div>
                <div class="text-slate-500 mt-0.5">Nama Alat: <strong id="modalEquipmentName" class="text-slate-800"></strong></div>
            </div>

            <!-- Deskripsi Kerusakan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Deskripsi / Kronologi Kerusakan <span class="text-red-500">*</span>
                </label>
                <textarea name="damage_description" rows="3" required placeholder="Jelaskan detail bagian yang rusak atau bagaimana kerusakan terjadi..."
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500 transition"></textarea>
                <span class="text-[10px] text-slate-400">Contoh: Lensa mikroskop tergores saat praktikum preparat, layar tidak menyala, kabel putus, dsb.</span>
            </div>

            <!-- Foto Bukti Kerusakan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Foto Bukti Kerusakan (Opsional, JPG/PNG Max 2MB)</label>
                <input type="file" name="damage_photo" accept="image/*"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 border-dashed rounded-xl text-xs focus:bg-white focus:outline-none">
            </div>

            <!-- Warning Notice -->
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-800 leading-relaxed">
                Pemberitahuan: Laporan ini akan langsung diteruskan ke laboran untuk ditindaklanjuti. Harap simpan alat di tempat yang aman.
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeDamageModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Kirim Laporan Kerusakan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDamageModal(id, borrowCode, equipmentName) {
        const modal = document.getElementById('damageModal');
        const form = document.getElementById('damageForm');
        
        form.action = `/riwayat/${id}/lapor-rusak`;
        document.getElementById('modalBorrowCode').textContent = '#' + borrowCode;
        document.getElementById('modalEquipmentName').textContent = equipmentName;
        
        modal.classList.remove('hidden');
    }

    function closeDamageModal() {
        document.getElementById('damageModal').classList.add('hidden');
    }
</script>
@endsection

