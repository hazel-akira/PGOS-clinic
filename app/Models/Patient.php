<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Patient extends Model
{
    use HasUuids;

    protected $fillable = [
        'patient_type',
        'external_id',
        'external_ref',
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

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function visits()
    {
        return $this->hasMany(Visit::class)->latest('visited_at');
    }

}
