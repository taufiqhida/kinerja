<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerilakuMaster extends Model
{
    protected $table = 'perilaku_master';

    protected $fillable = [
        'nama_perilaku',
        'urutan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function indikator(): HasMany
    {
        return $this->hasMany(PerilakuIndikator::class)->orderBy('urutan');
    }

    public function penilaianPerilaku(): HasMany
    {
        return $this->hasMany(PenilaianPerilaku::class);
    }
}
