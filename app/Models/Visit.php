<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'person_id',
        'school_id',
        'arrival_at',
        'departure_at',
        'visit_type',
        'triage_level',
        'chief_complaint',
        'history_notes',
        'assessment_notes',
        'disposition',
        'created_by_user_id',
    ];

    protected $casts = [
        'arrival_at' => 'datetime',
        'departure_at' => 'datetime',
    ];

    /**
     * Get the person (patient) for this visit.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * Get the user who created this visit.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get all prescriptions for this visit.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get all vitals for this visit.
     */
    public function vitals(): HasMany
    {
        return $this->hasMany(Vital::class);
    }

    /**
     * Get all diagnoses for this visit.
     */
    public function diagnoses(): HasMany
    {
        return $this->hasMany(Diagnosis::class);
    }

    /**
     * Get all treatments for this visit.
     */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    /**
     * Check if visit is currently active (checked in but not discharged).
     */
    public function isActive(): bool
    {
        return $this->departure_at === null;
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
