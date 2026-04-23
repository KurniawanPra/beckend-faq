<?php

namespace Database\Seeders;

use App\Models\PertanyaanFromUser;
use App\Models\Pertanyaan;
use App\Models\Topik;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ─────────────────────────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin PT INL',
                'password' => Hash::make('admin123'),
            ]
        );

        // ─── Topik ─────────────────────────────────────────────────────────────
        $topikData = [
            ['nama' => 'Akun & Registrasi'],
            ['nama' => 'Password & Keamanan'],
            ['nama' => 'Pembayaran & Tagihan'],
            ['nama' => 'Layanan & Fitur'],
            ['nama' => 'Kebijakan & Privasi'],
            ['nama' => 'Teknis & Troubleshooting'],
        ];

        $topiks = [];
        foreach ($topikData as $data) {
            $topiks[] = Topik::updateOrCreate(['nama' => $data['nama']]);
        }

        [$akun, $password, $bayar, $layanan, $kebijakan, $teknis] = $topiks;

        // ─── Pertanyaan ────────────────────────────────────────────────────────
        $pertanyaanData = [
            [
                'topik_id' => $akun->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Bagaimana cara mendaftarkan akun baru?',
                'jawaban_admin' => 'Klik tombol "Daftar" di pojok kanan atas halaman. Isi formulir dengan data diri yang valid, lalu verifikasi email Anda melalui tautan yang dikirimkan.',
            ],
            [
                'topik_id' => $akun->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Apakah data karyawan bisa diedit setelah terdaftar?',
                'jawaban_admin' => 'Ya. Masuk ke menu Profil, pilih Edit Profil, ubah data yang diperlukan, lalu simpan perubahan. Perubahan NIK atau nama memerlukan verifikasi HRD.',
            ],
            [
                'topik_id' => $akun->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Berapa lama proses aktivasi akun?',
                'jawaban_admin' => 'Aktivasi akun dilakukan dalam 1x24 jam kerja setelah data lengkap dan terverifikasi oleh HRD.',
            ],
            [
                'topik_id' => $password->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Bagaimana cara mereset password?',
                'jawaban_admin' => 'Klik "Lupa Password" di halaman login, masukkan email terdaftar Anda, dan ikuti tautan yang dikirimkan ke email untuk membuat password baru.',
            ],
            [
                'topik_id' => $password->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Apa syarat password yang aman?',
                'jawaban_admin' => 'Password minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter spesial. Jangan gunakan tanggal lahir atau nama sendiri.',
            ],
            [
                'topik_id' => $bayar->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Metode pembayaran apa saja yang diterima?',
                'jawaban_admin' => 'Kami menerima transfer bank (BCA, Mandiri, BNI, BRI), kartu kredit/debit, dan dompet digital (GoPay, OVO, Dana).',
            ],
            [
                'topik_id' => $bayar->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Bagaimana cara mengajukan reimbursement?',
                'jawaban_admin' => 'Unduh formulir reimbursement di menu HR Tools, isi dengan lengkap, lampirkan bukti pengeluaran, lalu ajukan melalui sistem sebelum tanggal 25 setiap bulan.',
            ],
            [
                'topik_id' => $layanan->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Fitur apa saja yang tersedia untuk karyawan HRD?',
                'jawaban_admin' => 'Tersedia fitur manajemen absensi, pengajuan cuti online, penggajian, penilaian kinerja, rekrutmen, dan laporan HR. Semua dapat diakses dari dashboard.',
            ],
            [
                'topik_id' => $layanan->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Bagaimana cara mengajukan cuti?',
                'jawaban_admin' => 'Buka menu Cuti > Ajukan Cuti, pilih jenis dan tanggal cuti, tambahkan keterangan jika perlu, lalu submit. Atasan langsung akan mendapat notifikasi untuk persetujuan.',
            ],
            [
                'topik_id' => $kebijakan->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Bagaimana kebijakan privasi data karyawan?',
                'jawaban_admin' => 'Data karyawan dilindungi sesuai UU No. 27 Tahun 2022 tentang Perlindungan Data Pribadi. Data hanya digunakan untuk keperluan operasional HR dan tidak dibagikan ke pihak ketiga.',
            ],
            [
                'topik_id' => $teknis->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Aplikasi tidak bisa dibuka, apa yang harus dilakukan?',
                'jawaban_admin' => 'Coba langkah berikut: (1) Clear cache browser, (2) Gunakan browser Chrome/Firefox versi terbaru, (3) Nonaktifkan ekstensi yang mungkin mengganggu, (4) Hubungi IT Support jika masalah berlanjut.',
            ],
            [
                'topik_id' => $teknis->id,
                'user_id' => $admin->id,
                'pertanyaan' => 'Bagaimana cara menghubungi IT Support?',
                'jawaban_admin' => 'IT Support dapat dihubungi melalui: Email it-support@inl.co.id, WhatsApp 0812-XXXX-XXXX (jam kerja 08.00–17.00 WIB), atau buka tiket di menu Help Center dalam aplikasi.',
            ],
        ];

        foreach ($pertanyaanData as $data) {
            Pertanyaan::updateOrCreate(
                ['pertanyaan' => $data['pertanyaan']],
                $data
            );
        }

        // ─── Contoh Inquiry dari User ──────────────────────────────────────────
        $inquiryData = [
            [
                'nama_user' => 'Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'jabatan' => 'Staff HRD',
                'bagian' => 'Administrasi Kepegawaian',
                'pertanyaan' => 'Apakah ada batas waktu pengajuan cuti tahunan? Saya sudah bekerja selama 2 tahun tapi belum pernah mengambil cuti.',
                'jawaban_melalui' => 'email',
                'status' => false,
            ],
            [
                'nama_user' => 'Siti Rahayu',
                'email' => 'siti.rahayu@perusahaan.com',
                'jabatan' => 'Manajer Rekrutmen',
                'bagian' => 'HR Development',
                'pertanyaan' => 'Bagaimana prosedur pengajuan kandidat dari luar kota? Apakah ada penggantian biaya transportasi untuk proses interview?',
                'jawaban_melalui' => 'whatsapp',
                'status' => true,
            ],
            [
                'nama_user' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@inl.co.id',
                'jabatan' => 'Supervisor Payroll',
                'bagian' => 'Kompensasi & Benefit',
                'pertanyaan' => 'Kapan batas akhir input lembur bulan ini? Sistem sepertinya tidak menerima input setelah tanggal 20.',
                'jawaban_melalui' => 'telepon',
                'status' => false,
            ],
        ];

        foreach ($inquiryData as $data) {
            PertanyaanFromUser::firstOrCreate(
                ['email' => $data['email'], 'pertanyaan' => $data['pertanyaan']],
                $data
            );
        }
    }
}
