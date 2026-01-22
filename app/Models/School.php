<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasUuid;

    protected $fillable = [
        'name',
        'code',
        'website',
        'phone',
        'address',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Persons (students/staff/visitors) associated with this school.
     */
    public function persons(): HasMany
    {
        return $this->hasMany(Person::class);
    }
}
