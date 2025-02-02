<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AnggotaPenanganan
 *
 * @property int $id
 * @property int $user_id
 * @property int $kasus_id
 * @property string $peran
 * @property bool $aktif
 * @property string|null $alasan_dibatalkan
 * @property bool $selesai
 * @property Carbon|null $selesai_pada
 * @property int $poin_diperoleh
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Kasu $kasu
 * @property User $user
 * @property Collection|BuktiPenyelesaianKasu[] $bukti_penyelesaian_kasus
 *
 * @package App\Models
 */
class AnggotaPenanganan extends Model
{
	protected $table = 'anggota_penanganan';

	protected $casts = [
		'user_id' => 'int',
		'kasus_id' => 'int',
		'aktif' => 'bool',
		'selesai' => 'bool',
		'selesai_pada' => 'datetime',
		'poin_diperoleh' => 'int'
	];

	protected $fillable = [
		'user_id',
		'kasus_id',
		'peran',
		'aktif',
		'alasan_dibatalkan',
		'keterangan_admin',
		'selesai',
		'selesai_pada',
		'poin_diperoleh'
	];

	public function kasu()
	{
		return $this->belongsTo(Kasu::class, 'kasus_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function bukti_penyelesaian_kasus()
	{
		return $this->hasMany(BuktiPenyelesaianKasu::class);
	}
}
