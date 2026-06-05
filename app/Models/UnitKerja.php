<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';

    protected $fillable = [
        'nama_unit',
        'kode_unit',
    ];

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }

    public function kepala(): HasMany
    {
        return $this->hasMany(Kepala::class);
    }
}
