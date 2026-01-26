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

    protected $fillable = [
        'medication_id',
        'batch_number',
        'expiry_date',
        'manufacture_date',
        'quantity_in',
        'quantity_out',
        'quantity_available',
        'minimum_stock_level',
        'supplier_name',
        'supplier_contact',
        'purchase_date',
        'unit_price',
        'storage_location',
        'storage_notes',
        'is_expired',
        'is_low_stock',
        'low_stock_alerted_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'manufacture_date' => 'date',
        'purchase_date' => 'date',
        'low_stock_alerted_at' => 'datetime',
        'unit_price' => 'decimal:2',
        'is_expired' => 'boolean',
        'is_low_stock' => 'boolean',
    ];

    /**
     * Get the medication for this inventory entry.
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Get all visit medications that used this inventory batch.
     */
    public function visitMedications(): HasMany
    {
        return $this->hasMany(VisitMedication::class);
    }

    /**
     * Get the user who created this inventory entry.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this inventory entry.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Check if this inventory item is expired.
     */
    public function checkExpiry(): void
    {
        if ($this->expiry_date && $this->expiry_date->isPast()) {
            $this->is_expired = true;
            $this->save();
        }
    }

    /**
     * Check if this inventory item is low stock.
     */
    public function checkLowStock(): void
    {
        $wasLowStock = $this->is_low_stock;
        $this->is_low_stock = $this->quantity_available <= $this->minimum_stock_level;

        if ($this->is_low_stock && ! $wasLowStock) {
            $this->low_stock_alerted_at = now();
        }

        $this->save();
    }
}
