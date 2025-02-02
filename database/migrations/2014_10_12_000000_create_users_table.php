<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();

            $table->string('no_telepon')->nullable();
            $table->string('no_whatsapp')->nullable();
            $table->string('nik')->nullable();

            $table->text('foto_kk')->nullable();
            $table->text('foto_ktp')->nullable();

            $table->string('google_id')->nullable();
            $table->text('avatar')->nullable();
            $table->enum('peran', ['ADMIN', 'POLISI', 'WARGA'])->default('POLISI');
            $table->string('password');
            $table->boolean('acc')->default(false);
            $table->boolean('aktif')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();

            $table->enum('status', ['SEDANG_TIDAK_BERTUGAS', 'SEDANG_BERTUGAS', 'SEDANG_MENANGANI_KASUS'])->default('SEDANG_BERTUGAS');
            $table->decimal('latitude_terakhir', 18, 15)->nullable();
            $table->decimal('longitude_terakhir', 18, 15)->nullable();
            $table->timestamp('update_lokasi_terakhir')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->integer('total_poin')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
