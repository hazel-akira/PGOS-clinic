<?php

/**
 * Audit Logging Configuration
 *
 * This file prepares the system for audit logging of sensitive medical data access.
 * For production, consider using packages like:
 * - spatie/laravel-activitylog
 * - owen-it/laravel-auditing
 *
 * Key requirements for healthcare compliance:
 * - Log all access to patient records
 * - Log all modifications to medical data
 * - Maintain immutable audit trails
 * - Regular audit log reviews
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Enable Audit Logging
    |--------------------------------------------------------------------------
    |
    | Set to true to enable audit logging for sensitive operations.
    |
    */
    'enabled' => env('AUDIT_LOGGING_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Audit Log Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "database", "file", "custom"
    |
    */
    'driver' => env('AUDIT_LOG_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Events to Audit
    |--------------------------------------------------------------------------
    |
    | List of events that should be audited.
    |
    */
    'events' => [
        'patient.created',
        'patient.updated',
        'patient.deleted',
        'patient.viewed',
        'visit.created',
        'visit.updated',
        'visit.deleted',
        'medication.dispensed',
        'medication.updated',
        'user.login',
        'user.logout',
        'permission.granted',
        'permission.revoked',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sensitive Fields
    |--------------------------------------------------------------------------
    |
    | Fields that should be redacted or encrypted in audit logs.
    |
    */
    'sensitive_fields' => [
        'password',
        'ssn',
        'medical_record_number',
        'diagnosis',
        'treatment_notes',
    ],

    /*
    |--------------------------------------------------------------------------
    | Retention Period (days)
    |--------------------------------------------------------------------------
    |
    | How long to retain audit logs (compliance requirement).
    |
    */
    'retention_days' => env('AUDIT_RETENTION_DAYS', 2555), // ~7 years for healthcare
];
