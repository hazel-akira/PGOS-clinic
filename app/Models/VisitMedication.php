<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitMedication extends Model
{
    use HasUuid;

    protected $fillable = [
        'visit_id',
        'medication_id',
        'inventory_id',
        'dosage',
        'frequency',
        'quantity_issued',
        'instructions',
        'issued_at',
        'issued_by',
        'notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'quantity_issued' => 'integer',
    ];

    /**
     * Get the visit for this medication issue.
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the medication that was issued.
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Get the inventory batch that was used.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Get the user who issued this medication.
     */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
