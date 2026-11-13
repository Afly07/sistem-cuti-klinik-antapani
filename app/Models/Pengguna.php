<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $guarded = ['id'];
    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function peran()
    {
        return $this->belongsTo(Peran::class, 'peran_id');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'pengguna_id');
    }

    public function hasRole(string|array $kode): bool
    {
        $peranKode = $this->peran->kode ?? '';
        return is_array($kode) ? in_array($peranKode, $kode, true) : $peranKode === $kode;
    }
}
