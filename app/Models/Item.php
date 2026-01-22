<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasUuid;

    protected $fillable = [
        'name',
        'form',
        'strength',
        'unit',
        'reorder_level',
        'is_medicine',
        'active',
    ];

    protected $casts = [
        'reorder_level' => 'integer',
        'is_medicine' => 'boolean',
        'active' => 'boolean',
    ];

    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }
}
