<x-filament-panels::page>
    <div style="background: #f0f4f8; min-height: 100vh; padding: 2rem;">
        @if($this->getStudents()->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No children linked</h3>
                <p class="text-gray-600">Please contact the school administration to link your children to your account.</p>
            </div>
        @else
            @foreach($this->getStudents() as $student)
                <div class="space-y-6">
                    <!-- Welcome Banner -->
                    <div style="background: linear-gradient(135deg, #df8811 0%, #f5a742 100%); border-radius: 12px; padding: 2rem; color: white;">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
                                <p class="text-white/90 mb-4">Monitor your child's health and access medical records anytime.</p>
                                <a href="{{ route('filament.parent.resources.students.view', $student) }}" 
                                  style="color: #1e3a5f;" class="inline-flex items-center px-6 py-3 bg-white  rounded-lg hover:bg-gray-100 transition-colors font-semibold" >
                                    View Full Profile
                                </a>
                            </div>
                            <div class="hidden md:block">
                                <div class="w-48 h-48 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Total Visits</p>
                                    <p class="text-3xl font-bold" style="color: #1e3a5f;">{{ $student->visits->count() }}</p>
                                </div>
                                <div class="w-16 h-16 rounded-lg flex items-center justify-center" style="background: #e3f2fd;">
                                    <svg class="w-8 h-8" style="color: #1e3a5f;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Clinic visit records</p>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Active Conditions</p>
                                    <p class="text-3xl font-bold" style="color: #df8811;">{{ $student->chronicConditions->where('active', true)->count() }}</p>
                                </div>
                                <div class="w-16 h-16 rounded-lg flex items-center justify-center" style="background: #fff3e0;">
                                    <svg class="w-8 h-8" style="color: #df8811;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Medical conditions</p>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Allergies</p>
                                    <p class="text-3xl font-bold" style="color: #1e3a5f;">{{ $student->allergies->count() }}</p>
                                </div>
                                <div class="w-16 h-16 rounded-lg flex items-center justify-center" style="background: #ffebee;">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Known allergies</p>
                        </div>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column: Student Profile & Visits -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Student Profile Card -->
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-start gap-6">
                                    <div class="flex-shrink-0">
                                        <div class="w-32 h-32 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #1e3a5f 0%, #2d4a6f 100%);">
                                            <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="inline-block px-3 py-1 rounded text-xs font-semibold mb-2" style="background: #e3f2fd; color: #1e3a5f;">Student</span>
                                        <h3 class="text-2xl font-bold mb-1" style="color: #df8811;"  >{{ $student->full_name }}</h3>
                                        <p class="text-gray-600 mb-4">Age {{ $student->age ?? 'N/A' }}, {{ ucfirst($student->gender ?? 'N/A') }}</p>
                                        
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1">Blood Group</p>
                                                <p class="text-sm font-semibold "style="color: #1e3a5f;" >
                                                    {{ $student->medicalProfile?->blood_group ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1">Height</p>
                                                <p class="text-sm font-semibold " style="color: #1e3a5f;" >N/A</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1">Weight</p>
                                                <p class="text-sm font-semibold " style="color: #1e3a5f;" >
                                                    @if($student->visits->isNotEmpty() && $student->visits->first()->vitals->isNotEmpty())
                                                        {{ $student->visits->first()->vitals->first()->weight ?? 'N/A' }} kg
                                                    @else
                                                        N/A
                                                    @endif
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1">Blood Pressure</p>
                                                <p class="text-sm font-semibold " style="color: #1e3a5f;" >
                                                    @if($student->visits->isNotEmpty() && $student->visits->first()->vitals->isNotEmpty())
                                                        @php $vitals = $student->visits->first()->vitals->first(); @endphp
                                                        {{ $vitals->blood_pressure_systolic ?? 'N/A' }}/{{ $vitals->blood_pressure_diastolic ?? 'N/A' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="text-sm " style="color: #1e3a5f;" >
                                            <p class="mb-1"><strong>School:</strong> {{ $student->school?->name ?? 'N/A' }}</p>
                                            @if($student->enrolment && $student->enrolment->stream)
                                                <p><strong>Class:</strong> {{ $student->enrolment->stream }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Visits -->
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-bold " style="color: #1e3a5f;" >Recent Clinic Visits</h4>
                                    <a href="{{ route('filament.parent.resources.students.view', $student) }}" 
                                       class="text-sm font-semibold" style="color: #1e3a5f;">See all</a>
                                </div>
                                
                                @if($student->visits->isEmpty())
                                    <div class="text-center py-8">
                                        <p class="text-sm " style="color: #1e3a5f;" >No clinic visits recorded</p>
                                    </div>
                                @else
                                    <div class="space-y-3">
                                        @foreach($student->visits->take(5) as $visit)
                                            <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #e3f2fd;">
                                                        <svg class="w-6 h-6" style="color: #1e3a5f;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <p class="text-sm font-semibold " style="color: #1e3a5f;" >
                                                            {{ ucfirst(str_replace('_', ' ', strtolower($visit->visit_type))) }}
                                                        </p>
                                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                                            {{ $visit->triage_level === 'EMERGENCY' ? 'bg-red-100 text-red-800' : 
                                                               ($visit->triage_level === 'HIGH' ? 'bg-orange-100 text-orange-800' : 
                                                               ($visit->triage_level === 'MEDIUM' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                                            {{ $visit->triage_level }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs " style="color: #1e3a5f;" mb-1">
                                                        {{ $visit->arrival_at->format('d M Y') }} - {{ $visit->arrival_at->format('g:i A') }}
                                                    </p>
                                                    @if($visit->chief_complaint)
                                                        <p class="text-xs " style="color: #1e3a5f;" truncate>{{ Str::limit($visit->chief_complaint, 60) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column: Calendar, Appointments, Treatments -->
                        <div class="space-y-6">
                            <!-- Calendar -->
                            <div class="bg-white rounded-lg shadow p-6">
                                <h4 class="text-lg font-bold " style="color: #1e3a5f;" mb-4>{{ now()->format('F Y') }}</h4>
                                <div class="grid grid-cols-7 gap-1 mb-2">
                                    @foreach(['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $day)
                                        <div class="text-center text-xs font-semibold " style="color: #1e3a5f;" py-1>{{ $day }}</div>
                                    @endforeach
                                </div>
                                <div class="grid grid-cols-7 gap-1">
                                    @php
                                        $firstDay = now()->startOfMonth()->dayOfWeek;
                                        $daysInMonth = now()->daysInMonth;
                                        $currentDay = now()->day;
                                    @endphp
                                    @for($i = 0; $i < $firstDay; $i++)
                                        <div class="aspect-square"></div>
                                    @endfor
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        <div class="aspect-square flex items-center justify-center text-sm rounded
                                            {{ $day == $currentDay ? 'bg-blue-600 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                                            {{ $day }}
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Upcoming Appointments -->
                            <div class="bg-white rounded-lg shadow p-6">
                                <h4 class="text-lg font-bold " style="color: #1e3a5f;" mb-4>Upcoming Visits</h4>
                                @if($student->visits->whereNull('departure_at')->isNotEmpty())
                                    <div class="space-y-3">
                                        @foreach($student->visits->whereNull('departure_at')->take(2) as $visit)
                                            <div class="p-3 border border-gray-200 rounded-lg">
                                                <p class="text-sm font-semibold " style="color: #1e3a5f;" mb-1">
                                                    {{ ucfirst(str_replace('_', ' ', strtolower($visit->visit_type))) }}
                                                </p>
                                                <p class="text-xs " style="color: #1e3a5f;" mb-2">
                                                    {{ $visit->arrival_at->format('g:i A') }} - {{ $visit->departure_at ? $visit->departure_at->format('g:i A') : 'Ongoing' }}
                                                </p>
                                                <p class="text-xs text-gray-500">Clinic</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm " style="color: #1e3a5f;" text-center py-4>No upcoming visits</p>
                                @endif
                            </div>

                            <!-- Treatments/Prescriptions -->
                            <div class="bg-white rounded-lg shadow p-6">
                                <h4 class="text-lg font-bold " style="color: #1e3a5f;" mb-4>Current Treatments</h4>
                                @if($student->visits->isNotEmpty())
                                    @php $latestVisit = $student->visits->first(); @endphp
                                    @if($latestVisit->prescriptions->isNotEmpty() || $latestVisit->treatments->isNotEmpty())
                                        <div class="space-y-3">
                                            @if($latestVisit->prescriptions->isNotEmpty())
                                                @foreach($latestVisit->prescriptions->take(2) as $index => $prescription)
                                                    <div class="p-3 border border-gray-200 rounded-lg">
                                                        <p class="text-sm font-semibold " style="color: #1e3a5f;" mb-1">
                                                            {{ $index + 1 }}. {{ $prescription->item->name ?? 'Medication' }}
                                                        </p>
                                                        <p class="text-xs " style="color: #1e3a5f;" >
                                                            {{ $prescription->dose ?? '1' }} {{ $prescription->frequency ?? 'daily' }}
                                                        </p>
                                                        @if($prescription->instructions)
                                                            <p class="text-xs " style="color: #1e3a5f;" mt-1>{{ $prescription->instructions }}</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @elseif($latestVisit->treatments->isNotEmpty())
                                                @foreach($latestVisit->treatments->take(2) as $index => $treatment)
                                                    <div class="p-3 border border-gray-200 rounded-lg">
                                                        <p class="text-sm font-semibold " style="color: #1e3a5f;" mb-1">
                                                            {{ $index + 1 }}. {{ ucfirst(str_replace('_', ' ', strtolower($treatment->treatment_type))) }}
                                                        </p>
                                                        <p class="text-xs text-gray-600">{{ Str::limit($treatment->description, 50) }}</p>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <a href="{{ route('filament.parent.resources.students.view', $student) }}" 
                                               class="text-xs font-semibold" style="color: #1e3a5f;">more...</a>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 text-center py-4">No current treatments</p>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-500 text-center py-4">No treatments recorded</p>
                                @endif
                            </div>

                            <!-- Medical History -->
                            <div class="bg-white rounded-lg shadow p-6">
                                <h4 class="text-lg font-bold  mb-4" style="color: #1e3a5f;" >Medical History</h4>
                                <div class="space-y-3">
                                    @if($student->chronicConditions->where('active', true)->isNotEmpty())
                                        @foreach($student->chronicConditions->where('active', true)->take(2) as $condition)
                                            <div class="p-3 border border-gray-200 rounded-lg">
                                                <div class="flex items-start justify-between mb-2">
                                                    <h5 class="text-sm font-semibold text-gray-900">{{ $condition->condition }}</h5>
                                                    <span class="w-2 h-2 rounded-full" style="background: #df8811;"></span>
                                                </div>
                                                <p class="text-xs text-gray-600 mb-2">{{ Str::limit($condition->notes ?? 'Active condition', 60) }}</p>
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="font-semibold" style="color: #df8811;">Active</span>
                                                    <a href="{{ route('filament.parent.resources.students.view', $student) }}" 
                                                       class="font-semibold" style="color: #1e3a5f;">See full history →</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    
                                    @if($student->chronicConditions->where('active', false)->isNotEmpty())
                                        @foreach($student->chronicConditions->where('active', false)->take(1) as $condition)
                                            <div class="p-3 border border-gray-200 rounded-lg">
                                                <div class="flex items-start justify-between mb-2">
                                                    <h5 class="text-sm font-semibold text-gray-900">{{ $condition->condition }}</h5>
                                                    <span class="w-2 h-2 rounded-full bg-green-600"></span>
                                                </div>
                                                <p class="text-xs text-gray-600 mb-2">
                                                    Resolved: {{ $condition->updated_at->format('d M Y') }}
                                                </p>
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="font-semibold text-green-600">Resolved</span>
                                                    <a href="{{ route('filament.parent.resources.students.view', $student) }}" 
                                                       class="font-semibold" style="color: #1e3a5f;">See full history →</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    @if($student->chronicConditions->isEmpty())
                                        <p class="text-sm text-gray-500 text-center py-4">No medical history</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-filament-panels::page>
