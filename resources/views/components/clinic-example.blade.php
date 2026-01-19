{{-- Example component showcasing Tailwind customizations --}}
{{-- This is a reference component - can be deleted or used as a template --}}

<div class="space-y-6 p-6">
    {{-- Status Badges --}}
    <div class="form-section">
        <h3 class="form-section-title">Status Badges</h3>
        <div class="flex gap-4">
            <span class="badge-success">Cleared</span>
            <span class="badge-warning">Caution</span>
            <span class="badge-danger">Critical</span>
            <span class="badge-info">Information</span>
        </div>
    </div>

    {{-- Triage Levels --}}
    <div class="form-section">
        <h3 class="form-section-title">Triage Levels</h3>
        <div class="flex gap-4">
            <span class="triage-immediate">Immediate</span>
            <span class="triage-urgent">Urgent</span>
            <span class="triage-standard">Standard</span>
            <span class="triage-nonurgent">Non-urgent</span>
        </div>
    </div>

    {{-- Clinic Card Example --}}
    <div class="clinic-card">
        <h3 class="text-lg font-semibold text-clinic-text-primary mb-4">Patient Visit Card</h3>
        <div class="space-y-2">
            <p class="text-clinic-text-secondary">Patient: <span class="text-clinic-text-primary font-medium">John Doe</span></p>
            <p class="text-clinic-text-secondary">Date: <span class="text-clinic-text-primary">2026-01-19</span></p>
            <p class="text-clinic-text-secondary">Temperature: <span class="text-clinic-text-primary font-medium">98.6°F</span></p>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <span class="badge-success">Cleared for Return</span>
            <button class="btn-clinic-primary">View Details</button>
        </div>
    </div>

    {{-- Medical Alert Examples --}}
    <div class="space-y-3">
        <div class="alert-medical-success">
            <strong>Success:</strong> Patient cleared for return to class.
        </div>
        <div class="alert-medical-warning">
            <strong>Warning:</strong> Follow-up appointment required.
        </div>
        <div class="alert-medical-danger">
            <strong>Urgent:</strong> Immediate medical attention needed.
        </div>
    </div>

    {{-- Medical Table Example --}}
    <div class="form-section">
        <h3 class="form-section-title">Recent Visits</h3>
        <table class="medical-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Triage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>John Doe</td>
                    <td>2026-01-19</td>
                    <td><span class="badge-success">Cleared</span></td>
                    <td><span class="triage-standard">Standard</span></td>
                </tr>
                <tr>
                    <td>Jane Smith</td>
                    <td>2026-01-19</td>
                    <td><span class="badge-warning">Follow-up</span></td>
                    <td><span class="triage-urgent">Urgent</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
