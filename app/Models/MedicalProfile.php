<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MedicalProfile extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'person_id',
        'blood_group',
        'allergies_summary',
        'chronic_conditions_summary',
        'special_needs_notes',
        'last_reviewed_at',
    ];

    protected $casts = [
        'last_reviewed_at' => 'datetime',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
