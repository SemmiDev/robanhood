<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class KategoriKasu
 *
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property string|null $path_simbol
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Kasu[] $kasus
 *
 * @package App\Models
 */
class KategoriKasu extends Model
{
	protected $table = 'kategori_kasus';

	protected $fillable = [
		'nama',
		'deskripsi',
		'simbol',
		'pengingat',
	];

	public function kasus()
	{
		return $this->hasMany(Kasu::class, 'kategori_kasus_id');
	}
}
