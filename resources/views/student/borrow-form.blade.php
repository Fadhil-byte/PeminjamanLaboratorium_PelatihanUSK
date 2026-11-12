@extends('layouts.app')

@section('title', 'Formulir Pengajuan Pinjam Alat')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-4">
    <nav class="flex text-xs text-slate-500 font-medium space-x-2">
        <a href="{{ route('student.catalog') }}" class="hover:text-blue-600">Beranda</a>
        <span>&gt;</span>
        <a href="{{ route('student.catalog') }}" class="hover:text-blue-600">Katalog Alat</a>
        <span>&gt;</span>
        <span class="text-slate-900 font-bold">Formulir Peminjaman</span>
    </nav>
</div>

<!-- Header -->
<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Formulir Pengajuan Peminjaman Alat</h1>
    <p class="text-sm text-slate-500 mt-1">Lengkapi data di bawah ini untuk meminjam alat laboratorium sesuai jadwal praktikum.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <!-- Left Column: Form Pengajuan (8 Kolom) -->
    <div class="lg:col-span-8 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center justify-between">
            <span>Informasi Alat & Tanggal Praktikum</span>
            <span class="text-xs font-normal text-slate-400">Tanda (<span class="text-red-500">*</span>) wajib diisi</span>
        </h2>

        <form action="{{ route('student.borrow.submit') }}" method="POST" id="borrowForm" class="space-y-5">
            @csrf

            <!-- Field 1: Pilih Alat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Alat Laboratorium <span class="text-red-500">*</span></label>
                <select name="equipment_id" id="equipmentSelect" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="" disabled {{ !$selectedEquipment ? 'selected' : '' }}>-- Pilih Alat dari Daftar --</option>
                    @foreach($equipments as $eq)
                        <option value="{{ $eq->id }}" 
                            data-stock="{{ $eq->stock }}" 
                            data-name="{{ $eq->name }}"
                            data-cat="{{ $eq->category->name ?? '' }}"
                            {{ ($selectedEquipment && $selectedEquipment->id == $eq->id) || old('equipment_id') == $eq->id ? 'selected' : '' }}>
                            {{ $eq->code }} — {{ $eq->name }} (Stok Tersedia: {{ $eq->stock }} Unit)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Field 2: Jumlah Unit (Validasi: Stok tidak boleh minus) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Unit yang Dipinjam <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                    <div class="relative">
                        <input type="number" name="amount" id="amountInput" value="{{ old('amount', 1) }}" min="1" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <span class="absolute right-3.5 top-2.5 text-xs font-semibold text-slate-400">Unit</span>
                    </div>

                    <!-- Validation Helper Badge -->
                    <div class="p-2.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2 text-xs text-emerald-800">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span id="stockValidationText" class="font-medium">
                            Validasi: Min. 1 unit & stok tidak boleh minus.
                        </span>
                    </div>
                </div>
            </div>

            <!-- Field 3 & 4: Tanggal Pinjam & Kembali (Validasi tanggal kembali tidak boleh sebelum tanggal pinjam) -->
            <div class="space-y-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai Pinjam <span class="text-red-500">*</span></label>
                        <input type="date" name="borrow_date" id="borrowDate" value="{{ old('borrow_date', date('Y-m-d')) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Pengembalian <span class="text-red-500">*</span></label>
                        <input type="date" name="return_date" id="returnDate" value="{{ old('return_date', date('Y-m-d', strtotime('+3 days'))) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>

                <!-- Alert Validasi Tanggal (Standar USK No. 4) -->
                <div class="p-2.5 bg-blue-50 border border-blue-200 rounded-xl flex items-center gap-2 text-xs text-blue-800">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        <strong>Aturan Validasi:</strong> Tanggal pengembalian harus sama atau setelah tanggal pinjam.
                    </span>
                </div>
            </div>

            <!-- Field 5: Guru Pembimbing -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Guru Pembimbing / Pengampu Praktikum</label>
                <input type="text" name="supervisor" value="{{ old('supervisor') }}" placeholder="Contoh: Dra. Hj. Nurjanah, M.Pd"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- Field 6: Keperluan Praktikum -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tujuan / Keperluan Praktikum <span class="text-red-500">*</span></label>
                <textarea name="purpose" rows="3" required placeholder="Jelaskan judul materi praktikum atau riset yang akan dilakukan..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('purpose') }}</textarea>
            </div>

            <!-- Agreement Checkbox -->
            <div class="flex items-start gap-2 pt-2">
                <input id="agreement" type="checkbox" required class="h-4 w-4 mt-0.5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                <label for="agreement" class="text-xs text-slate-600 leading-relaxed cursor-pointer">
                    Saya menyatakan akan bertanggung jawab penuh atas keutuhan, kebersihan, dan keselamatan alat laboratorium yang saya pinjam sesuai jadwal yang tertera.
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center gap-3">
                <button type="submit" class="py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-500/20 transition flex items-center gap-2">
                    <span>Kirim Pengajuan Pinjam</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
                <a href="{{ route('student.catalog') }}" class="py-3 px-5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-semibold text-sm rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Right Column: Summary & SOP (4 Kolom) -->
    <div class="lg:col-span-4 space-y-6">
        <!-- Summary Box -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 pb-3 mb-4 border-b border-slate-100">Ringkasan Peminjaman</h3>
            
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 id="summaryToolName" class="text-xs font-bold text-slate-900 truncate">
                        {{ $selectedEquipment->name ?? 'Belum ada alat dipilih' }}
                    </h4>
                    <p id="summaryToolCategory" class="text-[11px] text-slate-500">
                        {{ $selectedEquipment->category->name ?? 'Pilih alat terlebih dahulu' }}
                    </p>
                </div>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Nama Peminjam</span>
                    <span class="font-bold text-slate-800">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">NISN / Kelas</span>
                    <span class="font-medium text-slate-800">{{ Auth::user()->nisn ?? '-' }} ({{ Auth::user()->kelas ?? 'Siswa' }})</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Status Awal</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        Menunggu Persetujuan
                    </span>
                </div>
            </div>
        </div>

        <!-- SOP Tata Tertib Box -->
        <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Tata Tertib & SOP Laboratorium</span>
            </h3>

            <ol class="space-y-2.5 text-xs text-slate-300 list-decimal list-inside leading-relaxed">
                <li>Pengajuan wajib disetujui Guru & Laboran sebelum alat diambil fisik.</li>
                <li>Pemeriksaan fisik dilakukan bersama saat serah terima alat.</li>
                <li>Keterlambatan pengembalian tanpa konfirmasi dikenakan sanksi.</li>
                <li>Wajib mengembalikan alat dalam keadaan bersih dan rapi.</li>
            </ol>
        </div>
    </div>
