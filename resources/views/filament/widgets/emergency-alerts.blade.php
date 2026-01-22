<x-filament-widgets::widget>
    <x-filament::section>
        @php
            $data = $this->getViewData();
            $emergencyIncidents = $data['emergencyIncidents'];
            $count = $data['count'];
        @endphp

        @if($count > 0)
            <x-filament::card>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-danger-600">
                                🚨 Emergency Alerts
                            </h2>
                            <p class="text-sm text-gray-600">
                                {{ $count }} {{ Str::plural('emergency case', $count) }} requiring immediate attention
                            </p>
                        </div>

                        <a href="{{ \Filament\Facades\Filament::getPanel('clinic')->getUrl() }}/incidents?tableFilters[is_emergency][value]=1">
                            <x-filament::button color="danger">
                                View All Cases
                            </x-filament::button>
                        </a>
                    </div>

                    <div class="space-y-2">
                        @foreach($emergencyIncidents as $incident)
                            <div class="flex items-center justify-between p-3 bg-danger-50 dark:bg-danger-900/20 rounded-lg border border-danger-200 dark:border-danger-800">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-danger-700 dark:text-danger-400">
                                            {{ $incident->person->first_name }} {{ $incident->person->last_name }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            ({{ $incident->school->name ?? 'N/A' }})
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $incident->incident_type }} - {{ $incident->location }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $incident->occurred_at->diffForHumans() }}
                                    </p>
                                </div>
                                <a href="{{ \Filament\Facades\Filament::getPanel('clinic')->getUrl() }}/incidents/{{ $incident->id }}">
                                    <x-filament::button size="sm" color="danger" outlined>
                                        View
                                    </x-filament::button>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-filament::card>
        @else
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-600">
                            ✅ No Emergency Cases
                        </h2>
                        <p class="text-sm text-gray-500">
                            All cases are under control
                        </p>
                    </div>
                </div>
            </x-filament::card>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
