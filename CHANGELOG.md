# Changelog

All notable changes to Web3DShare will be documented in this file.

## 2026 June — Current Changes

### Report Module Overhaul (v1)

- **AdminController** (`app/Http/Controllers/AdminController.php`): added admin bypass for `isStaff` policy check and upgraded the admin dashboard UI; handles report review, verification request review, category management, user model actions.
- **ReportModule**: reworked the report system with a new `myreports` list page on each user's profile; reports now include task-reply features; model-viewer page category and tag UI fix (`app/Http/Controllers/ModelController.php`).
- **Report Filtering**: added filtering capability for reports and improved comment styles.
- **Admin Panel Layout Fix**: corrected JavaScript rendering in the admin layout so forms and modals load correctly.

### Interaction & Engagement Updates (v2)

- **InteractionController** (`app/Http/Controllers/InteractionController.php`): AJAX support via `ForceJsonResponse` middleware, rate limiting tightened for stars, comments, reports, downloads; owners can no longer inflate their own download counts.
- **ModelController** (`app/Http/Controllers/ModelController.php`): enhanced user-side delete and edit operations with proper ACL checks; uploader limits enforced by configurable monthly quota + cooldown rules; pending upload queue display fixed.
- **View Profile / Creator Page**: profile pages include model engagement statistics (views, stars, downloads); `myuploads`, `publishedmodels`, and public creator profiles render correctly (`app/Http/Controllers/ProfileController.php`).

### Verification & Upload Lifecycle (v3)

- Added full verification request flow with cooldown timers (minimum models owned, minimum total downloads, account age, pending-request count per user, rejection-cooldown window).
- Onboarding improvements: EULA and RoA acceptance gates on registration.
- Supabase storage migration (`legacy SUPABASE_SERVICE_ROLE_KEY` → new `SUPABASE_SECRET_KEY` server-only key + new bucket handling).

### UI & Navigation (v4)

- Fixed `app/Http/Controllers/LayoutTemplate.php` to render admin pages, model modals, and upload verification forms consistently.
- Theme switcher bug-fixed; light / dark mode stable across all page templates.
