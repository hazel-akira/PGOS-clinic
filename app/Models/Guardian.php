<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guardian extends Model
{
    use HasUuid;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'email',
        'relationship',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guardianLinks(): HasMany
    {
        return $this->hasMany(GuardianLink::class);
    }

    /**
     * Get all students linked to this guardian.
     */
    public function students()
    {
        return $this->hasManyThrough(
            Person::class,
            GuardianLink::class,
            'guardian_id',
            'id',
            'id',
            'student_person_id'
        );
    }
}
