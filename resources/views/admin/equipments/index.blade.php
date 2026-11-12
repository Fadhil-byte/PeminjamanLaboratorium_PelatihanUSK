@extends('layouts.admin')

@section('title', 'Kelola Data Alat')
@section('page_title', 'Kelola Inventaris Alat')

@section('admin_content')
<!-- Header Toolbar (Identik dengan Figma Mockup 6) -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Data Alat Laboratorium</h1>
        <p class="text-xs text-slate-500 mt-1">Tambah, ubah data alat, atur jumlah stok, upload foto, dan kelola kategori.</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-2">
        <button type="button" onclick="openAddModal()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Alat Baru</span>
        </button>

        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl transition">
            Kelola Kategori
        </a>
    </div>
</div>

<!-- Filter & Search Toolbar -->
<div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm mb-6">
    <form action="{{ route('admin.equipments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
        <!-- Search Input -->
        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat / kode seri / lokasi..."
                class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Category Dropdown -->
        <div class="md:col-span-4">
            <select name="category_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2 flex gap-1.5">
            <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'category_id']))
                <a href="{{ route('admin.equipments.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl" title="Reset">
                    &times;
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
    @if($equipments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Foto</th>
                        <th class="py-3.5 px-5">Nama Alat & Spesifikasi</th>
                        <th class="py-3.5 px-5">Kategori</th>
                        <th class="py-3.5 px-5">Stok (Tersedia)</th>
                        <th class="py-3.5 px-5">Kondisi</th>
                        <th class="py-3.5 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($equipments as $item)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Foto Thumbnail -->
                            <td class="py-3 px-5">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                    @if($item->photo)
                                        <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    @endif
                                </div>
                            </td>

                            <!-- Nama & Spesifikasi -->
                            <td class="py-3 px-5">
                                <div class="font-bold text-slate-900 text-sm">{{ $item->name }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    Kode: <strong class="text-slate-700">{{ $item->code }}</strong>
                                    @if($item->location) • Rak: {{ $item->location }} @endif
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="py-3 px-5">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $item->category->name ?? '-' }}
                                </span>
                            </td>

                            <!-- Stok (Validasi Stok >= 0) -->
                            <td class="py-3 px-5 whitespace-nowrap">
                                <div class="text-sm font-extrabold {{ $item->stock > 0 ? 'text-slate-900' : 'text-red-600' }}">
                                    {{ $item->stock }} Unit
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $item->stock > 0 ? 'Siap dipinjam' : 'Stok kosong' }}
                                </div>
                            </td>

                            <!-- Kondisi -->
                            <td class="py-3 px-5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $item->condition === 'Baik' ? 'text-emerald-700' : 'text-amber-700' }}">
                                    <span class="w-2 h-2 rounded-full {{ $item->condition === 'Baik' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ $item->condition }}
                                </span>
                            </td>

                            <!-- Aksi (Edit & Hapus) -->
                            <td class="py-3 px-5 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Ubah -->
                                    <button type="button" 
                                        onclick="openEditModal({{ json_encode($item) }})" 
                                        class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-bold transition">
                                        Ubah
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.equipments.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data alat {{ $item->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-xs font-bold transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $equipments->links() }}
        </div>
    @else
        <div class="py-12 text-center text-slate-400 text-xs">
            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <p class="font-bold text-slate-700 text-sm">Belum Ada Data Alat</p>
            <p class="text-slate-400 mt-1">Klik tombol "Tambah Alat Baru" di atas untuk menambahkan data.</p>
        </div>
    @endif
</div>

<!-- ================= MODAL TAMBAH / EDIT ALAT ================= -->
<div id="equipmentModal" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-blue-500/20 my-8">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                    FORM MODAL POPUP
                </span>
                <h3 id="modalTitle" class="text-lg font-extrabold text-slate-900 tracking-tight mt-1">
                    Tambah Data Alat Laboratorium
                </h3>
            </div>
            <button type="button" onclick="closeEquipmentModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>

        <form id="equipmentForm" method="POST" action="{{ route('admin.equipments.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div id="methodContainer"></div>

            <!-- Nama & Kode Seri (2 Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Alat Laboratorium <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="modalName" required placeholder="Contoh: Mikroskop CX23"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Seri Unik <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="modalCode" required placeholder="Contoh: LAB-BIO-001"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Kategori, Stok & Kondisi (3 Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" id="modalCategory" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Validasi Stok Tidak Boleh Minus (Soal No. 4) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Jumlah Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stock" id="modalStock" min="0" required placeholder="Min 0"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="text-[10px] text-slate-500 font-medium">Validasi: Min. 0 (tidak boleh minus)</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kondisi Alat</label>
                    <select name="condition" id="modalCondition"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Baik">Baik (Siap Pakai)</option>
                        <option value="Perlu Kalibrasi">Perlu Kalibrasi</option>
                        <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                    </select>
                </div>
            </div>

            <!-- Lokasi Lemari / Rak -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Penyimpanan Rak / Meja</label>
                <input type="text" name="location" id="modalLocation" placeholder="Contoh: Lemari Kaca Bio A-2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Upload Foto Alat (Soal No. 2) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Foto Alat (JPG / PNG, Max 2MB)</label>
                <input type="file" name="photo" id="modalPhoto" accept="image/*"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 border-dashed rounded-xl text-xs focus:bg-white focus:outline-none">
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi & Spesifikasi Singkat</label>
                <textarea name="description" id="modalDescription" rows="2" placeholder="Spesifikasi teknis alat..."
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEquipmentModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Simpan Data Alat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('equipmentModal');
    const form = document.getElementById('equipmentForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');

    function openAddModal() {
        modalTitle.textContent = 'Tambah Data Alat Laboratorium Baru';
        form.action = "{{ route('admin.equipments.store') }}";
        methodContainer.innerHTML = '';
        
        document.getElementById('modalName').value = '';
        document.getElementById('modalCode').value = '';
        document.getElementById('modalStock').value = 1;
        document.getElementById('modalLocation').value = '';
        document.getElementById('modalDescription').value = '';
        
        modal.classList.remove('hidden');
    }

    function openEditModal(item) {
        modalTitle.textContent = 'Ubah Data Alat: ' + item.name;
        form.action = `/admin/alat/${item.id}`;
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('modalName').value = item.name;
        document.getElementById('modalCode').value = item.code;
        document.getElementById('modalCategory').value = item.category_id;
        document.getElementById('modalStock').value = item.stock;
        document.getElementById('modalCondition').value = item.condition;
        document.getElementById('modalLocation').value = item.location || '';
        document.getElementById('modalDescription').value = item.description || '';

        modal.classList.remove('hidden');
    }

    function closeEquipmentModal() {
        modal.classList.add('hidden');
    }
</script>
@endsection

