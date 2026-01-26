<?php

namespace App\Observers;

use App\Models\Medication;
use App\Models\ActivityLog;

class MedicationObserver
{
    public function created(Medication $medication): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'subject_type' => Medication::class,
            'subject_id' => $medication->id,
        ]);
    }

    public function updated(Medication $medication): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => Medication::class,
            'subject_id' => $medication->id,
        ]);
    }

    public function deleted(Medication $medication): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'subject_type' => Medication::class,
            'subject_id' => $medication->id,
        ]);
    }

    public function restored(Medication $medication): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'restored',
            'subject_type' => Medication::class,
            'subject_id' => $medication->id,
        ]);
    }

    public function forceDeleted(Medication $medication): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'force_deleted',
            'subject_type' => Medication::class,
            'subject_id' => $medication->id,
        ]);
    }
}
