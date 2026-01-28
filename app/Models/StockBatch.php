<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class StockBatch extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'inventory_id',
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

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
