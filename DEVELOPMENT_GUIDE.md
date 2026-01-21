# School Clinic Management System - Development Guide

## ✅ Phase 1: Foundation (COMPLETED)

### Database Structure
We've created the core database schema with the following tables:

1. **`patients`** - Stores student and staff patient information
   - Supports both students and staff
   - Bio data, guardian contacts, allergies, medical history
   - Consent records for first aid and emergency care

2. **`visits`** - Clinic visit records
   - Check-in/check-out tracking
   - Triage levels (low, medium, high, critical)
   - Vital signs (temperature, BP, pulse, weight, height)
   - Symptoms, diagnosis, treatment notes
   - Observation room tracking
   - Referral management
   - Emergency escalation flags

3. **`medications`** - Medication catalog
   - Name, generic name, manufacturer
   - Dosage forms and strengths
   - Categories and prescription requirements

4. **`inventory`** - Stock management
   - Batch and expiry tracking
   - Quantity in/out/available
   - Low stock alerts
   - Supplier information

5. **`visit_medications`** - Pivot table linking visits to medications
   - Tracks which medications were issued during visits
   - Dosage and frequency information

### Models Created
All models include:
- UUID primary keys (using `HasUuid` trait)
- Soft deletes for audit compliance
- Relationships (belongsTo, hasMany)
- Accessor methods (e.g., `getFullNameAttribute()`)
- Helper methods (e.g., `isActive()`, `checkExpiry()`)

**Models:**
- `Patient` - with visits relationship
- `Visit` - with patient, medications, and staff relationships
- `Medication` - with inventory and visit medications relationships
- `Inventory` - with medication relationship and expiry/stock checking
- `VisitMedication` - pivot model with all relationships

---

## 🚀 Next Steps: Phase 2 - Filament Resources

### Step 1: Run Migrations
```bash
php artisan migrate
```

### Step 2: Create Filament Resources
We'll use Filament (already installed) to build the admin interface:

```bash
# Create resources
php artisan make:filament-resource Patient
php artisan make:filament-resource Visit
php artisan make:filament-resource Medication
php artisan make:filament-resource Inventory
```

### Step 3: Set Up Roles & Permissions
Create roles using Spatie Permission (already installed):

```bash
php artisan tinker
```

Then:
```php
use Spatie\Permission\Models\Role;

Role::create(['name' => 'clinic_nurse']);
Role::create(['name' => 'doctor']);
Role::create(['name' => 'school_admin']);
Role::create(['name' => 'principal']);
```

### Step 4: Build Dashboard Widgets
Create Filament widgets for:
- Daily visit summary
- Current patients in clinic
- Emergency alerts
- Low stock alerts
- Quick action buttons

---

## 📋 Recommended Development Order

### MVP Priority (Must Have)
1. ✅ Database structure & models
2. ⏭️ **Patient Management** - CRUD for students/staff
3. ⏭️ **Visit Management** - Check-in/out, basic visit records
4. ⏭️ **Dashboard** - Summary stats and alerts
5. ⏭️ **Medication Inventory** - Basic stock management

### Phase 2 (Important)
6. Triage system with severity levels
7. Observation room tracking
8. Referral forms (printable)
9. Low stock alerts
10. Basic reporting

### Phase 3 (Enhancements)
11. Role-based access control refinement
12. SMS/Email notifications
13. Appointment scheduling
14. Advanced reporting & exports
15. Document attachments

---

## 🎯 Key Features to Implement

### 1. Patient Management
- **Student Registration**: Name, class, DOB, guardian contacts
- **Staff Registration**: Name, department, contact info
- **Medical History**: Allergies, chronic conditions, current medications
- **Consent Management**: First aid and emergency care consent

### 2. Visit Workflow
- **Check-in**: Quick patient lookup, reason for visit
- **Triage**: Assign severity level (low/medium/high/critical)
- **Assessment**: Record symptoms, vital signs, diagnosis
- **Treatment**: Issue medications, provide care instructions
- **Check-out**: Discharge or refer to hospital
- **Observation**: Track patients in observation room

### 3. Dashboard Features
- **Today's Summary**: Total visits, students vs staff
- **Active Patients**: Currently in clinic
- **Emergency Alerts**: Critical cases requiring attention
- **Stock Alerts**: Low medication inventory
- **Quick Actions**: New Visit, New Patient, New Medication

### 4. Inventory Management
- **Stock In**: Add new medication batches
- **Stock Out**: Issue medications to patients
- **Expiry Tracking**: Alert on expiring medications
- **Low Stock Alerts**: Automatic notifications
- **Batch Tracking**: Track which batch was used

### 5. Reporting
- Daily/weekly/termly visit summaries
- Common illnesses statistics
- Medication usage reports
- Patient visit history
- Export to PDF/Excel

---

## 🔐 Security & Privacy

### Role-Based Access
- **Clinic Nurse**: Full access to visits, patients, medications
- **Doctor**: Same as nurse + ability to approve referrals
- **School Admin**: Full system access
- **Principal**: Read-only access to reports and statistics

### Data Privacy
- UUIDs prevent enumeration attacks
- Soft deletes for audit trails
- Role-based data visibility
- Session timeouts (configure in `config/session.php`)

---

## 📝 Configuration Needed

### Environment Variables
Add to `.env`:
```env
# Azure SSO (already configured)
AZURE_AD_CLIENT_ID=
AZURE_AD_CLIENT_SECRET=
AZURE_AD_TENANT_ID=
AZURE_AD_REDIRECT_URI=

# SMS Gateway (for Phase 3)
SMS_API_KEY=
SMS_SENDER_ID=

# Email (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
```

---

## 🛠️ Development Commands

```bash
# Run migrations
php artisan migrate

# Create a new resource
php artisan make:filament-resource ModelName

# Create a widget
php artisan make:filament-widget WidgetName

# Seed roles
php artisan db:seed --class=RoleSeeder

# Clear cache
php artisan optimize:clear
```

---

## 📚 Resources

- [Filament Documentation](https://filamentphp.com/docs)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)
- [Laravel Eloquent](https://laravel.com/docs/eloquent)

---

## 🎨 UI/UX Considerations

- **Tablet-friendly**: Nurses will use tablets in clinic
- **Quick search**: Fast patient lookup by ID or name
- **Color coding**: 
  - Red = Emergency/Critical
  - Orange = High priority
  - Yellow = Medium priority
  - Green = Low priority/Normal
- **Print-friendly**: Referral forms should print well

---

## Next Immediate Steps

1. **Run migrations**: `php artisan migrate`
2. **Create Filament resources** for Patient and Visit
3. **Build basic dashboard** with summary widgets
4. **Test patient registration** workflow
5. **Test visit check-in/out** workflow

Start with Patient and Visit resources as they're the core of the system!
