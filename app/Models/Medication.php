<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'name',
        'generic_name',
        'manufacturer',
        'description',
        'dosage_form',
        'strength',
        'dosage_instructions',
        'category',
        'requires_prescription',
        'is_controlled_substance',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'requires_prescription' => 'boolean',
        'is_controlled_substance' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get all inventory entries for this medication.
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get all visit medications using this medication.
     */
    public function visitMedications(): HasMany
    {
        return $this->hasMany(VisitMedication::class);
    }

    /**
     * Get the user who created this medication.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this medication.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
