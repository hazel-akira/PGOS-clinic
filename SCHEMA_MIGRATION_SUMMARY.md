# Database Schema Migration Summary

## ✅ All Migrations Created

All 25+ migrations have been created matching your comprehensive database schema.

### Identity & Enrollment (4 tables)
1. ✅ `persons` - Core identity table (STUDENT, STAFF, VISITOR)
2. ✅ `student_enrolments` - Student-specific enrollment data
3. ✅ `guardians` - Guardian contact information
4. ✅ `guardian_links` - Many-to-many relationship between students and guardians

### Medical Profile (4 tables)
5. ✅ `medical_profiles` - Long-lived medical profile per person
6. ✅ `allergies` - Detailed allergy records with severity
7. ✅ `chronic_conditions` - Chronic condition tracking
8. ✅ `immunizations` - Vaccination records

### Clinic Visits (5 tables)
9. ✅ `visits` - Main visit records (updated to match new schema)
10. ✅ `vitals` - Vital signs measurements
11. ✅ `diagnoses` - Diagnosis records with ICD10 support
12. ✅ `treatments` - Treatment procedures performed
13. ✅ `referrals` - Hospital referral tracking

### Pharmacy & Inventory (5 tables)
14. ✅ `items` - Medication/item catalog
15. ✅ `stock_batches` - Batch tracking with expiry dates
16. ✅ `stock_txns` - Stock transactions (RECEIVE, ISSUE, ADJUST, etc.)
17. ✅ `prescriptions` - Prescription records
18. ✅ `suppliers` - Supplier information

### Safety & Compliance (3 tables)
19. ✅ `incidents` - Incident reporting (injuries, accidents, etc.)
20. ✅ `consents` - Consent management with versioning
21. ✅ `notifications` - Communication tracking (SMS, Email, etc.)

### Documents & Attachments (1 table)
22. ✅ `attachments` - File attachments for various entities

### Security & Audit (4 tables)
23. ✅ `app_users` - Application users (separate from Laravel users)
24. ✅ `roles` - Role definitions
25. ✅ `user_roles` - User-role assignments
26. ✅ `audit_logs` - Comprehensive audit trail
27. ✅ `data_subject_requests` - GDPR data subject requests
28. ✅ `data_breaches` - Data breach tracking

### Migration Order Fix
- ✅ Created separate migration to add foreign key constraint for `visits.created_by_user_id` after `app_users` table exists

---

## ⚠️ Important Notes

### Old Migrations to Remove
The following old migrations should be **removed or not run** as they're replaced by the new schema:

- `2026_01_19_121126_create_patients_table.php` → Replaced by `persons` + `student_enrolments`
- `2026_01_19_121128_create_medications_table.php` → Replaced by `items`
- `2026_01_19_121129_create_inventory_table.php` → Replaced by `stock_batches` + `stock_txns`
- `2026_01_19_121130_create_visit_medications_table.php` → Replaced by `prescriptions`

**Action Required:** Delete or rename these old migration files before running migrations.

---

## 🚀 Next Steps

1. **Remove old migrations** (listed above)
2. **Run migrations:**
   ```bash
   php artisan migrate
   ```
3. **Create Eloquent models** matching the new schema
4. **Set up Filament resources** for the new models

---

## 📋 Key Schema Features

### UUID Primary Keys
All tables use UUID primary keys for better privacy and security.

### Proper Relationships
- Persons → Student Enrolments (one-to-one)
- Persons → Guardians (many-to-many via guardian_links)
- Persons → Visits (one-to-many)
- Visits → Vitals, Diagnoses, Treatments (one-to-many)
- Items → Stock Batches (one-to-many)
- Stock Batches → Stock Transactions (one-to-many)

### Audit & Compliance
- Comprehensive audit logging
- Data subject request tracking (GDPR)
- Data breach management
- Consent versioning

### Indexes
All key indexes have been added as specified:
- `visit(person_id, arrival_at desc)`
- `vitals(visit_id, taken_at desc)`
- `stock_batch(item_id, expiry_date)`
- `stock_txn(batch_id, performed_at desc)`
- `audit_log(entity_type, entity_id, timestamp desc)`

---

## 🔗 Integration with Laravel Users

**Note:** The schema uses `app_users` table separate from Laravel's `users` table. You'll need to:

1. Either sync `app_users` with Laravel `users` table
2. Or use Laravel `users` table and map to `app_users` when needed
3. Or use `app_users` as the primary user table and extend Laravel authentication

This is a design decision you'll need to make based on your authentication strategy (Azure SSO uses Laravel users, but clinic operations use app_users).





