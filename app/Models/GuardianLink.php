<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardianLink extends Model
{
    use HasUuid;

    protected $fillable = [
        'student_person_id',
        'guardian_id',
        'is_primary',
        'notes',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'student_person_id');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class);
    }
}
