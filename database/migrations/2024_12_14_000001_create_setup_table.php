<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetupTable extends Migration
{
    public function up()
    {
        Schema::create('pangkat_polisi', function (Blueprint $table) {
            $table->id();
            $table->string('grup');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('unit_polisi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('profil_polisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nrp');
            $table->foreignId('pangkat_id')->nullable()->constrained('pangkat_polisi')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('unit_id')->nullable()->constrained('unit_polisi')->nullOnDelete()->cascadeOnUpdate();
            $table->string('tempat_lahir')->nullable();
            $table->string('jabatan')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->string('agama')->nullable();
            $table->enum('status_pernikahan', ['LAJANG', 'MENIKAH', 'DUDA/JANDA'])->nullable();
            $table->string('spesialisasi')->nullable();
            $table->timestamps();
        });

        Schema::create('kategori_kasus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->text('simbol')->nullable();
            $table->longText('pengingat')->nullable();
            $table->timestamps();
        });

        Schema::create('kasus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelapor_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();;
            $table->foreignId('kategori_kasus_id')->nullable()->constrained('kategori_kasus')->nullOnDelete()->cascadeOnUpdate();;
            $table->string('judul');
            $table->enum('jenis', ['sos', 'kasus'])->default('kasus');
            $table->text('deskripsi')->nullable();
            $table->decimal('latitude', 18, 15)->nullable();
            $table->decimal('longitude', 18, 15)->nullable();
            $table->string('alamat')->nullable();
            $table->longText('feedback')->nullable();
            $table->enum('tingkat_keparahan', ['RINGAN', 'SEDANG', 'BERAT', 'LAINNYA'])->default('LAINNYA');
            $table->enum('status', ['MENUNGGU', 'DITUGASKAN', 'DALAM_PROSES', 'SELESAI', 'DITUTUP'])->default('MENUNGGU');
            $table->timestamp('selesai_pada')->nullable();
            $table->timestamp('waktu_respon')->nullable();
            $table->timestamp('waktu_kejadian')->nullable();
            $table->timestamps();
        });

        Schema::create('bukti_kasus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasus_id')->constrained('kasus')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('path');
            $table->string('mime')->nullable();
            $table->integer('size')->nullable()->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('anggota_penanganan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('kasus_id')->constrained('kasus')->onDelete('cascade');
            $table->enum('peran', ['KETUA', 'ANGGOTA'])->default('ANGGOTA');
            $table->boolean('aktif')->default(true); // menangani / tidak jadi menangani.
            $table->text('alasan_dibatalkan')->nullable(); // menangani / tidak jadi menangani.
            $table->text('keterangan_admin')->nullable(); // menangani / tidak jadi menangani.
            $table->boolean('selesai')->default(false);
            $table->timestamp('selesai_pada')->nullable();
            $table->integer('poin_diperoleh')->default(0);
            $table->timestamps();
        });

        Schema::create('bukti_penyelesaian_kasus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_penanganan_id')->constrained('anggota_penanganan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('path');
            $table->string('mime')->nullable();
            $table->integer('size')->nullable()->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('pengaturan_website', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->boolean('izinkan_warga_daftar')->nullable()->default(true);
            $table->boolean('izinkan_polisi_daftar')->nullable()->default(true);
            $table->integer('radius_notifikasi')->nullable()->default(1);
            $table->timestamps();
        });

        // kasus created -> load on maps -> leaflet detect 1 km radius -> upsert user by kasus (if diluar area, set in_area to false)
        // kirim jika hanya status nya = MENUNGGU
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasus_id')->constrained('kasus')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('push_notifikasi_terkirim')->nullable()->default(false);
            $table->enum('jenis', ['chat', 'penugasan', 'kasus_sekitar', 'verifikasi_penanganan'])->nullable();
            $table->text('pesan')->nullable();
            $table->float('jarak')->nullable();
            $table->boolean('read')->nullable()->default(false);
            $table->timestamps();
        });

        Schema::create('chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasus_id')->constrained('kasus')->onDelete('cascade'); // jadi id room
            $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade');
            $table->longText('pesan')->nullable();
            $table->boolean('dibaca')->nullable()->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anggota_penanganan');
        Schema::dropIfExists('bukti_penyelesaian_kasus');
        Schema::dropIfExists('bukti_kasus');
        Schema::dropIfExists('kasus');
        Schema::dropIfExists('kategori_kasus');
        Schema::dropIfExists('profil_polisi');
        Schema::dropIfExists('unit_polisi');
        Schema::dropIfExists('pangkat_polisi');
        Schema::dropIfExists('pengaturan_website');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('chat');
    }
};
