<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peran extends Model
{
    protected $table = 'peran';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'peran_id');
    }
}
