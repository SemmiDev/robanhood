<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

	protected $table = 'users';

	protected $casts = [
		'aktif' => 'bool',
		'acc' => 'bool',
		'email_verified_at' => 'datetime',
		'latitude_terakhir' => 'float',
		'longitude_terakhir' => 'float',
		'update_lokasi_terakhir' => 'datetime',
		'last_login' => 'datetime',
		'total_poin' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'no_telepon',
		'no_whatsapp',
		'nik',
		'google_id',
		'avatar',
		'peran',
		'onesignal_id',
		'password',
		'aktif',
		'acc',
		'email_verified_at',
		'remember_token',
		'status',
		'latitude_terakhir',
		'longitude_terakhir',
		'update_lokasi_terakhir',
        'last_login',
		'total_poin',
        'foto_kk',
        'foto_ktp',
	];

	public function anggota_penanganans()
	{
		return $this->hasMany(AnggotaPenanganan::class);
	}

	public function kasus()
	{
		return $this->hasMany(Kasu::class, 'pelapor_id');
	}

	public function profil_polisis()
	{
		return $this->hasMany(ProfilPolisi::class);
	}
}
