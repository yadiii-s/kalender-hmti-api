<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\Event;
use App\Models\EventRundown;
use App\Models\DivisionWorkProgram;
use App\Models\DivisionHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Pimpinan Himpunan ───────────────────────────────────────

        $kahim = User::create([
            'nim'      => '210001',
            'name'     => 'Ahmad Fauzi Rahman',
            'email'    => 'kahim@hmj.ac.id',
            'password' => Hash::make('password'),
            'phone'    => '081234567890',
            'angkatan' => '2021',
            'jabatan'  => 'kahim',
            'status'   => 'aktif',
        ]);

        $wakahim = User::create([
            'nim'      => '210002',
            'name'     => 'Siti Nurhaliza',
            'email'    => 'wakahim@hmj.ac.id',
            'password' => Hash::make('password'),
            'phone'    => '081234567891',
            'angkatan' => '2021',
            'jabatan'  => 'wakahim',
            'status'   => 'aktif',
        ]);

        User::create([
            'nim'      => '210003',
            'name'     => 'Budi Santoso',
            'email'    => 'sekum1@hmj.ac.id',
            'password' => Hash::make('password'),
            'phone'    => '081234567892',
            'angkatan' => '2022',
            'jabatan'  => 'sekum1',
            'status'   => 'aktif',
        ]);

        User::create([
            'nim'      => '210004',
            'name'     => 'Ani Kusuma',
            'email'    => 'sekum2@hmj.ac.id',
            'password' => Hash::make('password'),
            'phone'    => '081234567893',
            'angkatan' => '2022',
            'jabatan'  => 'sekum2',
            'status'   => 'aktif',
        ]);

        User::create([
            'nim'      => '210005',
            'name'     => 'Rudi Hermawan',
            'email'    => 'bendum1@hmj.ac.id',
            'password' => Hash::make('password'),
            'phone'    => '081234567894',
            'angkatan' => '2022',
            'jabatan'  => 'bendum1',
            'status'   => 'aktif',
        ]);

        User::create([
            'nim'      => '210006',
            'name'     => 'Rina Permata',
            'email'    => 'bendum2@hmj.ac.id',
            'password' => Hash::make('password'),
            'phone'    => '081234567895',
            'angkatan' => '2022',
            'jabatan'  => 'bendum2',
            'status'   => 'aktif',
        ]);

        // ─── Kepala Divisi ────────────────────────────────────────────

        $divisons = Division::pluck('id', 'name');

        $kadivs = [
            ['nim' => '220001', 'name' => 'Rizki Maulana',   'email' => 'rizki.m@kampus.ac.id',  'divisi' => 'KWSB'],
            ['nim' => '220002', 'name' => 'Dewi Anjani',     'email' => 'dewi.a@kampus.ac.id',   'divisi' => 'Internal'],
            ['nim' => '220003', 'name' => 'Hendra Wijaya',   'email' => 'hendra.w@kampus.ac.id', 'divisi' => 'Eksternal'],
            ['nim' => '220004', 'name' => 'Sari Wulandari',  'email' => 'sari.w@kampus.ac.id',   'divisi' => 'Minbak'],
            ['nim' => '220005', 'name' => 'Bayu Setiawan',   'email' => 'bayu.s@kampus.ac.id',   'divisi' => 'Sosma'],
            ['nim' => '220006', 'name' => 'Nadia Pramita',   'email' => 'nadia.p@kampus.ac.id',  'divisi' => 'Infokom'],
            ['nim' => '220007', 'name' => 'Fajar Nugroho',   'email' => 'fajar.n@kampus.ac.id',  'divisi' => 'KWU'],
        ];

        $kadivUsers = [];
        foreach ($kadivs as $i => $data) {
            $user = User::create([
                'nim'      => $data['nim'],
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('password'),
                'phone'    => '08123456' . str_pad(900 + $i, 4, '0', STR_PAD_LEFT),
                'angkatan' => '2022',
                'jabatan'  => 'kadiv',
                'divisi'   => $data['divisi'],
                'status'   => 'aktif',
            ]);
            $kadivUsers[$data['divisi']] = $user;

            // Daftarkan sebagai anggota divisi dengan posisi Koordinator
            if (isset($divisons[$data['divisi']])) {
                DivisionMember::create([
                    'division_id' => $divisons[$data['divisi']],
                    'user_id'     => $user->id,
                    'position'    => 'Koordinator',
                    'batch'       => '2022',
                    'email'       => $user->email,
                    'phone'       => $user->phone,
                ]);
            }
        }

        // ─── Anggota Contoh ───────────────────────────────────────────

        $anggotaInternal = User::create([
            'nim'      => '230001',
            'name'     => 'Putri Rahayu',
            'email'    => 'anggota@kampus.ac.id',
            'password' => Hash::make('password'),
            'phone'    => '081234567903',
            'angkatan' => '2023',
            'jabatan'  => 'anggota',
            'divisi'   => 'Internal',
            'status'   => 'aktif',
        ]);

        if (isset($divisons['Internal'])) {
            DivisionMember::create([
                'division_id' => $divisons['Internal'],
                'user_id'     => $anggotaInternal->id,
                'position'    => 'Anggota',
                'batch'       => '2023',
                'email'       => $anggotaInternal->email,
                'phone'       => $anggotaInternal->phone,
            ]);
        }

        // ─── Contoh Event ─────────────────────────────────────────────

        $event1 = Event::create([
            'title'       => 'Rapat Pleno Evaluasi Semester Genap',
            'division'    => 'Internal',
            'pic'         => 'Dewi Anjani',
            'start_time'  => '2026-07-12 14:00:00',
            'end_time'    => '2026-07-12 16:00:00',
            'location'    => 'Ruang Rapat Utama, Gedung A',
            'status'      => 'Selesai',
            'description' => 'Evaluasi menyeluruh terhadap pelaksanaan program kerja semester genap.',
            'created_by'  => 'Dewi Anjani',
        ]);

        $event1->rundowns()->createMany([
            ['time' => '14:00', 'description' => 'Pembukaan & Doa',          'order' => 1],
            ['time' => '14:15', 'description' => 'Laporan Divisi Internal',   'order' => 2],
            ['time' => '15:00', 'description' => 'Diskusi & Evaluasi',        'order' => 3],
            ['time' => '15:50', 'description' => 'Kesimpulan & Penutup',      'order' => 4],
        ]);

        $event2 = Event::create([
            'title'       => 'Bakti Sosial & Donor Darah HMJ',
            'division'    => 'Sosma',
            'pic'         => 'Bayu Setiawan',
            'start_time'  => '2026-08-20 08:00:00',
            'end_time'    => '2026-08-20 14:00:00',
            'location'    => 'Aula Kampus Utama',
            'status'      => 'Mendatang',
            'description' => 'Kegiatan bakti sosial dan donor darah untuk mahasiswa dan masyarakat sekitar.',
            'created_by'  => 'Bayu Setiawan',
        ]);

        $event2->rundowns()->createMany([
            ['time' => '08:00', 'description' => 'Registrasi Peserta',          'order' => 1],
            ['time' => '08:30', 'description' => 'Pembukaan & Sambutan',        'order' => 2],
            ['time' => '09:00', 'description' => 'Kegiatan Donor Darah',        'order' => 3],
            ['time' => '12:00', 'description' => 'Ishoma',                      'order' => 4],
            ['time' => '13:00', 'description' => 'Bakti Sosial Lingkungan',     'order' => 5],
            ['time' => '14:00', 'description' => 'Penutupan',                   'order' => 6],
        ]);

        $event3 = Event::create([
            'title'       => 'Workshop Kewirausahaan Mahasiswa 2026',
            'division'    => 'KWU',
            'pic'         => 'Fajar Nugroho',
            'start_time'  => '2026-09-05 09:00:00',
            'end_time'    => '2026-09-05 17:00:00',
            'location'    => 'Aula Gedung B, Lantai 3',
            'status'      => 'Mendatang',
            'description' => 'Workshop intensif kewirausahaan dengan narasumber dari praktisi industri.',
            'created_by'  => 'Fajar Nugroho',
        ]);

        // ─── Contoh Work Programs ─────────────────────────────────────

        if (isset($divisons['Internal'])) {
            DivisionWorkProgram::create([
                'division_id' => $divisons['Internal'],
                'name'        => 'Kaderisasi Anggota Baru',
                'date'        => 'September 2026',
                'pic'         => 'Dewi Anjani',
                'status'      => 'Mendatang',
                'progress'    => 10,
            ]);
            DivisionWorkProgram::create([
                'division_id' => $divisons['Internal'],
                'name'        => 'Gathering Himpunan',
                'date'        => 'Juli 2026',
                'pic'         => 'Dewi Anjani',
                'status'      => 'Selesai',
                'progress'    => 100,
            ]);
        }

        if (isset($divisons['KWU'])) {
            DivisionWorkProgram::create([
                'division_id' => $divisons['KWU'],
                'name'        => 'Workshop Kewirausahaan',
                'date'        => 'September 2026',
                'pic'         => 'Fajar Nugroho',
                'status'      => 'Mendatang',
                'progress'    => 30,
            ]);
        }

        // ─── Contoh Division Histories ────────────────────────────────

        if (isset($divisons['Sosma'])) {
            DivisionHistory::create([
                'division_id' => $divisons['Sosma'],
                'name'        => 'Bakti Sosial Semester Genap 2025',
                'date'        => 'Januari 2025',
                'status'      => 'Selesai',
                'icon'        => 'bi-heart-fill',
            ]);
        }

        $this->command->info('✅ UserSeeder: Users, members, events, dan work programs berhasil dibuat.');
    }
}
