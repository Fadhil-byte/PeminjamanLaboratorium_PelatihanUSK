<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\CategoryController;

// Halaman Utama: Redirect ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autentikasi (Siswa & Admin)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Area Siswa (Memerlukan Login & Role Siswa)
Route::middleware(['auth'])->group(function () {
    // Katalog Alat
    Route::get('/katalog', [StudentController::class, 'catalog'])->name('student.catalog');
    
    // Formulir Peminjaman
    Route::get('/pinjam/{id?}', [StudentController::class, 'showBorrowForm'])->name('student.borrow.form');
    Route::post('/pinjam', [StudentController::class, 'submitBorrow'])->name('student.borrow.submit');
    
    // Riwayat & Status Siswa
    Route::get('/riwayat', [StudentController::class, 'history'])->name('student.history');
    Route::post('/riwayat/{id}/lapor-rusak', [StudentController::class, 'reportDamage'])->name('student.borrow.report_damage');
});

// Area Administrator (Memerlukan Login & Role Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Persetujuan & Tindakan Peminjaman
    Route::get('/peminjaman', [AdminController::class, 'borrowings'])->name('borrowings.index');
    Route::post('/peminjaman/{id}/setujui', [AdminController::class, 'approveBorrowing'])->name('borrowings.approve');
    Route::post('/peminjaman/{id}/tolak', [AdminController::class, 'rejectBorrowing'])->name('borrowings.reject');
    Route::post('/peminjaman/{id}/kembalikan', [AdminController::class, 'returnBorrowing'])->name('borrowings.return');
    
    // Daftar Siswa
    Route::get('/siswa', [AdminController::class, 'students'])->name('students.index');
    
    // Kelola Data Alat (CRUD)
    Route::get('/alat', [EquipmentController::class, 'index'])->name('equipments.index');
    Route::post('/alat', [EquipmentController::class, 'store'])->name('equipments.store');
    Route::put('/alat/{id}', [EquipmentController::class, 'update'])->name('equipments.update');
    Route::delete('/alat/{id}', [EquipmentController::class, 'destroy'])->name('equipments.destroy');
    
    // Kelola Kategori (CRUD)
    Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/kategori', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/kategori/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/kategori/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
