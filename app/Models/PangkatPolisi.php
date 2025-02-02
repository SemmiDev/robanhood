<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PangkatPolisi
 * 
 * @property int $id
 * @property string $grup
 * @property string $nama
 * @property string|null $deskripsi
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ProfilPolisi[] $profil_polisis
 *
 * @package App\Models
 */
class PangkatPolisi extends Model
{
	protected $table = 'pangkat_polisi';

	protected $fillable = [
		'grup',
		'nama',
		'deskripsi'
	];

	public function profil_polisis()
	{
		return $this->hasMany(ProfilPolisi::class, 'pangkat_id');
	}
}
