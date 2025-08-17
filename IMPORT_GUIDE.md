# 🚀 Recruitsy Import Guide

This guide explains how to use the comprehensive import system for tenants (companies) and jobs in the Recruitsy job portal.

## 📋 Available Commands

### 1. Import Tenants (`tenants:import`)

Import companies/tenants from various sources.

#### Options:
- `--and-sync` - Sync jobs after importing tenants
- `--from-file=<path>` - Import tenants from a JSON file
- `--name=<name>` - Company name for manual import
- `--url=<url>` - HR portal URL for manual import
- `--api-key=<key>` - API key for manual import
- `--api-secret=<secret>` - API secret for manual import
- `--list` - List all existing tenants
- `--dry-run` - Show what would be imported without actually importing

#### Examples:

**List existing tenants:**
```bash
php artisan tenants:import --list
```

**Import from HR portal API:**
```bash
php artisan tenants:import
```

**Import from HR portal and sync jobs:**
```bash
php artisan tenants:import --and-sync
```

**Import from JSON file:**
```bash
php artisan tenants:import --from-file="storage/app/sample-tenants.json"
```

**Manual tenant creation:**
```bash
php artisan tenants:import \
  --name="My Company" \
  --url="https://hr.mycompany.com" \
  --api-key="my_api_key" \
  --api-secret="my_api_secret"
```

**Dry run (preview):**
```bash
php artisan tenants:import --from-file="storage/app/sample-tenants.json" --dry-run
```

### 2. Import Jobs (`jobs:import`)

Import jobs from various sources.

#### Options:
- `--from-file=<path>` - Import jobs from a JSON file
- `--company-id=<id>` - Company ID for the jobs
- `--company-name=<name>` - Company name for the jobs
- `--title=<title>` - Job title for manual import
- `--description=<desc>` - Job description for manual import
- `--location=<location>` - Job location for manual import
- `--employment-type=<type>` - Employment type for manual import
- `--salary-min=<min>` - Minimum salary for manual import
- `--salary-max=<max>` - Maximum salary for manual import
- `--currency=<currency>` - Salary currency for manual import
- `--posted-at=<date>` - Job posted date for manual import
- `--apply-url=<url>` - Apply URL for manual import
- `--is-remote=<bool>` - Whether job is remote for manual import
- `--external-id=<id>` - External ID for manual import
- `--list` - List all existing jobs
- `--company=<filter>` - Filter jobs by company ID or name
- `--dry-run` - Show what would be imported without actually importing

#### Examples:

**List existing jobs:**
```bash
php artisan jobs:import --list
```

**List jobs for a specific company:**
```bash
php artisan jobs:import --list --company="TechCorp"
```

**Import from JSON file:**
```bash
php artisan jobs:import --from-file="storage/app/sample-jobs.json" --company-id=2
```

**Manual job creation:**
```bash
php artisan jobs:import \
  --title="Senior Developer" \
  --company-id=1 \
  --location="San Francisco" \
  --employment-type="Full-time" \
  --salary-min=120000 \
  --salary-max=180000 \
  --currency="USD" \
  --is-remote=true
```

**Dry run (preview):**
```bash
php artisan jobs:import --from-file="storage/app/sample-jobs.json" --company-id=2 --dry-run
```

### 3. Import All (`import:all`)

Comprehensive import command that can handle both tenants and jobs.

#### Options:
- `--tenants-only` - Import only tenants
- `--jobs-only` - Import only jobs
- `--from-file=<path>` - Import from a JSON file containing both tenants and jobs
- `--sync-after` - Sync jobs from HR portals after import
- `--dry-run` - Show what would be imported without actually importing

#### Examples:

**Import everything (tenants + jobs):**
```bash
php artisan import:all
```

**Import only tenants:**
```bash
php artisan import:all --tenants-only
```

**Import only jobs:**
```bash
php artisan import:all --jobs-only
```

**Import from combined file:**
```bash
php artisan import:all --from-file="storage/app/sample-combined.json"
```

**Import and then sync:**
```bash
php artisan import:all --sync-after
```

**Dry run (preview):**
```bash
php artisan import:all --from-file="storage/app/sample-combined.json" --dry-run
```

### 4. Sync Jobs (`jobs:sync`)

Sync jobs from HR portals.

#### Options:
- `--company-id=<id>` - Sync jobs for a specific company only

#### Examples:

