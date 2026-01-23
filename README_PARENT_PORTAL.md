# Parent Portal - User Guide

## Overview
The Parent Portal provides parents and guardians with secure access to view their children's medical records and clinic visit history from the school clinic.

## Features

### 1. Dashboard Overview
- **Children Overview**: Quick stats showing the number of registered children and recent clinic visits
- **Recent Medical Visits**: Table showing all recent visits with key details
- **Health Summary Cards**: Individual health profiles for each child

### 2. My Children
- View detailed profiles of all registered children
- See personal information, contact details, and medical history
- Access emergency consent status
- View known allergies and chronic conditions

### 3. Medical Visits
- Complete history of all clinic visits
- Filter by child, visit type, or active visits
- View detailed visit information including:
  - Vital signs (temperature, blood pressure, pulse, etc.)
  - Diagnoses and medical assessments
  - Treatments provided
  - Prescriptions and medications
  - Arrival and departure times

## Access Instructions

### URL
Access the Parent Portal at: `https://your-school-domain.com/parent`

### First Time Setup
1. Contact the school office to register as a parent/guardian
2. Provide your email address (must match the guardian email on file)
3. Receive login credentials from the school administrator
4. Log in at `/parent` with your credentials

### Security
- Parents can only view information for children linked to their email address
- All data is read-only - parents cannot modify medical records
- Sessions are secured with Laravel's authentication system

## Technical Setup (For Administrators)

### 1. Assign Parent Role
```php
use App\Models\User;

// Create or find user
$user = User::firstOrCreate([
    'email' => 'parent@example.com',
], [
    'name' => 'Parent Name',
    'password' => bcrypt('secure-password'),
]);

// Assign parent role
$user->assignRole('parent');
```

### 2. Link Children to Parents
Ensure the `patients` table has the correct `guardian_email` matching the parent's user email:

```php
use App\Models\Patient;

$patient = Patient::find($patientId);
$patient->guardian_email = 'parent@example.com';
$patient->save();
```

### 3. Run Database Seeders
```bash
php artisan db:seed --class=ParentRoleSeeder
```

### 4. Clear Cache
```bash
php artisan optimize:clear
php artisan filament:optimize-clear
```

## Troubleshooting

### Parent Cannot See Children
- Verify the parent's email in the `users` table matches the `guardian_email` in the `patients` table
- Ensure the patient records have `is_active = true`
- Check that the parent has been assigned the 'parent' role

### Cannot Access Parent Portal
- Verify user has 'parent' role: `$user->hasRole('parent')`
- Check middleware is properly configured
- Ensure ParentPanelProvider is registered in `bootstrap/providers.php`

### No Visits Showing
- Verify the `adm_or_staff_no` in the `persons` table matches the `student_id` in the `patients` table
- Check that visits exist in the database with matching `person_id`

## Customization

### Branding
Update the panel configuration in `app/Providers/Filament/ParentPanelProvider.php`:

```php
->brandName('Your School Parent Portal')
->brandLogo(asset('images/your-logo.svg'))
->colors([
    'primary' => Color::Indigo,
])
```

### Email Notifications
Parents can be notified via email when their children visit the clinic. Configure in your notification settings.

## Support
For technical support or questions, contact your school's IT administrator.
