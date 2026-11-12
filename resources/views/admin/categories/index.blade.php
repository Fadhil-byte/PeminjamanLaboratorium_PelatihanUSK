@extends('layouts.admin')

@section('title', 'Kelola Kategori Alat')
@section('page_title', 'Kelola Kategori')

@section('admin_content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Kategori Alat Laboratorium</h1>
        <p class="text-xs text-slate-500 mt-1">Tambah, ubah, dan hapus kategori instrumen laboratorium (Biologi, Kimia, Elektronika, Fisika, dll).</p>
    </div>

    <button type="button" onclick="openAddCatModal()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>+ Tambah Kategori Baru</span>
    </button>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                <tr>
                    <th class="py-3.5 px-5">No.</th>
                    <th class="py-3.5 px-5">Nama Kategori</th>
                    <th class="py-3.5 px-5">Slug URL</th>
                    <th class="py-3.5 px-5">Deskripsi</th>
                    <th class="py-3.5 px-5 text-center">Jumlah Alat</th>
                    <th class="py-3.5 px-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $index => $cat)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-5 text-slate-400 font-bold">{{ $index + 1 }}</td>
                        <td class="py-3.5 px-5 font-bold text-slate-900 text-sm">{{ $cat->name }}</td>
                        <td class="py-3.5 px-5 text-slate-500 font-mono text-[11px]">{{ $cat->slug }}</td>
                        <td class="py-3.5 px-5 text-slate-600 max-w-sm">{{ $cat->description ?? '-' }}</td>
                        <td class="py-3.5 px-5 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                {{ $cat->equipments_count }} Alat
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" 
                                    onclick="openEditCatModal({{ json_encode($cat) }})" 
                                    class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-bold transition">
                                    Ubah
                                </button>

                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-xs font-bold transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">Belum ada kategori terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah / Edit Kategori -->
<div id="catModal" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-4">
            <h3 id="catModalTitle" class="text-base font-bold text-slate-900">Tambah Kategori Baru</h3>
            <button type="button" onclick="closeCatModal()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>

        <form id="catForm" method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
            @csrf
            <div id="catMethodContainer"></div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="catName" required placeholder="Contoh: Biologi Terapan"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                <textarea name="description" id="catDesc" rows="3" placeholder="Peralatan untuk praktikum..."
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeCatModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<script>
    const catModal = document.getElementById('catModal');
    const catForm = document.getElementById('catForm');
    const catModalTitle = document.getElementById('catModalTitle');
    const catMethodContainer = document.getElementById('catMethodContainer');

    function openAddCatModal() {
        catModalTitle.textContent = 'Tambah Kategori Alat Baru';
        catForm.action = "{{ route('admin.categories.store') }}";
        catMethodContainer.innerHTML = '';
        document.getElementById('catName').value = '';
        document.getElementById('catDesc').value = '';
        catModal.classList.remove('hidden');
    }

    function openEditCatModal(cat) {
        catModalTitle.textContent = 'Ubah Kategori: ' + cat.name;
        catForm.action = `/admin/kategori/${cat.id}`;
        catMethodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('catName').value = cat.name;
        document.getElementById('catDesc').value = cat.description || '';
        catModal.classList.remove('hidden');
    }

    function closeCatModal() {
        catModal.classList.add('hidden');
    }
</script>
@endsection

