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
     * Get the guardians linked to this person (student).
     */
    public function guardians()
    {
        return $this->belongsToMany(
            Guardian::class,
            'guardian_links',
            'student_person_id',
            'guardian_id'
        )->withPivot('is_primary', 'notes')->withTimestamps();
    }

    /**
     * Get the guardian links for this person.
     */
    public function guardianLinks(): HasMany
    {
        return $this->hasMany(GuardianLink::class, 'student_person_id');
    }

    /**
     * Get all allergies for this person.
     */
    public function allergies(): HasMany
    {
        return $this->hasMany(Allergy::class);
    }

    /**
     * Get all chronic conditions for this person.
     */
    public function chronicConditions(): HasMany
    {
        return $this->hasMany(ChronicCondition::class);
    }

    /**
     * Get the medical profile for this person.
     */
    public function medicalProfile()
    {
        return $this->hasOne(MedicalProfile::class);
    }

    /**
     * Get all consents for this person.
     */
    public function consents(): HasMany
    {
        return $this->hasMany(Consent::class);
    }

    /**
     * Get the student enrolment (for students only).
     */
    public function enrolment()
    {
        return $this->hasOne(StudentEnrolment::class);
    }

    /**
     * Get the person's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Calculate age from date of birth.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }
}
