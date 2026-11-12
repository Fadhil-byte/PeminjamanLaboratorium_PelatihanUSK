@extends('layouts.admin')

@section('title', 'Dashboard Administrator')
@section('page_title', 'Dashboard Utama')

@section('admin_content')
<!-- Header -->
<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Administrator Laboratorium</h1>
    <p class="text-xs text-slate-500 mt-1">Ringkasan aktivitas inventaris alat, status pengajuan siswa, dan log laboratorium hari ini.</p>
</div>

<!-- 4 Stats Cards (Identik dengan Figma Mockup 5) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1: Total Alat -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div>
            <span class="block text-[11px] font-bold text-slate-400 tracking-wider">TOTAL DATA ALAT</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ $stats['total_equipments'] }} Jenis</span>
            <span class="block text-[11px] text-emerald-600 font-semibold">{{ $stats['total_stock'] }} Unit Total Stok</span>
        </div>
    </div>

    <!-- Card 2: Peminjaman Aktif -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
        </div>
        <div>
            <span class="block text-[11px] font-bold text-slate-400 tracking-wider">PEMINJAMAN AKTIF</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ $stats['active_borrowings'] }} Transaksi</span>
            <span class="block text-[11px] text-blue-600 font-semibold">Sedang digunakan siswa</span>
        </div>
    </div>

    <!-- Card 3: Menunggu Verifikasi (Highlight) -->
    <div class="bg-amber-50/80 p-5 rounded-2xl border border-amber-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-amber-500/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <span class="block text-[11px] font-bold text-amber-800 tracking-wider">PERLU PERSETUJUAN</span>
            <span class="text-2xl font-extrabold text-amber-900">{{ $stats['pending_borrowings'] }} Pengajuan</span>
            <span class="block text-[11px] text-amber-700 font-bold">Segera setujui / tolak</span>
        </div>
    </div>

    <!-- Card 4: Total Siswa -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
            <span class="block text-[11px] font-bold text-slate-400 tracking-wider">SISWA TERDAFTAR</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ $stats['total_students'] }} Siswa</span>
            <span class="block text-[11px] text-slate-500 font-semibold">RPL, TKJ, dll.</span>
        </div>
    </div>
</div>

<!-- Main Split: Approval List & Category Distribution -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    <!-- Left Column: Pengajuan Perlu Persetujuan Segera (8 Kolom) -->
    <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Pengajuan Peminjaman Terbaru (Menunggu Persetujuan)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Tindakan Admin: Setujui pengajuan atau tolak dengan alasan yang jelas.</p>
            </div>
            <a href="{{ route('admin.borrowings.index', ['status' => 'menunggu']) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        @if($pendingApprovals->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($pendingApprovals as $p)
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/60 transition">
                        <!-- Student & Equipment Details -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($p->user->name ?? 'S', 0, 2)) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-slate-900">{{ $p->user->name ?? 'Siswa' }}</h4>
                                    <span class="text-xs font-semibold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">
                                        {{ $p->user->kelas ?? 'Siswa' }} • NISN: {{ $p->user->nisn ?? '-' }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-700 mt-1">
                                    Meminjam: <strong class="text-blue-600">{{ $p->equipment->name ?? '-' }}</strong> 
                                    <span class="font-bold text-slate-900">({{ $p->amount }} Unit)</span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    Jadwal: <strong>{{ $p->borrow_date->format('d M') }} s/d {{ $p->return_date->format('d M Y') }}</strong>
                                    @if($p->supervisor) • Guru: {{ $p->supervisor }} @endif
                                </div>
                                <div class="text-[11px] text-slate-500 mt-1 italic">
                                    "{{ Str::limit($p->purpose, 70) }}"
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons: Setujui & Tolak (Soal No. 2) -->
                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                            <!-- Tombol Setujui -->
                            <form action="{{ route('admin.borrowings.approve', $p->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini? Stok alat akan berkurang otomatis.')" 
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Setujui</span>
                                </button>
                            </form>

                            <!-- Tombol Tolak Modal Trigger -->
                            <button type="button" onclick="openRejectModal('{{ $p->id }}', '{{ $p->user->name }}', '{{ $p->equipment->name }}')" 
                                class="px-3.5 py-2 bg-white border border-red-300 text-red-600 hover:bg-red-50 font-bold text-xs rounded-xl transition">
                                Tolak
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-slate-400 text-xs">
                Tidak ada pengajuan peminjaman baru yang menunggu persetujuan.
            </div>
        @endif
    </div>

    <!-- Right Column: Distribusi Kategori Alat (4 Kolom) -->
    <div class="lg:col-span-4 space-y-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-1">Distribusi Kategori Alat</h3>
            <p class="text-[11px] text-slate-500 mb-4">Total data alat terkelola di database laboratorium.</p>

            <div class="space-y-4">
                @foreach($categoryDistribution as $cat)
                    @php
                        $percentage = $stats['total_equipments'] > 0 ? round(($cat->equipments_count / $stats['total_equipments']) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">{{ $cat->name }}</span>
                            <span class="text-slate-900">{{ $cat->equipments_count }} Jenis ({{ $percentage }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.categories.index') }}" class="w-full py-2.5 px-4 bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5">
                    <span>+ Kelola Kategori Alat</span>
                </a>
            </div>
        </div>

        <!-- Quick Links Card -->
        <div class="bg-slate-900 text-white p-5 rounded-2xl shadow-sm text-xs space-y-3">
            <h4 class="font-bold text-white text-sm">Pintasan Cepat</h4>
            <div class="space-y-2">
                <a href="{{ route('admin.equipments.index') }}" class="block p-2 rounded-lg bg-slate-800 hover:bg-slate-700 transition">
                    + Tambah Alat Laboratorium Baru
                </a>
                <a href="{{ route('admin.students.index') }}" class="block p-2 rounded-lg bg-slate-800 hover:bg-slate-700 transition">
                    Lihat Data Siswa Terdaftar
                </a>
                <a href="{{ route('admin.borrowings.index') }}" class="block p-2 rounded-lg bg-slate-800 hover:bg-slate-700 transition">
                    Rekap Semua Transaksi Peminjaman
                </a>
            </div>
        </div>
    </div>
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

