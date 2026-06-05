<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerilakuIndikator extends Model
{
    protected $table = 'perilaku_indikator';

    protected $fillable = [
        'perilaku_master_id',
        'deskripsi_indikator',
        'urutan',
    ];

    public function perilakuMaster(): BelongsTo
    {
        return $this->belongsTo(PerilakuMaster::class);
    }
}
