<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    use HasUuid;

    protected $fillable = [
        'stock_batch_id',
        'type', // in | out
        'quantity',
        'notes',
        'performed_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'stock_batch_id');
    }

    public function stockIn(
    StockBatch $batch,
    int $quantity,
    int $userId
    ): void {
        $batch->increment('qty_on_hand', $quantity);

        $batch->transactions()->create([
            'type' => 'in',
            'quantity' => $quantity,
            'notes' => 'Purchase / Supply',
            'performed_by' => $userId,
        ]);

        $batch->inventory->recalculateStock();
    }

    public function stockOut(
    StockBatch $batch,
    int $quantity,
    int $userId,
    string $reason = 'Issued to patient'
    ): void {
        if ($batch->qty_on_hand < $quantity) {
            throw new \Exception('Insufficient stock in batch.');
        }

        $batch->decrement('qty_on_hand', $quantity);

        $batch->transactions()->create([
            'type' => 'out',
            'quantity' => $quantity,
            'notes' => $reason,
            'performed_by' => $userId,
        ]);

        $batch->inventory->recalculateStock();
    }


}

