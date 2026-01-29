<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasUuid, SoftDeletes;

    protected $table = 'inventory';

    /**
     * Mass assignable fields.
     * Inventory is an AGGREGATE, not a transaction log.
     */
    protected $fillable = [
        'medication_id',
        'quantity_available',
        'minimum_stock_level',
        'is_low_stock',
        'low_stock_alerted_at',
        'created_by',
        'updated_by',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'quantity_available'   => 'integer',
        'minimum_stock_level'  => 'integer',
        'is_low_stock'         => 'boolean',
        'low_stock_alerted_at' => 'datetime',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * Medication this inventory belongs to.
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * All stock batches under this inventory.
     * Each batch has its own expiry and supplier.
     */
    public function batches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    /**
     * User who created the inventory record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the inventory record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* -----------------------------------------------------------------
     |  Core Business Logic
     | -----------------------------------------------------------------
     */

    /**
     * Recalculate total stock from all active batches.
     * This method MUST be called after any stock in/out.
     */
    public function recalculateStock(): void
    {
        $this->quantity_available = $this->batches()
            ->whereNull('deleted_at')
            ->sum('qty_on_hand');

        $this->evaluateLowStock();

        $this->save();
    }

    /**
     * Determine whether inventory is low stock.
     */
    protected function evaluateLowStock(): void
    {
        $wasLowStock = $this->is_low_stock;

        $this->is_low_stock =
            $this->quantity_available <= $this->minimum_stock_level;

        if ($this->is_low_stock && ! $wasLowStock) {
            $this->low_stock_alerted_at = now();
        }

        if (! $this->is_low_stock) {
            $this->low_stock_alerted_at = null;
        }
    }

    /**
     * Check if inventory is completely out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity_available <= 0;
    }

    /**
     * Get batches ordered by expiry (FIFO issuing).
     */
    public function batchesForIssuing(): HasMany
    {
        return $this->batches()
            ->where('qty_on_hand', '>', 0)
            ->orderBy('expiry_date');
    }

    

}
