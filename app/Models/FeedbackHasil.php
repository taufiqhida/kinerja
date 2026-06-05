<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackHasil extends Model
{
    protected $table = 'feedback_hasil';

    protected $fillable = [
        'indikator_kinerja_id',
        'kepala_id',
        'isi_feedback',
    ];

    public function indikatorKinerja(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerja::class);
    }

    public function kepala(): BelongsTo
    {
        return $this->belongsTo(Kepala::class);
    }
}
