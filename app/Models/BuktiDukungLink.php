<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiDukungLink extends Model
{
    protected $table = 'bukti_dukung_link';

    protected $fillable = [
        'indikator_kinerja_id',
        'judul_bukti',
        'link_bukti',
    ];

    public function indikatorKinerja(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerja::class);
    }
}
