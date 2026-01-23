# Parent Portal - Setup Guide

## Quick Start

Follow these steps to set up and test the Parent Portal:

### 1. Run Database Migrations
```bash
php artisan migrate
```

### 2. Create Parent Role and Permissions
```bash
php artisan db:seed --class=ParentRoleSeeder
```

### 3. (Optional) Create Demo Parent Users
**⚠️ DEVELOPMENT/TESTING ONLY**
```bash
php artisan db:seed --class=DemoParentUserSeeder
```

This will create two demo parent accounts:
- Email: `parent1@demo.com` | Password: `password123`
- Email: `parent2@demo.com` | Password: `password123`

### 4. Clear Application Cache
```bash
php artisan optimize:clear
php artisan filament:optimize-clear
```

### 5. Access the Parent Portal
Navigate to: `http://your-domain.com/parent`

## Manual Parent Setup (Production)

### Step 1: Create Parent User Account
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$parent = User::create([
    'name' => 'Parent Full Name',
    'email' => 'parent@example.com',
    'password' => Hash::make('secure-password'),
]);

// Assign parent role
$parent->assignRole('parent');
```

### Step 2: Link Children to Parent
Update the `guardian_email` field in the `patients` table to match the parent's email:

```php
use App\Models\Patient;

// Method 1: Update existing patient
$patient = Patient::where('student_id', 'STU001')->first();
$patient->update([
    'guardian_email' => 'parent@example.com',
    'guardian_name' => 'Parent Full Name',
    'guardian_phone' => '+1234567890',
    'guardian_relationship' => 'Father', // or 'Mother', 'Guardian', etc.
]);

// Method 2: Update multiple children at once
Patient::whereIn('student_id', ['STU001', 'STU002'])
    ->update([
        'guardian_email' => 'parent@example.com',
        'guardian_name' => 'Parent Full Name',
    ]);
```

### Step 3: Verify Setup
```php
// Check if parent has correct role
$user = User::where('email', 'parent@example.com')->first();
echo $user->hasRole('parent') ? 'Has parent role ✓' : 'Missing parent role ✗';

// Check linked children
use App\Models\Patient;
$children = Patient::where('guardian_email', 'parent@example.com')
    ->where('is_active', true)
    ->get();
echo "Linked children: " . $children->count();
```

## Important Notes

### Data Model Relationship
The system uses two parallel models for person records:

1. **Patient Model** (`patients` table)
   - Contains detailed student information
   - Includes guardian details (guardian_email, guardian_name, etc.)
   - Used for linking parents to children

2. **Person Model** (`persons` table)
   - Used by clinic for visit records
   - Linked via `adm_or_staff_no` = `student_id`

**Key Relationship:**
```
User (parent) → guardian_email matches → Patient.guardian_email
Patient.student_id matches → Person.adm_or_staff_no
Person → has many → Visits
```

### Required Data
For the parent portal to work correctly, ensure:

1. ✅ User has 'parent' role
2. ✅ Patient records have matching `guardian_email`
3. ✅ Patient records have `is_active = true`
4. ✅ Patient `student_id` matches Person `adm_or_staff_no`
5. ✅ Person records exist for students
6. ✅ Visits are linked to Person records

## Bulk Import Parents (CSV/Excel)

If you need to import many parent accounts:

```php
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

// Example CSV structure: name, email, student_ids (comma-separated)
$csv = [
    ['John Doe', 'john@example.com', 'STU001,STU002'],
    ['Jane Smith', 'jane@example.com', 'STU003'],
];

$parentRole = Role::where('name', 'parent')->first();

foreach ($csv as $row) {
    [$name, $email, $studentIds] = $row;
    
    // Create user
    $user = User::firstOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'password' => Hash::make(Str::random(16)), // Generate random password
        ]
    );
    
    // Assign role
    if (!$user->hasRole('parent')) {
        $user->assignRole($parentRole);
    }
    
    // Link to children
    $ids = explode(',', $studentIds);
    Patient::whereIn('student_id', $ids)
        ->update([
            'guardian_email' => $email,
            'guardian_name' => $name,
        ]);
    
    // Send welcome email with password reset link
    // $user->sendPasswordResetNotification(...);
}
```

## Security Considerations

### Access Control
- Parents can only view their own children (filtered by email)
- All actions are read-only (view only)
- No edit or delete permissions
- Middleware enforces role-based access

### Multi-Guardian Support
To support multiple guardians per child:

```php
// Option 1: Use semicolon-separated emails
$patient->guardian_email = 'parent1@example.com;parent2@example.com';

// Option 2: Create separate patient records (not recommended)

// Option 3: Create a pivot table (recommended for production)
// Create: parents table, parent_student pivot table
```

## Troubleshooting

### Issue: Parent Cannot Login
```bash
# Verify user exists and has parent role
php artisan tinker
>>> $user = \App\Models\User::where('email', 'parent@example.com')->first();
>>> $user->getRoleNames();
```

### Issue: No Children Showing
```sql
-- Check guardian email matches
SELECT * FROM patients 
WHERE guardian_email = 'parent@example.com' 
AND is_active = 1;

-- Check person records exist
SELECT p.*, per.id as person_id 
FROM patients p 
LEFT JOIN persons per ON p.student_id = per.adm_or_staff_no 
WHERE p.guardian_email = 'parent@example.com';
```

### Issue: No Visits Showing
```sql
-- Check visits exist for linked persons
SELECT v.*, p.full_name 
FROM visits v 
JOIN persons p ON v.person_id = p.id 
WHERE p.adm_or_staff_no IN (
    SELECT student_id FROM patients 
    WHERE guardian_email = 'parent@example.com'
);
```

## Customization

### Change Panel URL
Edit `app/Providers/Filament/ParentPanelProvider.php`:
```php
->path('parent') // Change to 'parents' or 'guardian'
```

### Add Custom Navigation Items
```php
// In ParentPanelProvider.php
->navigationGroups([
    'My Children',
    'Medical Reports',
    'Documents', // Add new group
    'Settings',
])
```

### Customize Dashboard Widgets
Edit `app/Filament/Parent/Pages/ParentDashboard.php`:
```php
public function getWidgets(): array
{
    return [
        ChildrenOverview::class,
        RecentVisits::class,
        HealthSummary::class,
        // Add your custom widgets here
    ];
}
```

## Support

For questions or issues:
1. Check the troubleshooting section above
2. Review Laravel logs: `storage/logs/laravel.log`
3. Enable debug mode: `APP_DEBUG=true` in `.env` (development only)
4. Check Filament documentation: https://filamentphp.com/docs

## License
Part of the School Clinic Management System
