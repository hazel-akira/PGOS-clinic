<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockBatch extends Model
{
    use HasUuid;

    protected $fillable = [
        'item_id',
        'batch_no',
        'expiry_date',
        'qty_on_hand',
        'unit_cost',
        'supplier_id',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'qty_on_hand' => 'integer',
        'unit_cost' => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockTxns(): HasMany
    {
        return $this->hasMany(StockTxn::class, 'batch_id');
    }
}
