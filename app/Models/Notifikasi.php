<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notifikasi
 *
 * @property int $id
 * @property int $kasus_id
 * @property int $user_id
 * @property bool|null $push_notifikasi_terkirim
 * @property float|null $jarak
 * @property bool|null $read
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Kasu $kasu
 * @property User $user
 *
 * @package App\Models
 */
class Notifikasi extends Model
{
	protected $table = 'notifikasi';

	protected $casts = [
		'kasus_id' => 'int',
		'user_id' => 'int',
		'push_notifikasi_terkirim' => 'bool',
		'jarak' => 'float',
		'read' => 'bool'
	];

	protected $fillable = [
		'kasus_id',
		'user_id',
		'push_notifikasi_terkirim',
		'pesan',
        'jenis',
		'jarak',
		'read'
	];

	public function kasu()
	{
		return $this->belongsTo(Kasu::class, 'kasus_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
