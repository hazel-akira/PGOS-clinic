<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consent extends Model
{
    use HasUuid;

    protected $fillable = [
        'person_id',
        'consent_type',
        'given_by',
        'relationship',
        'channel',
        'consent_text_version',
        'given_at',
        'expires_at',
        'evidence_attachment_id',
    ];

    protected $casts = [
        'given_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
