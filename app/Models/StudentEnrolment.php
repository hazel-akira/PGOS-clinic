<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEnrolment extends Model
{
    use HasUuid;

    protected $fillable = [
        'person_id',
        'class_id',
        'stream',
        'boarding_status',
        'guardian_primary_id',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class, 'guardian_primary_id');
    }
}
