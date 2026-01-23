<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-heart class="w-5 h-5 text-primary-500" />
                <span>My Children's Health Summary</span>
            </div>
        </x-slot>

        @php
            $children = $this->getViewData()['children'];
        @endphp

        @if(count($children) > 0)
            <div class="space-y-4">
                @foreach($children as $childData)
                    @php
                        $child = $childData['patient'];
                        $person = $childData['person'];
                        $recentVisit = $childData['recent_visit'];
                        $visitCount = $childData['visit_count'];
                    @endphp
                    
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/20">
                                        <x-heroicon-o-user class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $child->full_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Student ID: {{ $child->student_id }} 
                                            @if($child->class)
                                                • {{ $child->class }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                    <div class="flex items-center gap-2 text-sm">
                                        <x-heroicon-o-calendar class="w-4 h-4 text-gray-400" />
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Age: {{ $child->date_of_birth ? $child->date_of_birth->age : 'N/A' }} years
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm">
                                        <x-heroicon-o-clipboard-document-list class="w-4 h-4 text-gray-400" />
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Visits this year: {{ $visitCount }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm">
                                        <x-heroicon-o-heart class="w-4 h-4 text-gray-400" />
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Status: 
                                            <span class="font-medium text-success-600 dark:text-success-400">Healthy</span>
                                        </span>
                                    </div>
                                </div>

                                @if($child->allergies || $child->chronic_conditions)
                                    <div class="mt-3 p-3 rounded-lg bg-warning-50 dark:bg-warning-900/10 border border-warning-200 dark:border-warning-800">
                                        <h4 class="text-sm font-semibold text-warning-800 dark:text-warning-300 mb-2 flex items-center gap-2">
                                            <x-heroicon-o-exclamation-triangle class="w-4 h-4" />
                                            Important Medical Information
                                        </h4>
                                        @if($child->allergies)
                                            <p class="text-sm text-warning-700 dark:text-warning-400 mb-1">
                                                <strong>Allergies:</strong> {{ $child->allergies }}
                                            </p>
                                        @endif
                                        @if($child->chronic_conditions)
                                            <p class="text-sm text-warning-700 dark:text-warning-400">
                                                <strong>Chronic Conditions:</strong> {{ $child->chronic_conditions }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                @if($recentVisit)
                                    <div class="mt-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Last Clinic Visit
                                        </h4>
                                        <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                            <p><strong>Date:</strong> {{ $recentVisit->arrival_at->format('M d, Y h:i A') }}</p>
                                            <p><strong>Reason:</strong> {{ $recentVisit->chief_complaint }}</p>
                                            @if($recentVisit->disposition)
                                                <p><strong>Outcome:</strong> {{ str_replace('_', ' ', $recentVisit->disposition) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3 p-3 rounded-lg bg-success-50 dark:bg-success-900/10 border border-success-200 dark:border-success-800">
                                        <p class="text-sm text-success-700 dark:text-success-400 flex items-center gap-2">
                                            <x-heroicon-o-check-circle class="w-4 h-4" />
                                            No recent clinic visits
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <x-heroicon-o-user-group class="w-16 h-16 text-gray-400 mx-auto mb-3" />
                <p class="text-gray-500 dark:text-gray-400">No children registered under your account.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Please contact the school office to link your children.</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
