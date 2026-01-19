# School Clinic Management Portal - Setup Guide

## Overview

This is a Laravel-based School Clinic/Dispensary Management Portal for Pioneer Group of schools. The system is designed to handle sensitive medical data with compliance considerations.

## Tech Stack

- **Laravel 12** (PHP 8.3+)
- **SQLite** (development) - configured with absolute path
- **Filament v3** - Admin panel framework
- **Livewire 3** - Reactive components
- **Laravel Breeze (Blade)** - Authentication
- **Spatie Laravel Permission** - Role-based access control

## Installation Status

✅ All core packages installed and configured
✅ SQLite configured with absolute path
✅ Laravel Breeze (Blade) installed
✅ Filament v3 installed with Clinic Panel at `/clinic`
✅ Spatie Permissions installed and configured
✅ Security settings configured
✅ Roles created

## Project Structure

```
app/
├── Models/
│   └── User.php (with Spatie HasRoles trait)
├── Providers/
│   └── Filament/
│       ├── AdminPanelProvider.php (default admin panel at /admin)
│       └── ClinicPanelProvider.php (clinic panel at /clinic)
├── Traits/
│   └── HasUuid.php (for UUID-based models)
└── ...

config/
├── audit.php (audit logging configuration)
├── database.php (SQLite with absolute path)
├── permission.php (Spatie permissions config)
└── session.php (30-minute timeout, encrypted)

database/
├── migrations/
│   ├── 2026_01_19_091421_create_permission_tables.php
│   └── 2026_01_19_000000_example_uuid_migration.php (template)
└── seeders/
    └── RoleSeeder.php (creates: clinic_nurse, doctor, admin, principal_readonly)
```

## Access Points

- **Public Routes**: `/` (Breeze authentication)
- **Admin Panel**: `/admin` (Filament admin panel)
- **Clinic Panel**: `/clinic` (Filament clinic panel - primary interface)

## Roles & Permissions

The following roles have been created:

1. **clinic_nurse** - Can manage visits, triage, basic treatment
2. **doctor** - Full access to medical records and treatment
3. **admin** - System administration
4. **principal_readonly** - Read-only access for compliance reporting

## Security Configuration

### Session Settings
- **Lifetime**: 30 minutes (reduced for sensitive data)
- **Expire on Close**: Enabled
- **Encryption**: Enabled

### Database
- **SQLite**: Absolute path configured (`/home/engineer/Desktop/school-clinic/database/database.sqlite`)
- **Foreign Keys**: Enabled
- **Portable**: Migrations work with SQLite, MySQL, and PostgreSQL

## UUID Implementation

For all domain models (patients, visits, inventory, etc.), use the `HasUuid` trait:

```php
use App\Traits\HasUuid;

class Patient extends Model
{
    use HasUuid;
    
    // Model implementation
}
```

See `database/migrations/2026_01_19_000000_example_uuid_migration.php` for migration template.

## Next Steps (Not Yet Implemented)

### Domain Models to Create
- Patient (students & staff)
- Clinic Visit
- Triage/Vitals
- Medication Inventory
- Medication Expiry Tracking

### Features to Implement
- Patient profile management
- Visit tracking with triage
- Medication inventory management
- Reporting & compliance dashboards
- SMS/Email notifications (no medical details)

### Audit Logging
- Configuration ready in `config/audit.php`
- Consider implementing `spatie/laravel-activitylog` or `owen-it/laravel-auditing`
- All patient data access must be logged

## Commands

```bash
# Run migrations
php artisan migrate

# Seed roles
php artisan db:seed --class=RoleSeeder

# Create a new user and assign role
php artisan tinker
>>> $user = User::create(['name' => 'Nurse Jane', 'email' => 'nurse@school.com', 'password' => bcrypt('password')]);
>>> $user->assignRole('clinic_nurse');

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Environment Variables

Key settings in `.env`:
```env
APP_NAME="School Clinic Portal"
DB_CONNECTION=sqlite
DB_DATABASE=/home/engineer/Desktop/school-clinic/database/database.sqlite
SESSION_LIFETIME=30
SESSION_EXPIRE_ON_CLOSE=true
SESSION_ENCRYPT=true
```

## Compliance Considerations

1. **Data Privacy**: UUIDs prevent enumeration attacks
2. **Access Control**: Role-based permissions via Spatie
3. **Session Security**: Short timeout, encryption enabled
4. **Audit Logging**: Configuration ready for implementation
5. **Data Retention**: Configured for 7-year retention (healthcare standard)

## Development Notes

- Node version warning: Current Node.js version (v12.22.9) is outdated. Consider upgrading for Vite builds.
- All migrations are portable (SQLite → MySQL/PostgreSQL)
- Filament panels are separated: Admin for system management, Clinic for medical operations

## Support

For questions or issues, refer to:
- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Spatie Permissions](https://spatie.be/docs/laravel-permission)
