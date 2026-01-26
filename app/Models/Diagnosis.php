<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'visit_id',
        'diagnosis_name',
        'diagnosis_code',
        'diagnosis_type',
        'notes',
        'diagnosed_at',
        'diagnosed_by_user_id',
    ];

    protected $casts = [
        'diagnosed_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
