<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasUuid;

    /**
     * Explicit table name to match migration.
     */
    protected $table = 'persons';

    protected $fillable = [
        'person_type',
        'adm_or_staff_no',
        'first_name',
        'last_name',
        'other_names',
        'gender',
        'dob',
        'phone',
        'email',
        'school_id',
        'campus_id',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get the person's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
