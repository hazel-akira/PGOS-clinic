@php
    // Prefer the column's record accessor to avoid conflicts with exported methods named `record`.
    $model = null;

    if (isset($column) && is_callable([$column, 'getRecord'])) {
        $model = $column->getRecord();
    }

    if (! $model && isset($record) && ! (is_object($record) && ($record instanceof \Closure))) {
        $model = $record;
    }

    if ($model instanceof \Closure || is_callable($model)) {
        try {
            $model = $model();
        } catch (\Throwable $e) {
            $model = null;
        }
    }

    if (! $model) {
        echo '<span class="text-gray-400">-</span>'; return; 
    }

@endphp

@if($model->batches->isEmpty())
    <span class="text-gray-400">-</span>
@else
    <table class="w-full text-sm border border-gray-200 rounded">
        <thead>
            <tr class="bg-amber-500 text-white font-bold">
                <th class="p-1 border">Batch #</th>
                <th class="p-1 border">Qty</th>
                <th class="p-1 border">Expiry</th>
                <th class="p-1 border">Unit Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->batches as $batch)
                <tr class="border-b">
                    <td class="p-1 border">{{ $batch->batch_no }}</td>
                    <td class="p-1 border">{{ $batch->qty_on_hand }}</td>
                    <td class="p-1 border">
                        {{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('Y-m-d') : '-' }}
                    </td>
                    <td class="p-1 border">
                        {{ $batch->unit_cost ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
