<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasUuid;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }
}
