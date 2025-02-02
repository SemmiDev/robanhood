<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Chat
 * 
 * @property int $id
 * @property int $kasus_id
 * @property int $pengirim_id
 * @property string|null $pesan
 * @property bool|null $dibaca
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Kasu $kasu
 * @property User $user
 *
 * @package App\Models
 */
class Chat extends Model
{
	protected $table = 'chat';

	protected $casts = [
		'kasus_id' => 'int',
		'pengirim_id' => 'int',
		'dibaca' => 'bool'
	];

	protected $fillable = [
		'kasus_id',
		'pengirim_id',
		'pesan',
		'dibaca'
	];

	public function kasu()
	{
		return $this->belongsTo(Kasu::class, 'kasus_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'pengirim_id');
	}
}
