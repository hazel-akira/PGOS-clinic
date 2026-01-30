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
        'stock_batch_id',
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

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * Visit where the medication was issued.
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Medication that was issued.
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Exact batch used to issue this medication.
     * This enables expiry, supplier & recall tracking.
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'stock_batch_id');
    }

    /**
     * User who issued the medication.
     */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