**Sync all companies:**
```bash
php artisan jobs:sync
```

**Sync specific company:**
```bash
php artisan jobs:sync --company-id=1
```

## 📁 JSON File Formats

### Tenants Only
```json
[
    {
        "name": "Company Name",
        "hr_portal_url": "https://hr.company.com",
        "api_key": "api_key_123",
        "api_secret": "api_secret_456"
    }
]
```

### Jobs Only
```json
{
    "jobs": [
        {
            "company_id": 1,
            "external_id": "job_001",
            "title": "Job Title",
            "description": "Job description...",
            "location": "Location",
            "employment_type": "Full-time",
            "salary_min": 50000,
            "salary_max": 80000,
            "currency": "USD",
            "posted_at": "2024-01-15T10:00:00Z",
            "apply_url": "https://company.com/apply",
            "is_remote": true
        }
    ]
}
```

### Combined (Tenants + Jobs)
```json
{
    "tenants": [
        {
            "name": "Company Name",
            "hr_portal_url": "https://hr.company.com",
            "api_key": "api_key_123",
            "api_secret": "api_secret_456"
        }
    ],
    "jobs": [
        {
            "company_id": 1,
            "title": "Job Title",
            "location": "Location",
            "employment_type": "Full-time"
        }
    ]
}
```

## 🔧 Configuration

### Environment Variables

Add these to your `.env` file:

```env
# HR Portal API Configuration
HR_PORTAL_BASE_URL=https://your-hr-portal.com
HR_PORTAL_API_KEY=your_api_key
HR_PORTAL_API_SECRET=your_api_secret

# Recruitsy Sync Token (for API endpoints)
RECRUITSY_SYNC_TOKEN=your_sync_token
```

### Services Configuration

The system uses Laravel's services configuration. Ensure these are set in `config/services.php`:

```php
'hr_portal' => [
    'base_url' => env('HR_PORTAL_BASE_URL'),
    'api_key' => env('HR_PORTAL_API_KEY'),
    'api_secret' => env('HR_PORTAL_API_SECRET'),
],

'recruitsy' => [
    'sync_token' => env('RECRUITSY_SYNC_TOKEN'),
],
```

## 🚨 Error Handling

All commands include comprehensive error handling:

- **Validation errors** are logged and displayed
- **API failures** are logged with details
- **Database errors** are caught and reported
- **File read errors** are handled gracefully

## 📊 Monitoring

Use the `--list` options to monitor your data:

```bash
# Check tenants
php artisan tenants:import --list

# Check jobs
php artisan jobs:import --list

# Check jobs for specific company
php artisan jobs:import --list --company="Company Name"
```

## 🔄 Automation

### Cron Jobs

Add these to your crontab for automated imports:

```bash
# Import tenants daily at 2 AM
0 2 * * * cd /path/to/your/app && php artisan tenants:import

# Sync jobs every 4 hours
0 */4 * * * cd /path/to/your/app && php artisan jobs:sync

# Full import weekly on Sunday at 3 AM
0 3 * * 0 cd /path/to/your/app && php artisan import:all
```

### Queue Jobs

For large imports, the system automatically uses Laravel queues:

```bash
# Process queued import jobs
php artisan queue:work
```

## 🧪 Testing

Test your imports with dry runs first:

```bash
# Test tenant import
php artisan tenants:import --from-file="sample.json" --dry-run

# Test job import
php artisan jobs:import --from-file="jobs.json" --company-id=1 --dry-run

# Test combined import
php artisan import:all --from-file="combined.json" --dry-run
```

## 📝 Best Practices

1. **Always use dry runs first** to preview what will be imported
2. **Validate your JSON files** before importing
3. **Monitor logs** for any import issues
4. **Use specific company IDs** when importing jobs
5. **Test with small datasets** before large imports
6. **Backup your database** before major imports
7. **Use the sync functionality** to keep data up-to-date

## 🆘 Troubleshooting

### Common Issues

**"No tenants were imported"**
- Check your HR portal API configuration
- Verify API credentials are correct
- Check network connectivity to HR portal

**"Jobs import failed"**
- Ensure company ID exists
- Check job data format
- Verify required fields are present

**"File not found"**
- Check file path is correct
- Ensure file has proper permissions
- Verify JSON format is valid

### Getting Help

- Check Laravel logs in `storage/logs/`
- Use `--dry-run` to debug import issues
- Verify environment variables are set
- Check database connection and permissions
