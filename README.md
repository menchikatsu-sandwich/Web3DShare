# Web3DShare

> A community platform for publishing, discovering, previewing, and downloading 3D models.

Web3DShare is a Laravel application built around shareable GLB assets. Creators can upload models, manage their published work, build a public profile, and request verified-uploader status. Community members can browse models, star them, download them, join discussions, report content that needs moderation, view creator analytics, and moderate flagged items through the admin / moderator panels.

## Table Of Contents

- [Changelog](#changelog)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Architecture](#architecture)
- [Requirements](#requirements)
- [Local Setup](#local-setup)
- [Environment Configuration](#environment-configuration)
- [Database](#database)
- [API](#api)
- [Roles And Access](#roles-and-access)
- [Project Structure](#project-structure)
- [Quality Checks](#quality-checks)
- [Deployment Notes](#deployment-notes)

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## Features

### Community And Discovery

- Explore models by search term, category, tag, timeframe, and popularity.
- Preview GLB models in the browser with `model-viewer`.
- View creator profiles with published-model and engagement statistics.
- Light and dark themes, onboarding guidance, and responsive Blade views.

### Creator Workflow

- Upload GLB models with thumbnail images, categories, descriptions, and tags.
- Manage owned models from **My Models**; metadata can be edited and models can be deleted.
- Configurable monthly upload limit for basic uploaders.
- Verification request flow with minimum model, download, account-age, pending-request, and rejection-cooldown rules.
- Profile nickname and image updates.

### Engagement And Moderation

- Stars, download metrics, threaded comments, and comment deletion permissions.
- Download and view cooldowns to reduce repeated metric inflation; owners do not increase their own download count.
- Model reporting with duplicate-report protection.
- Moderator and admin panel for reports, verification requests, categories, users, and model moderation.

### API

- Web routes for the Blade application and versionless JSON routes under `/api`.
- Laravel Sanctum token authentication for protected API routes.
- Consistent JSON responses and rate limiting for authentication, uploads, comments, reports, downloads, and administrative actions.
- A Postman walkthrough is available in [api_postman_guide.txt](api_postman_guide.txt).

## Technology Stack

| Layer           | Technology                                        |
| --------------- | ------------------------------------------------- |
| Backend         | Laravel 13, PHP 8.3+                              |
| Database        | PostgreSQL                                        |
| Authentication  | Laravel session auth and Laravel Sanctum          |
| Storage         | Supabase Storage through its HTTP API             |
| Frontend        | Blade, Tailwind CSS 4, Vite                       |
| 3D Preview      | Google `model-viewer`                             |
| Testing         | PHPUnit                                           |
| Code Quality    | Laravel Pint, Prettier, Prettier Tailwind plugin  |
| Container Build | Dockerfile for PHP 8.3 with PostgreSQL extensions |

## Architecture

```text
Browser / API Client
        |
        v
Routes -> Middleware -> Controllers -> Services / Policies / Models
        |                                  |
        |                                  +-> Supabase Storage
        v
PostgreSQL <-> Eloquent Models
```

- **Controllers** coordinate requests and responses.
- **Form Requests** hold input validation for forms and API commands.
- **Policies** authorize model, comment, and report actions.
- **Services** contain Supabase storage and cached metadata behavior.
- **Observers** clean model files from storage when models are removed.

## Requirements

- PHP `8.3` or newer with PostgreSQL extensions (`pdo_pgsql`, `pgsql`)
- Composer 2
- Node.js and npm
- PostgreSQL database
- Supabase project and a storage bucket for model assets

## Local Setup

1. Clone the repository and enter the project directory.

    ```bash
    git clone <your-repository-url> web3dshare
    cd web3dshare
    ```

2. Install backend and frontend dependencies.

    ```bash
    composer install
    npm install
    ```

3. Create the local environment file and application key.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Configure the database and Supabase values in `.env`. See [Environment Configuration](#environment-configuration).

5. Run migrations and build frontend assets.

    ```bash
    php artisan migrate
    npm run build
    ```

6. Start the development stack.

    ```bash
    composer dev
    ```

    This starts Laravel, the queue listener, Laravel Pail, and Vite together. For a minimal server-only session, use:

    ```bash
    php artisan serve
    ```

Open `http://127.0.0.1:8000` in the browser.

## Environment Configuration

Start from `.env.example`. Keep `.env` local and never commit its secret values.

| Variable              | Purpose                                                                        |
| --------------------- | ------------------------------------------------------------------------------ |
| `APP_ENV`             | Use `local` for development and `production` when deployed.                    |
| `APP_DEBUG`           | Keep `true` locally; set to `false` in production.                             |
| `APP_URL`             | Public application URL.                                                        |
| `DB_CONNECTION`       | Database driver; this project uses `pgsql`.                                    |
| `DB_URL`              | PostgreSQL connection URL.                                                     |
| `SUPABASE_URL`        | Supabase project URL.                                                          |
| `SUPABASE_SECRET_KEY` | Server-only key used for storage operations. Never expose it in frontend code. |
| `AWS_BUCKET`          | Supabase Storage bucket name. Defaults to `model-assets`.                      |
| `WEB3D_*`             | Upload limits, engagement cooldowns, and metadata-cache settings.              |
| `VERIFY_*`            | Verification eligibility and cooldown settings.                                |

The storage service also accepts the legacy `SUPABASE_SERVICE_ROLE_KEY` fallback. Prefer the current server-only secret key configuration and keep both forms of privileged credentials out of Git.

## Database

The application has migrations for users, models, categories, tags, comments, stars, downloads, views, reports, verification requests, sessions, personal access tokens, and performance indexes.

```bash
php artisan migrate
```

To rebuild only a disposable local database:

```bash
php artisan migrate:fresh
```

Do not run `migrate:fresh` against a database containing data you want to keep.

## API

Public API endpoints are served under `/api`. Protected endpoints use a Sanctum bearer token obtained from `POST /api/login` or `POST /api/register`.

```http
Authorization: Bearer <token>
Accept: application/json
```

Common endpoints:

| Method | Endpoint                      | Description                                  |
| ------ | ----------------------------- | -------------------------------------------- |
| `GET`  | `/api/models`                 | Browse and filter models.                    |
| `GET`  | `/api/models/{model}`         | Get a model and related data.                |
| `POST` | `/api/register`               | Register and receive a token.                |
| `POST` | `/api/login`                  | Authenticate and receive a token.            |
| `POST` | `/api/models`                 | Upload a model. Requires a token.            |
| `POST` | `/api/models/{model}/star`    | Toggle a star. Requires a token.             |
| `POST` | `/api/models/{model}/comment` | Create a comment or reply. Requires a token. |
| `POST` | `/api/models/{model}/report`  | Report a model. Requires a token.            |
| `GET`  | `/api/creators/{username}`    | View a creator profile.                      |

See [api_postman_guide.txt](api_postman_guide.txt) for full request headers, bodies, authentication, and Postman steps.

## Roles And Access

| Role              | Access                                                                                                                    |
| ----------------- | ------------------------------------------------------------------------------------------------------------------------- |
| Guest             | Browse models, creator profiles, and public model pages.                                                                  |
| User              | Upload, manage own models, interact, report, and request verification.                                                    |
| Verified uploader | Exempt from the basic monthly upload limit.                                                                               |
| Moderator         | Staff access with unlimited uploads; can review reports, verification requests, categories, and model moderation actions. |
| Admin             | Staff access with unlimited uploads; has all moderator access plus user promotion, demotion, and deletion.                |

Authorization is enforced on the server through route middleware and policies; hiding a UI control is not treated as authorization.

## Project Structure

```text
web3dshare/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php      # Moderator and admin workflows
│   │   │   ├── Auth/                    # Registration, login, logout
│   │   │   ├── Concerns/                # Shared controller response helpers
│   │   │   ├── InteractionController.php# Stars, comments, downloads
│   │   │   ├── ModelController.php      # Explore, upload, edit, delete, viewer
│   │   │   ├── ProfileController.php    # Profile and creator pages
│   │   │   ├── ReportController.php     # Model reports
│   │   │   ├── UploadPageController.php # Upload workflow UI page
│   │   │   └── VerifyController.php     # Uploader verification lifecycle
│   │   ├── Middleware/                  # Roles, JSON, security headers
│   │   └── Requests/                   # Validation contracts per command
│   ├── Models/                        # Eloquent models and relationships
│   ├── Observers/                     # Model lifecycle cleanup
│   ├── Policies/                      # Server-side authorization rules
│   ├── Providers/                     # Policies, observers, rate limiters
│   └── Services/                      # Supabase storage and metadata cache
│
├── bootstrap/
│   ├── app.php                        # Application routing and middleware setup
│   └── providers.php                  # Registered service providers
│
├── config/
│   ├── auth.php                       # Browser authentication guard
│   ├── cache.php / queue.php           # Runtime storage and queue settings
│   ├── database.php                   # PostgreSQL connection configuration
│   ├── filesystems.php                # Filesystem and S3-compatible settings
│   ├── sanctum.php                    # API token configuration
│   ├── services.php                   # External service configuration
│   └── web3dshare.php                 # Product limits, cooldowns, cache, verification
│
├── database/
│   ├── factories/                     # Test model factories
│   ├── migrations/                    # Schema, relationships, and performance indexes
│   └── seeders/                       # Optional initial database data
│
├── docker/
│   └── render-start.sh                # Container startup and optional migration script
├── docs/
│   └── CODE_STYLE.md                  # SDLC, formatting, and code ownership conventions
│
├── public/
│   ├── build/                         # Generated Vite production assets; do not edit manually
│   └── index.php                      # Public Laravel entry point
│
├── resources/
│   ├── css/app.css                    # Tailwind theme, custom utilities, shared visual styles
│   ├── js/app.js                      # Vite JavaScript entry point
│   └── views/
│       ├── admin/                     # Moderator and admin panel
│       ├── auth/                      # Login and registration pages
│       ├── components/                # Reusable Blade controls and onboarding tour
│       ├── creator/                   # Public creator profile page
│       ├── layouts/                   # Shared application and admin shells
│       ├── legal/                     # Terms, access rules, and agreement text
│       ├── model/                     # Viewer, partial modal, and threaded comments
│       ├── profile/                   # Signed-in user profile editor
│       ├── reports/                   # Reports history and notification
│       ├── upload/                    # Upload workflow
│       └── verify/                    # Verification request workflow
│
├── routes/
│   ├── api.php                        # JSON API for Postman and external clients
│   ├── console.php                    # Artisan console routes
│   └── web.php                        # Browser routes, auth groups, role groups
│
├── storage/                           # Logs, cached views, sessions, framework files
├── tests/
│   ├── Feature/                       # End-to-end HTTP and workflow tests
│   └── Unit/                          # Isolated domain tests
│
├── .env.example                       # Safe environment-variable template
├── api_postman_guide.txt              # Endpoint-by-endpoint Postman instructions
├── composer.json / composer.lock       # PHP dependencies and Composer scripts
├── Dockerfile                          # PHP 8.3 container build
├── package.json / package-lock.json    # Frontend dependencies and npm scripts
├── phpunit.xml                         # PHPUnit configuration
├── vite.config.js                     # Vite and Laravel asset integration
└── README.md                           # Project handover and onboarding document
```

### Handover Map

When taking over the project, trace a feature in this order:

1. Start in `routes/web.php` or `routes/api.php` to find the public contract and middleware.
2. Read the matching controller to understand orchestration and response behavior.
3. Check `app/Http/Requests` for validation, `app/Policies` for authorization, and `app/Services` for shared or external integration logic.
4. Inspect the relevant model, migration, and index before changing stored data or queries.
5. Update the matching Blade view or component for browser behavior, then add focused coverage under `tests/`.
6. Run the checks in [Quality Checks](#quality-checks) before opening a pull request or deploying.

Avoid changing generated files under `public/build/`, cached files under `storage/framework/`, or secret values in `.env` directly in a commit.

## Quality Checks

```bash
# Run tests
composer test

# Format or verify PHP formatting
composer format
composer format:check

# Format or verify Blade, CSS, JavaScript, and Tailwind class order
npm run format
npm run format:check

# Build production assets
npm run build
```

Follow the conventions in [docs/CODE_STYLE.md](docs/CODE_STYLE.md) when extending the application.

## Deployment Notes

- Build frontend assets with `npm run build` before deployment.
- Use `composer install --no-dev --optimize-autoloader` for production dependencies.
- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Run migrations deliberately with `php artisan migrate --force`.
- Ensure the runtime has write access to `storage/` and `bootstrap/cache/`.
- Configure the same PostgreSQL and Supabase Storage credentials as the target environment.
- The repository contains a `Dockerfile` and `docker/render-start.sh` for container-oriented deployment workflows.

## License

No license has been specified for this repository.
