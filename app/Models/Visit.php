<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Visit extends Model
{
    use HasUuids;

    protected $fillable = [
        'patient_id',
        'visited_at',
        'reason',
        'notes',
        'outcome',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
