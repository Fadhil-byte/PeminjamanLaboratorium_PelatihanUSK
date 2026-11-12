<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Borrowing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users (Admin & Siswa) - Password disimpan sebagai Hash
        $admin = User::create([
            'name' => 'Pak Budi Santoso',
            'email' => 'admin@lab.sch.id',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        $siswa1 = User::create([
            'name' => 'Ahmad Fadhil',
            'email' => 'ahmad@smk.sch.id',
            'nisn' => '2104018',
            'kelas' => 'X RPL 1',
            'role' => 'siswa',
            'password' => Hash::make('password'),
        ]);

        $siswa2 = User::create([
            'name' => 'Siti Rahmah',
            'email' => 'siti@smk.sch.id',
            'nisn' => '2104033',
            'kelas' => 'XI TKJ 2',
            'role' => 'siswa',
            'password' => Hash::make('password'),
        ]);

        $siswa3 = User::create([
            'name' => 'Danu Tirta',
            'email' => 'danu@smk.sch.id',
            'nisn' => '2104089',
            'kelas' => 'XII RPL 1',
            'role' => 'siswa',
            'password' => Hash::make('password'),
        ]);

        // 2. Kategori
        $catBiologi = Category::create([
            'name' => 'Biologi Terapan',
            'slug' => 'biologi-terapan',
            'description' => 'Peralatan laboratorium biologi, mikrobiologi, dan anatomi.',
        ]);

        $catKimia = Category::create([
            'name' => 'Kimia Analitik',
            'slug' => 'kimia-analitik',
            'description' => 'Peralatan gelas kaca borosilikat, titrasi, dan reagen kimia.',
        ]);

        $catElektronika = Category::create([
            'name' => 'Elektronika & IT',
            'slug' => 'elektronika-dan-it',
            'description' => 'Alat ukur instrumen elektrik, oscilloscope, dan soldering station.',
        ]);

        $catFisika = Category::create([
            'name' => 'Fisika Dasar',
            'slug' => 'fisika-dasar',
            'description' => 'Peralatan mekanika, optik, gelombang, dan magnetisme.',
        ]);

        // 3. Alat Laboratorium (Validasi: stok tidak boleh minus)
        $eq1 = Equipment::create([
            'category_id' => $catBiologi->id,
            'name' => 'Mikroskop Binokuler Digital CX23',
            'code' => 'LAB-BIO-001',
            'stock' => 12,
            'location' => 'Lemari Bio Lantai 2, Rak A-2',
            'condition' => 'Baik',
            'description' => 'Lensa pembesaran 4x-100x Oil, pencahayaan LED adjustable, cocok untuk penelitian sel dan jaringan preparat basah.',
        ]);

        $eq2 = Equipment::create([
            'category_id' => $catElektronika->id,
            'name' => 'Digital Storage Oscilloscope 100MHz',
            'code' => 'LAB-ELK-014',
            'stock' => 5,
            'location' => 'Meja Praktikum Hardware B-4',
            'condition' => 'Baik',
            'description' => '2 Channel analog probe, sampling rate 1GSa/s, layar TFT warna 7 inci untuk analisa sinyal digital frekuensi tinggi.',
        ]);

        $eq3 = Equipment::create([
            'category_id' => $catKimia->id,
            'name' => 'Set Buret & Statif Titrasi Pyrex 50ml',
            'code' => 'LAB-KIM-008',
            'stock' => 18,
            'location' => 'Lemari Reagen Kimia K-1',
            'condition' => 'Baik',
            'description' => 'Buret kaca borosilikat presisi 0.1ml dengan kran Teflon tahan asam basa dan statif klem ganda anti karat.',
        ]);

        $eq4 = Equipment::create([
            'category_id' => $catElektronika->id,
            'name' => 'Multimeter Digital True RMS CD800a',
            'code' => 'LAB-ELK-029',
            'stock' => 8,
            'location' => 'Laci Tool Instrumen E-1',
            'condition' => 'Baik',
            'description' => 'Pengukuran akurat AC/DC Voltage, Ampere, Ohm, Frekuensi, dan kontinuitas buzzer dilengkapi probe pengaman.',
        ]);

        $eq5 = Equipment::create([
            'category_id' => $catBiologi->id,
            'name' => 'Centrifuge DLAB Clinical 4000 RPM',
            'code' => 'LAB-BIO-007',
            'stock' => 2,
            'location' => 'Meja Separasi Cairan B-1',
            'condition' => 'Baik',
            'description' => 'Kapasitas rotor 8x15ml dengan kontrol kecepatan presisi dan timer digital untuk pemisahan sampel cairan.',
        ]);

        $eq6 = Equipment::create([
            'category_id' => $catKimia->id,
            'name' => 'Gelas Kimia Beaker Pyrex 500ml',
            'code' => 'LAB-KIM-019',
            'stock' => 0, // Contoh stok habis
            'location' => 'Lemari Kaca C-3',
            'condition' => 'Baik',
            'description' => 'Gelas borosilikat tahan pemanasan langsung hingga 400°C.',
        ]);

        // 4. Data Peminjaman (Beragam status)
        Borrowing::create([
            'borrow_code' => 'PMJ-2026-0042',
            'user_id' => $siswa1->id,
            'equipment_id' => $eq1->id,
            'amount' => 2,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'purpose' => 'Praktikum pengamatan struktur sel daun Rhoeo discolor dan preparat basah.',
            'supervisor' => 'Dra. Hj. Nurjanah, M.Pd',
            'status' => 'menunggu',
        ]);

        Borrowing::create([
            'borrow_code' => 'PMJ-2026-0038',
            'user_id' => $siswa1->id,
            'equipment_id' => $eq2->id,
            'amount' => 1,
            'borrow_date' => now()->subDays(2)->toDateString(),
            'return_date' => now()->addDays(1)->toDateString(),
            'purpose' => 'Analisa sinyal PWM rangkaian catu daya elektronika.',
            'supervisor' => 'Hendra Setiawan, S.T.',
            'status' => 'disetujui',
            'approved_at' => now()->subDays(2),
        ]);

        Borrowing::create([
            'borrow_code' => 'PMJ-2026-0021',
            'user_id' => $siswa1->id,
            'equipment_id' => $eq4->id,
            'amount' => 1,
            'borrow_date' => now()->subDays(8)->toDateString(),
            'return_date' => now()->subDays(5)->toDateString(),
            'purpose' => 'Pengukuran tegangan drop adaptor.',
            'supervisor' => 'Hendra Setiawan, S.T.',
            'status' => 'selesai',
            'approved_at' => now()->subDays(8),
            'returned_at' => now()->subDays(5),
            'notes' => 'Alat dikembalikan tepat waktu dalam kondisi bersih dan normal.',
        ]);

        Borrowing::create([
            'borrow_code' => 'PMJ-2026-0045',
            'user_id' => $siswa2->id,
            'equipment_id' => $eq2->id,
            'amount' => 1,
            'borrow_date' => now()->addDay()->toDateString(),
            'return_date' => now()->addDays(4)->toDateString(),
            'purpose' => 'Pemeriksaan osilasi rangkaian mikrokontroler.',
            'supervisor' => 'Hendra Setiawan, S.T.',
            'status' => 'menunggu',
        ]);

        Borrowing::create([
            'borrow_code' => 'PMJ-2026-0046',
            'user_id' => $siswa3->id,
            'equipment_id' => $eq3->id,
            'amount' => 2,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDay()->toDateString(),
            'purpose' => 'Titrasi asam kuat basa kuat larutan HCl.',
            'supervisor' => 'Ibu Rina Marlina, S.Pd',
            'status' => 'menunggu',
        ]);
    }
}
