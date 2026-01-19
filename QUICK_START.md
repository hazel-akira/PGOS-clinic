# Quick Start Guide

## ✅ Setup Complete

All core components have been installed and configured:

1. **SQLite** - Configured with absolute path
2. **Laravel Breeze** - Authentication ready
3. **Filament v3** - Two panels configured:
   - Admin Panel: `/admin`
   - Clinic Panel: `/clinic` (primary interface)
4. **Spatie Permissions** - 4 roles created
5. **Security** - Session timeout (30 min), encryption enabled
6. **UUID Support** - Trait ready for domain models

## Access the System

1. **Create a user:**
   ```bash
   php artisan tinker
   ```
   ```php
   $user = \App\Models\User::create([
       'name' => 'Dr. Smith',
       'email' => 'doctor@school.com',
       'password' => bcrypt('password')
   ]);
   $user->assignRole('doctor');
   ```

2. **Access Clinic Panel:**
   - URL: `http://localhost:8000/clinic`
   - Login with the user you created

3. **Access Admin Panel:**
   - URL: `http://localhost:8000/admin`
   - Same credentials work

## Available Roles

- `clinic_nurse` - Basic clinic operations
- `doctor` - Full medical access
- `admin` - System administration
- `principal_readonly` - Read-only compliance access

## Next Development Steps

1. Create domain models using `HasUuid` trait
2. Create Filament Resources in `app/Filament/Clinic/Resources/`
3. Implement policies for role-based access
4. Set up audit logging (config ready in `config/audit.php`)
5. Build patient, visit, and inventory management

## Important Files

- `app/Traits/HasUuid.php` - Use for all domain models
- `config/audit.php` - Audit logging configuration
- `database/migrations/2026_01_19_000000_example_uuid_migration.php` - UUID migration template
- `SETUP.md` - Full setup documentation

## Security Notes

- Sessions expire after 30 minutes of inactivity
- Sessions encrypted and expire on browser close
- UUIDs used to prevent enumeration attacks
- Role-based access control implemented
