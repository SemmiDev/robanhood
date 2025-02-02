<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PengaturanWebsite
 *
 * @property int $id
 * @property string|null $nama
 * @property string|null $deskripsi
 * @property string|null $tagline
 * @property string|null $logo
 * @property string|null $favicon
 * @property bool|null $izinkan_warga_daftar
 * @property bool|null $izinkan_polisi_daftar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PengaturanWebsite extends Model
{
	protected $table = 'pengaturan_website';

	protected $casts = [
		'izinkan_warga_daftar' => 'bool',
		'izinkan_polisi_daftar' => 'bool'
	];

	protected $fillable = [
		'nama',
		'deskripsi',
		'tagline',
		'logo',
		'favicon',
		'izinkan_warga_daftar',
		'izinkan_polisi_daftar',
        'radius_notifikasi'
	];
}
