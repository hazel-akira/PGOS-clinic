<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChronicCondition extends Model
{
    use HasUuid;

    protected $fillable = [
        'person_id',
        'condition',
        'notes',
        'active',
        'recorded_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'recorded_at' => 'datetime',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
