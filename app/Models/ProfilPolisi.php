<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProfilPolisi
 *
 * @property int $id
 * @property int $user_id
 * @property string $nrp
 * @property int|null $pangkat_id
 * @property int|null $unit_id
 * @property string|null $tempat_lahir
 * @property Carbon|null $tanggal_lahir
 * @property string|null $jenis_kelamin
 * @property string|null $golongan_darah
 * @property string|null $agama
 * @property string|null $status_pernikahan
 * @property string|null $spesialisasi
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property PangkatPolisi|null $pangkat_polisi
 * @property UnitPolisi|null $unit_polisi
 * @property User $user
 *
 * @package App\Models
 */
class ProfilPolisi extends Model
{
	protected $table = 'profil_polisi';

	protected $casts = [
		'user_id' => 'int',
		'pangkat_id' => 'int',
		'unit_id' => 'int',
		'tanggal_lahir' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'nrp',
		'pangkat_id',
		'unit_id',
		'jabatan',
		'tempat_lahir',
		'tanggal_lahir',
		'jenis_kelamin',
		'golongan_darah',
		'agama',
		'status_pernikahan',
		'spesialisasi'
	];

	public function pangkat_polisi()
	{
		return $this->belongsTo(PangkatPolisi::class, 'pangkat_id');
	}

	public function unit_polisi()
	{
		return $this->belongsTo(UnitPolisi::class, 'unit_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
