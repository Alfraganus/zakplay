# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application (PHP 8.2+) with a modular architecture designed for a driving test platform. The application features:
- Driver management and leaderboards
- Test system with question/answer functionality
- Weather integration
- Car model management
- Multi-language support using Spatie translatable
- API-first design with Swagger documentation

## Development Commands

### Development Server
```bash
# Start development server with queue worker and Vite
composer run dev

# Or individually:
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```

### Frontend
```bash
npm run dev    # Start Vite development server
npm run build  # Build for production
```

### Testing
```bash
./vendor/bin/phpunit              # Run all tests
./vendor/bin/phpunit tests/Unit   # Run unit tests only
./vendor/bin/phpunit tests/Feature # Run feature tests only
```

### Code Quality
```bash
./vendor/bin/pint                 # Format PHP code (Laravel Pint)
```

### Database
```bash
php artisan migrate               # Run migrations
php artisan migrate:fresh         # Fresh migration
php artisan db:seed              # Run seeders
```

## Architecture

### Modular Structure
- **Core API Controllers**: `app/Http/Controllers/Api/` - Main API endpoints
- **Modular Features**: `app/Modules/test/` - Self-contained modules with their own controllers and routes
- **Helper Services**: `app/Helpers/` - Utility classes for media, caching, multilingual support
- **Business Logic**: `app/Service/` - Service layer for complex operations

### Key Components

#### Authentication & Authorization
- Uses Laravel Sanctum for API authentication
- Auth endpoints: `/register`, `/login`, `/getme`

#### Test System Module
Located in `app/Modules/test/`:
- Department management
- Test creation and management
- Question management with multiple types
- Answer submission and results tracking
- Multi-language question support

#### API Documentation
- Swagger/OpenAPI documentation using darkaonline/l5-swagger
- Swagger classes in `app/Swagger/` define API documentation
- Access at `/api/documentation` (typical L5-Swagger route)

#### Multi-Language Support
- Uses Spatie Laravel Translatable package
- Helper services: `MultiLanguageModelService`, `MultiLanguageModelSelectService`
- Supports content translation across models

#### Media Management
- Uses Spatie Laravel Media Library
- `MediaHelper` for file handling operations

### Database Architecture
- Standard Laravel migrations in `database/migrations/`
- Models follow Laravel conventions with additional multi-language support
- Key entities: Driver, CarModel, Leaderboard, Test-related tables

### Frontend
- Vite build system with Laravel plugin
- TailwindCSS 4.0 for styling
- Assets in `resources/css/` and `resources/js/`

### API Routes Structure
- Main API routes: `routes/api.php`
- Module routes: `app/Modules/test/Routes.php` (included in main routes)
- Grouped routes with middleware for authentication

## Development Notes

### Environment Setup
- Copy `.env.example` to `.env` and configure
- Database uses SQLite for testing (configured in phpunit.xml)
- Queue connection defaults to 'sync' in testing

### Testing Configuration
- PHPUnit configured with separate Unit and Feature test suites
- Uses in-memory SQLite for testing
- Test environment variables set in `phpunit.xml`

### Cache and Performance
- `RedisCacher` helper for caching operations
- Queue system configured (check `.env` for QUEUE_CONNECTION)

### Media and File Handling
- Media library integration for file uploads
- Helper methods available in `MediaHelper`