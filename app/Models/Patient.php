<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'type',
        'first_name',
        'last_name',
        'student_id',
        'staff_number',
        'date_of_birth',
        'gender',
        'class',
        'department',
        'phone',
        'email',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_relationship',
        'allergies',
        'chronic_conditions',
        'medical_history',
        'current_medications',
        'consent_first_aid',
        'consent_emergency_care',
        'consent_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'consent_date' => 'date',
        'consent_first_aid' => 'boolean',
        'consent_emergency_care' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the patient's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the patient's identifier (student_id or staff_number).
     */
    public function getIdentifierAttribute(): ?string
    {
        return $this->type === 'student' ? $this->student_id : $this->staff_number;
    }

    public function medicalProfile()
{
    return $this->hasOne(MedicalProfile::class);
}
public function visits()
{
    return $this->hasMany(Visit::class);
}
public function school()
{
    return $this->belongsTo(School::class);
}

   

    /**
     * Get the user who created this patient.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this patient.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
