<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BuktiKasu
 *
 * @property int $id
 * @property int $kasus_id
 * @property string $path
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Kasu $kasu
 *
 * @package App\Models
 */
class BuktiKasu extends Model
{
	protected $table = 'bukti_kasus';

	protected $casts = [
		'kasus_id' => 'int'
	];

	protected $fillable = [
		'kasus_id',
		'path',
		'mime',
		'size',
		'keterangan'
	];

	public function kasu()
	{
		return $this->belongsTo(Kasu::class, 'kasus_id');
	}
}