</div>

<script>
    // Client-side Validation Helper
    const equipmentSelect = document.getElementById('equipmentSelect');
    const amountInput = document.getElementById('amountInput');
    const borrowDate = document.getElementById('borrowDate');
    const returnDate = document.getElementById('returnDate');
    const stockValidationText = document.getElementById('stockValidationText');
    const summaryToolName = document.getElementById('summaryToolName');
    const summaryToolCategory = document.getElementById('summaryToolCategory');

    function updateEquipmentStock() {
        const selected = equipmentSelect.options[equipmentSelect.selectedIndex];
        if (selected && selected.value) {
            const stock = parseInt(selected.getAttribute('data-stock') || 0);
            amountInput.max = stock;
            stockValidationText.textContent = `Stok tersedia: ${stock} unit (Maks. ${stock})`;
            summaryToolName.textContent = selected.getAttribute('data-name');
            summaryToolCategory.textContent = selected.getAttribute('data-cat');
        }
    }

    equipmentSelect.addEventListener('change', updateEquipmentStock);
    borrowDate.addEventListener('change', function() {
        returnDate.min = borrowDate.value;
        if (returnDate.value < borrowDate.value) {
            returnDate.value = borrowDate.value;
        }
    });

    // Run on load
    updateEquipmentStock();
</script>
@endsection

