<?php

namespace Database\Seeders;

use App\Models\AnggotaPenanganan;
use App\Models\Kasu;
use App\Models\KategoriKasu;
use App\Models\PangkatPolisi;
use App\Models\PengaturanWebsite;
use App\Models\UnitPolisi;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $pangkatPolisi = [
            [
                'grup' => 'Perwira Tinggi (Pati)',
                'nama' => 'Jenderal Polisi',
                'deskripsi' => 'Lambang pangkat 4 bintang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Perwira Tinggi (Pati)',
                'nama' => 'Komisaris Jenderal Polisi (Komjen Pol)',
                'deskripsi' => 'Lambang pangkat 3 bintang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Perwira Tinggi (Pati)',
                'nama' => 'Inspektur Jenderal Polisi (Irjen Pol)',
                'deskripsi' => 'Lambang pangkat 2 bintang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Perwira Tinggi (Pati)',
                'nama' => 'Brigadir Jenderal Polisi (Brigjen Pol)',
                'deskripsi' => 'Lambang pangkat 1 bintang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Perwira Menengah (Pamen)',
                'nama' => 'Komisaris Besar Polisi (Kombes Pol)',
                'deskripsi' => 'Lambang pangkat 3 melati',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Perwira Menengah (Pamen)',
                'nama' => 'Ajun Komisaris Besar Polisi (AKBP)',
                'deskripsi' => 'Lambang pangkat 2 melati',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Perwira Menengah (Pamen)',
                'nama' => 'Komisaris Polisi (Kompol)',
                'deskripsi' => 'Lambang pangkat 1 melati',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Perwira Pertama (Pama)',
                'nama' => 'Ajun Komisaris Polisi (AKP)',
                'deskripsi' => 'Lambang pangkat 3 balok emas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Perwira Pertama (Pama)',
                'nama' => 'Inspektur Polisi Satu (Iptu)',
                'deskripsi' => 'Lambang pangkat 2 balok emas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Perwira Pertama (Pama)',
                'nama' => 'Inspektur Polisi Dua (Ipda)',
                'deskripsi' => 'Lambang pangkat 1 balok emas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Bintara Tinggi',
                'nama' => 'Ajun Inspektur Polisi Satu (Aiptu)',
                'deskripsi' => 'Lambang pangkat 2 balok bergelombang perak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Bintara Tinggi',
                'nama' => 'Ajun Inspektur Polisi Dua (Aipda)',
                'deskripsi' => 'Lambang pangkat 1 balok bergelombang perak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Bintara',
                'nama' => 'Brigadir Polisi Kepala (Bripka)',
                'deskripsi' => 'Lambang pangkat 4 balok panah perak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Bintara',
                'nama' => 'Brigadir Polisi (Brigpol)',
                'deskripsi' => 'Lambang pangkat 3 balok panah perak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Bintara',
                'nama' => 'Brigadir Polisi Satu (Briptu)',
                'deskripsi' => 'Lambang pangkat 2 balok panah perak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Bintara',
                'nama' => 'Brigadir Polisi Dua (Bripda)',
                'deskripsi' => 'Lambang pangkat 1 balok panah perak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Tamtama',
                'nama' => 'Ajun Brigadir Polisi (Abrip)',
                'deskripsi' => 'Lambang pangkat 3 balok panah merah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Tamtama',
                'nama' => 'Ajun Brigadir Polisi (Abriptu)',
                'deskripsi' => 'Lambang pangkat 2 balok panah merah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Tamtama',
                'nama' => 'Ajun Brigadir Polisi (Abripda)',
                'deskripsi' => 'Lambang pangkat 1 balok panah merah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Tamtama',
                'nama' => 'Bhayangkara Kepala (Bharaka)',
                'deskripsi' => 'Lambang pangkat 3 balok miring merah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Tamtama',
                'nama' => 'Bhayangkara Satu (Bharatu)',
                'deskripsi' => 'Lambang pangkat 2 balok miring merah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Tamtama',
                'nama' => 'Bhayangkara Dua (Bharada)',
                'deskripsi' => 'Lambang pangkat 1 balok miring merah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grup' => 'Lainnya',
                'nama' => 'Lainnya',
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($pangkatPolisi as $pangkat) {
            if (!PangkatPolisi::where('grup', $pangkat['grup'])->where('nama', $pangkat['nama'])->exists()) {
                PangkatPolisi::create($pangkat);
            }
        }

        $unitPolisi = [
            [
                'nama' => 'Brimob (Korps Brigade Mobil)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Densus 88 AT (Detasemen Khusus 88 Anti Teror)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Satbrimob (Satuan Brigade Mobil)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Gegana (Pasukan Gegana Anti Teror)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sabhara (Samapta Bhayangkara)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Polair (Polisi Air)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Polwan (Polisi Wanita)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Lantas (Lalu Lintas)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Reskrim (Reserse Kriminal)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Jatanras (Kejahatan dan Kekerasan)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Resnarkoba (Reserse Narkoba)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Intelkam (Intelijen Keamanan)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Binmas (Pembinaan Masyarakat)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Propam (Profesi dan Pengamanan)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Satreskrim (Satuan Reserse Kriminal)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Satpamobvit (Satuan Pengamanan Objek Vital)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Satreskoba (Satuan Reserse Narkoba)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sattipol (Satuan Teknis Kepolisian)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Polsus (Polisi Khusus)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Jihandak (Penjinak Bahan Peledak)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($unitPolisi as $unit) {
            if (!UnitPolisi::where('nama', $unit['nama'])->exists()) {
                UnitPolisi::create($unit);
            }
        }

        $kategoriKasus = [
            [
                'nama' => 'Kecelakaan',
                'deskripsi' => 'Kecelakaan kendaraan bermotor',
                'simbol' => 'kategori_kasus/simbol/kecelakaan.png',
                'pengingat' => 'Hati-hati berkendara, pastikan kondisi kendaraan dalam keadaan baik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tabrakan',
                'deskripsi' => 'Tabrakan kendaraan di jalan raya',
                'simbol' => 'kategori_kasus/simbol/tabrakan.png',
                'pengingat' => 'Patuhi rambu lalu lintas demi keselamatan bersama.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kebakaran Hutan',
                'deskripsi' => 'Kebakaran yang merusak kawasan hutan',
                'simbol' => 'kategori_kasus/simbol/kebakaran-hutan.png',
                'pengingat' => 'Jaga kelestarian hutan, hindari membuang puntung rokok sembarangan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kejahatan Cyber',
                'deskripsi' => 'Tindak pidana di dunia maya',
                'simbol' => 'kategori_kasus/simbol/kejahatan-cyber.png',
                'pengingat' => 'Lindungi data pribadi dan waspada terhadap penipuan online.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kekerasan Seksual',
                'deskripsi' => 'Tindak pidana pelecehan dan kekerasan seksual',
                'simbol' => 'kategori_kasus/simbol/kekerasan-seksual.png',
                'pengingat' => 'Hormati hak dan martabat setiap individu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Narkoba',
                'deskripsi' => 'Tindak pidana narkotika dan zat adiktif',
                'simbol' => 'kategori_kasus/simbol/narkoba.png',
                'pengingat' => 'Hindari narkoba, pilih hidup sehat dan produktif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pembunuhan',
                'deskripsi' => 'Tindak pidana pembunuhan berencana',
                'simbol' => 'kategori_kasus/simbol/pembunuhan.png',
                'pengingat' => 'Selesaikan masalah dengan kepala dingin, tanpa kekerasan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pencurian',
                'deskripsi' => 'Tindak pidana pengambilan barang milik orang lain',
                'simbol' => 'kategori_kasus/simbol/pencurian.png',
                'pengingat' => 'Jaga keamanan barang pribadi dan lingkungan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Perkelahian',
                'deskripsi' => 'Tindak kekerasan antar individu atau kelompok',
                'simbol' => 'kategori_kasus/simbol/perkelahian.png',
                'pengingat' => 'Hindari konflik, komunikasikan perbedaan dengan santun.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Lain-lain',
                'deskripsi' => 'Kasus tidak terklasifikasi atau khusus',
                'simbol' => 'kategori_kasus/simbol/warning.png',
                'pengingat' => 'Tetap waspada dan junjung tinggi hukum.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($kategoriKasus as $kategori) {
            if (!KategoriKasu::where('nama', $kategori['nama'])->exists()) {
                KategoriKasu::create($kategori);
            }
        }

        $users = [
            [
                'name' => "Admin",
                'email' => 'admin@gmail.com',
                'peran' => 'ADMIN',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => "Dr RK",
                'email' => 'dr.rk@gmail.com',
                'peran' => 'POLISI',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => "Sammi",
                'email' => 'sammi@gmail.com',
                'peran' => 'POLISI',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => "Fulan",
                'email' => 'fulan@gmail.com',
                'peran' => 'POLISI',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => "John",
                'email' => 'john@gmail.com',
                'peran' => 'WARGA',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
        ];

        $pangkatJendralPolisi = PangkatPolisi::where('nama', 'Jenderal Polisi')->first();
        $grupBrimob = UnitPolisi::where('nama', 'Brimob (Korps Brigade Mobil)')->first();

        $loc = [
            [0.4717940190119853, 101.38051440262034], // ARFA UNNAS
            [0.4767079080856403, 101.3831352269725], // Jembatan kupu kupu
            [0.4817388776171682, 101.37919198717469], // UPT Faperta
            [0.47478813488733407, 101.3763943475559], // FEB
            [0.47478813488733407, 101.3763943475559], // FEB
        ];

        foreach ($users as $key => $user) {
            if (!User::where('name', $user['name'])->where('email', $user['email'])->exists()) {
                $user = User::create($user);

                $user->latitude_terakhir = $loc[$key][0];
                $user->longitude_terakhir = $loc[$key][1];
                $user->update_lokasi_terakhir = now();
                $user->total_poin = 0;
                $user->acc = 1;
                $user->save();

                if ($user->peran == "ADMIN" || $user->peran == "POLISI") {
                    $user->profil_polisis()->create([
                        'nrp' => self::randomNRP(),
                        'pangkat_id' => $pangkatJendralPolisi->id,
                        'unit_id' => $grupBrimob->id,
                        'jenis_kelamin' => 'L',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // generate kasus
        $kategoriKasusKecelakaan = KategoriKasu::where('nama', '=', 'Kecelakaan')->first();

        $listKasus = [
            [
                'kategori_kasus_id' => $kategoriKasusKecelakaan->id,
                'pelapor_id' => 5, // john
                'judul' => 'Kecelakaan Bus',
                'jenis' => 'kasus',
                'deskripsi' => 'Kecelakaan Bus',
                'tingkat_keparahan' => 'BERAT',
                'latitude' => '0.478814817076407', // FAKULTAS PERTANIAN UNRI
                'longitude' => '101.37831648045345',
                'waktu_kejadian' => now()
            ]
        ];

        $fotoKasus = [
            'kecelakaan.png',
        ];

        foreach ($listKasus as $index => $kasus) {
            try {
                $kasus = Kasu::create($kasus);
                foreach ($fotoKasus as $foto) {
                    $foto = 'bukti_kasus/' . $foto;
                    $kasus->bukti_kasus()->create([
                        'path' => $foto,
                        'mime' => 'image/png',
                        'size' => 100,
                    ]);
                }
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        // pengaturan website
        PengaturanWebsite::create([
            'nama' => 'Robanhood',
            'deskripsi' => 'Real-Time Crime Reporting System',
            'tagline' => 'Tanggap Laporan, Tuntas Penanganan',
            'logo' => 'pengaturan-website/logo.webp',
            'favicon' => 'pengaturan-website/logo.webp',
            'izinkan_polisi_daftar' => true,
            'izinkan_warga_daftar' => true,
            'radius_notifikasi'=> 1,
        ]);
    }

    public static function randomNRP()
    {
        // Menggunakan fungsi bawaan PHP untuk menghasilkan angka acak sebanyak 10 digit
        $nrp = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);

        return $nrp;
    }
}
