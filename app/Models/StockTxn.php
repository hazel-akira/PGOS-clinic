<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTxn extends Model
{
    use HasUuid;

    protected $fillable = [
        'txn_type',
        'batch_id',
        'qty',
        'reason',
        'visit_id',
        'performed_by_user_id',
        'performed_at',
    ];

    protected $casts = [
        'qty' => 'integer',
        'performed_at' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'batch_id');
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }
}
