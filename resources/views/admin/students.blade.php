@extends('layouts.admin')

@section('title', 'Daftar Siswa')
@section('page_title', 'Daftar Siswa Terdaftar')

@section('admin_content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar Siswa Terdaftar</h1>
        <p class="text-xs text-slate-500 mt-1">Daftar akun siswa yang memiliki hak akses untuk meminjam peralatan laboratorium.</p>
    </div>
</div>

<!-- Search Bar -->
<div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm mb-6">
    <form action="{{ route('admin.students.index') }}" method="GET" class="flex gap-2">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa, NISN, atau kelas..."
                class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.students.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl" title="Reset">&times;</a>
        @endif
    </form>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
    @if($students->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Siswa</th>
                        <th class="py-3.5 px-5">NISN</th>
                        <th class="py-3.5 px-5">Kelas & Jurusan</th>
                        <th class="py-3.5 px-5">Alamat Email</th>
                        <th class="py-3.5 px-5 text-center">Total Peminjaman</th>
                        <th class="py-3.5 px-5">Tgl Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $s)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Avatar & Name -->
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($s->name, 0, 2)) }}
                                    </div>
                                    <div class="font-bold text-slate-900 text-sm">
                                        {{ $s->name }}
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-5 font-mono text-slate-700 font-bold">
                                {{ $s->nisn ?? '-' }}
                            </td>

                            <td class="py-3.5 px-5 font-medium text-slate-700">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 font-semibold">
                                    {{ $s->kelas ?? '-' }}
                                </span>
                            </td>

                            <td class="py-3.5 px-5 text-slate-600">
                                {{ $s->email }}
                            </td>

                            <td class="py-3.5 px-5 text-center font-bold text-blue-600">
                                {{ $s->borrowings_count }} Transaksi
                            </td>

                            <td class="py-3.5 px-5 text-slate-400">
                                {{ $s->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $students->links() }}
        </div>
    @else
        <div class="py-12 text-center text-slate-400 text-xs">
            Tidak ada data siswa ditemukan.
        </div>
    @endif
</div>
@endsection

