<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Kasu
 *
 * @property int $id
 * @property int|null $pelapor_id
 * @property int|null $kategori_kasus_id
 * @property string $judul
 * @property string|null $deskripsi
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $alamat
 * @property string $tingkat_keparahan
 * @property string $status
 * @property Carbon|null $selesai_pada
 * @property Carbon|null $waktu_respon
 * @property Carbon|null $waktu_kejadian
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property KategoriKasu|null $kategori_kasu
 * @property User|null $user
 * @property Collection|AnggotaPenanganan[] $anggota_penanganans
 * @property Collection|BuktiKasu[] $bukti_kasus
 *
 * @package App\Models
 */
class Kasu extends Model
{
	protected $table = 'kasus';

	protected $casts = [
		'pelapor_id' => 'int',
		'kategori_kasus_id' => 'int',
		'latitude' => 'float',
		'longitude' => 'float',
		'selesai_pada' => 'datetime',
		'waktu_respon' => 'datetime',
		'waktu_kejadian' => 'datetime'
	];

	protected $fillable = [
		'pelapor_id',
		'kategori_kasus_id',
		'judul',
		'jenis',
		'deskripsi',
		'latitude',
		'longitude',
		'alamat',
		'tingkat_keparahan',
		'status',
		'feedback',
		'selesai_pada',
		'waktu_respon',
		'waktu_kejadian'
	];

	public function kategori_kasu()
	{
		return $this->belongsTo(KategoriKasu::class, 'kategori_kasus_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'pelapor_id');
	}

	public function anggota_penanganans()
	{
		return $this->hasMany(AnggotaPenanganan::class, 'kasus_id');
	}

	public function bukti_kasus()
	{
		return $this->hasMany(BuktiKasu::class, 'kasus_id');
	}

    public function chats()
	{
		return $this->hasMany(Chat::class, 'kasus_id');
	}
}
