<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'person_id',
        'school_id',
        'incident_type',
        'occurred_at',
        'location',
        'description',
        'severity',
        'is_emergency',
        'linked_visit_id',
        'reported_by_user_id',
        'actions_taken',
        'parents_notified',
        'parents_notified_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'parents_notified_at' => 'datetime',
        'is_emergency' => 'boolean',
        'parents_notified' => 'boolean',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function linkedVisit(): BelongsTo
    {
        return $this->belongsTo(Visit::class, 'linked_visit_id');
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }
}
