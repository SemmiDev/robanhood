<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BuktiPenyelesaianKasu
 * 
 * @property int $id
 * @property int $anggota_penanganan_id
 * @property string $path
 * @property string|null $mime
 * @property int|null $size
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property AnggotaPenanganan $anggota_penanganan
 *
 * @package App\Models
 */
class BuktiPenyelesaianKasu extends Model
{
	protected $table = 'bukti_penyelesaian_kasus';

	protected $casts = [
		'anggota_penanganan_id' => 'int',
		'size' => 'int'
	];

	protected $fillable = [
		'anggota_penanganan_id',
		'path',
		'mime',
		'size',
		'keterangan'
	];

	public function anggota_penanganan()
	{
		return $this->belongsTo(AnggotaPenanganan::class);
	}
}
