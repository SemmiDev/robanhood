<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UnitPolisi
 * 
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ProfilPolisi[] $profil_polisis
 *
 * @package App\Models
 */
class UnitPolisi extends Model
{
	protected $table = 'unit_polisi';

	protected $fillable = [
		'nama',
		'deskripsi'
	];

	public function profil_polisis()
	{
		return $this->hasMany(ProfilPolisi::class, 'unit_id');
	}
}
