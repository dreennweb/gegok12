# Balochistan Single Window Business Registration Portal

## Architecture Overview

This is a comprehensive Laravel 11 application implementing a multi-tenant Single Window Business Registration Portal for Balochistan.

## Key Features

### 1. Dynamic Multi-Step Registration Form
- Supports three business types: Company (SECP), Association of Persons (AOP), Sole Proprietorship
- Conditional logic for department routing
- Support for pre-existing registrations
- Intelligent Worker Welfare Fund (WWF) inclusion based on worker status

### 2. Multi-Tenant Portal Architecture

#### Applicant Portal
- Real-time application status tracking per department
- Action Required tab for department queries
- Document upload and management
- Certificate viewing and download

#### Departmental Portal
Isolated logins for:
- **BRA (Sales Tax & WWF)**: Withholding Agent and Service Provider routing
- **Labour Department**: Worker registration and compliance
- **Excise Department**: Excise goods and regulations
- **BFA**: Food safety and business registration
- **BHC**: Healthcare facility registration

Functionalities:
- Review department-specific applications
- Download documents (signed URLs)
- Issue queries for more information
- Approve/Reject with certificates
- Generate registration certificates

#### Superadmin Portal
- Global configuration and management
- Department staff and executive account management
- System audit logs and monitoring
- Master data management
- Global Excel export across all departments

### 3. Data Import & Export (Excel)
- **Superadmin Level**: Export all registrations globally; Import legacy business data
- **Department Level**: Export department-specific data (pending and approved)

## Database Schema

### Core Tables
- `departments`: Department configuration
- `users`: User accounts
- `businesses`: Business entity data
- `applications`: Master application record
- `department_applications`: Department-specific application workflow
- `documents`: Secure document storage
- `department_users`: Department staff assignments
- `application_queries`: Department queries and responses
- `audit_logs`: Comprehensive audit trail

## Security & Compliance

### Data Isolation
- Query scopes ensure Department A cannot access Department B's data
- Shared master data (NTN, Business Name) is appropriately scoped
- Row-level security via authorization policies

### File Security
- Documents stored in secured storage disk
- Temporary signed URLs for document access (24-hour expiration)
- Proper MIME type validation
- File size restrictions (10MB max)

### Authorization
- Custom authorization policies for Applications and Documents
- Middleware for portal access control
- Role-based access through Laratrust

## Directory Structure

```
app/
├── Console/Commands/          # Artisan commands
├── Exports/                   # Excel export classes
├── Filament/                  # Filament admin resources
│   ├── Pages/
│   ├── Resources/
│   └── Widgets/
├── Http/
│   ├── Controllers/
│   │   ├── API/              # REST API endpoints
│   │   └── Portal/           # Portal controllers
│   ├── Middleware/           # Portal access middleware
│   └── Requests/             # Form validation requests
├── Livewire/                 # Livewire components
├── Models/                   # Eloquent models
├── Notifications/            # Email notifications
├── Policies/                 # Authorization policies
├── Services/                 # Business logic services
└── Traits/                   # Reusable traits

database/
├── migrations/               # All migrations
└── seeders/                  # Database seeders

resources/
├── views/
│   ├── livewire/            # Livewire component views
│   └── portals/             # Portal-specific views
```

## Setup Instructions

### Installation

1. Install dependencies:
```bash
composer install
```

2. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure database in `.env`:
```
DB_CONNECTION=mysql
DB_DATABASE=balochistan_brp
DB_USERNAME=root
DB_PASSWORD=
```

4. Run migrations and seeders:
```bash
php artisan migrate
php artisan db:seed
php artisan app:seed-departments
```

5. Install Filament:
```bash
php artisan filament:install --teams
```

6. Create superadmin user:
```bash
php artisan make:filament-user
```

### Queue Configuration

For production, configure queue connection in `.env`:
```
QUEUE_CONNECTION=redis
```

Run queue worker:
```bash
php artisan queue:work
```

## API Endpoints

### Applications
- `GET /api/applications` - List user applications
- `GET /api/applications/{id}` - Get application details
- `POST /api/applications/{id}/submit` - Submit application

### Documents
- `GET /api/documents/{id}/download` - Download document
- `GET /api/documents/{id}/signed-url` - Get signed URL

## Notifications

System sends notifications for:
- Application submitted
- Department query issued
- Application approved
- Application rejected

## Best Practices Implemented

✅ Service layer for business logic separation
✅ Form Requests for validation
✅ Authorization Policies for data access control
✅ Eloquent relationships and scopes
✅ Query optimization with eager loading
✅ Audit logging for compliance
✅ Secure file storage with temporary URLs
✅ Comprehensive error handling
✅ Queue-based notifications
✅ RBAC with Laratrust

## Configuration Files

- `config/filesystems.php` - Storage disk configuration
- `config/auth-portals.php` - Portal-specific guards
- `config/laratrust.php` - Role and permission configuration

## Development

Run development server:
```bash
composer run dev
```

Run tests:
```bash
composer run test
```

## Support

For issues or questions, please contact: noreply@brp.balochistan.gov.pk
