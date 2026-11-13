<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BagianOrganisasi extends Model
{
    protected $table = 'bagian_organisasi';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'bagian_organisasi_id');
    }
}
