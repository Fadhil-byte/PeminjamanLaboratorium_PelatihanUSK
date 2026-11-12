@extends('layouts.app')

@section('title', 'Katalog Alat Laboratorium')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Alat Laboratorium</h1>
    <p class="text-sm text-slate-500 mt-1">Jelajahi dan pilih alat praktikum yang tersedia untuk dipinjam secara langsung.</p>
</div>

<!-- Search & Filter Toolbar (Identik dengan Figma Mockup 2) -->
<div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm mb-6">
    <form action="{{ route('student.catalog') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
        <!-- Search Input -->
        <div class="md:col-span-5 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat (misal: Mikroskop, Osiloskop)..."
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>

        <!-- Filter Kategori -->
        <div class="md:col-span-3">
            <select name="category_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->equipments_count }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Status Stok -->
        <div class="md:col-span-2">
            <select name="status_stok" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <option value="">Semua Status Stok</option>
                <option value="ready" {{ request('status_stok') == 'ready' ? 'selected' : '' }}>Tersedia (Ready)</option>
                <option value="empty" {{ request('status_stok') == 'empty' ? 'selected' : '' }}>Stok Habis</option>
            </select>
        </div>

        <!-- Submit Filter Button -->
        <div class="md:col-span-2 flex gap-2">
            <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Filter Alat</span>
            </button>
            @if(request()->hasAny(['search', 'category_id', 'status_stok']))
                <a href="{{ route('student.catalog') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl" title="Reset filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Quick Category Pills -->
<div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 no-scrollbar">
    <a href="{{ route('student.catalog') }}" class="px-4 py-1.5 rounded-full text-xs font-semibold shrink-0 transition {{ !request('category_id') ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
        Semua Alat
    </a>
    @foreach($categories as $cat)
        <a href="{{ route('student.catalog', array_merge(request()->query(), ['category_id' => $cat->id])) }}" 
           class="px-4 py-1.5 rounded-full text-xs font-semibold shrink-0 transition {{ request('category_id') == $cat->id ? 'bg-blue-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
            {{ $cat->name }} ({{ $cat->equipments_count }})
        </a>
    @endforeach
</div>

<!-- Equipment Grid (3 Kolom seperti Mockup Figma) -->
@if($equipments->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($equipments as $item)
            @php
                // Category badge colors
                $catColor = match(strtolower($item->category->name ?? '')) {
                    'biologi terapan', 'biologi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'kimia analitik', 'kimia' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'elektronika & it', 'elektronika' => 'bg-blue-50 text-blue-700 border-blue-200',
                    default => 'bg-amber-50 text-amber-700 border-amber-200'
                };
            @endphp

            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md transition flex flex-col overflow-hidden {{ $item->stock <= 0 ? 'opacity-80' : '' }}">
                <!-- Thumbnail Box -->
                <div class="h-44 bg-slate-100 relative flex items-center justify-center overflow-hidden border-b border-slate-100">
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                    @endif

                    <!-- Category Badge -->
                    <span class="absolute top-3 left-3 px-2.5 py-1 text-[11px] font-bold rounded-lg border uppercase tracking-wider {{ $catColor }}">
                        {{ $item->category->name ?? 'Umum' }}
                    </span>

                    <!-- Stock Badge -->
                    <span class="absolute top-3 right-3 px-2.5 py-1 text-[11px] font-bold rounded-lg border shadow-xs {{ $item->stock > 2 ? 'bg-blue-50 text-blue-700 border-blue-200' : ($item->stock > 0 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-red-50 text-red-700 border-red-200') }}">
                        @if($item->stock > 2)
                            Stok: {{ $item->stock }} Unit
                        @elseif($item->stock > 0)
                            Sisa {{ $item->stock }} Unit
                        @else
                            Stok Habis (0)
                        @endif
                    </span>
                </div>

                <!-- Card Content -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight leading-snug line-clamp-1">
                            {{ $item->name }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                            <span>Kode: <strong class="text-slate-700">{{ $item->code }}</strong></span>
                            @if($item->location)
                                <span>•</span>
                                <span class="truncate">{{ $item->location }}</span>
                            @endif
                        </p>

                        <p class="text-xs text-slate-600 mt-3 line-clamp-2 leading-relaxed">
                            {{ $item->description ?? 'Tidak ada deskripsi rinci untuk alat laboratorium ini.' }}
                        </p>
                    </div>

                    <!-- Divider -->
                    <div class="pt-5 mt-4 border-t border-slate-100">
                        @if($item->stock > 0)
                            <a href="{{ route('student.borrow.form', $item->id) }}" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                                <span>Ajukan Peminjaman</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        @else
                            <button type="button" disabled class="w-full py-2.5 px-4 bg-slate-100 text-slate-400 font-semibold text-xs rounded-xl cursor-not-allowed">
                                Stok Sedang Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $equipments->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto">
        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800">Alat Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 mt-1">Tidak ada alat yang sesuai dengan kriteria filter atau pencarian Anda.</p>
        <a href="{{ route('student.catalog') }}" class="inline-block mt-4 text-xs font-semibold text-blue-600 hover:underline">
            Tampilkan Semua Alat &rarr;
        </a>
    </div>
@endif

@endsection

