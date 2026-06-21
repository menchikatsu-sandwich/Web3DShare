# Web3DShare Code Style

## Goal

Keep behavior explicit, validation close to its use case, and shared UI patterns in one place. Formatting is automated; code review should focus on behavior, security, and maintainability.

## Laravel boundaries

- **Controllers** coordinate a request and response. Keep database workflows and integrations out of large controller methods when they can become shared behavior.
- Prefer **guard clauses** for invalid access, exhausted limits, duplicate requests, and no-op work. Return early, then leave the successful path at the lowest indentation level.
- **Form Requests** in `app/Http/Requests` own validation and validation messages. Add a new request class when a form or endpoint gains a non-trivial input contract.
- **Policies** decide whether an authenticated user may perform an action. Do not trust a hidden UI control as authorization.
- **Services** handle integrations and reusable workflows, such as Supabase storage or cached metadata.
- **Models** represent relationships, casts, scopes, and small domain behavior. Avoid turning them into all-purpose service classes.

`web` is the browser session guard. Sanctum authenticates API tokens. Guards answer “who is this request's user?”; policies answer “may this user do this action?”. Neither should be used to organize CSS.

## Blade and Tailwind

- Use `@class([...])` for classes that depend on conditions instead of building class strings manually.
- Add reusable controls under `resources/views/components/ui`. Use `<x-ui.button>` for standard primary, secondary, warning, and danger actions.
- Keep one-off layout classes in the page that owns them. Extract only patterns used in at least two places.
- Use semantic `button` elements for actions and links for navigation.

## Comments

- Explain _why_ a decision exists, especially for permissions, caching, limits, external APIs, and unusual browser behavior.
- Do not narrate an obvious assignment or repeat what the method name already says.
- Keep comments current when behavior changes.

## Daily commands

```bash
composer format       # Apply Laravel Pint to PHP files.
composer format:check # Check PHP formatting without changing files.
npm run format        # Format Blade, CSS, and JavaScript with Tailwind class sorting.
npm run format:check  # Check frontend formatting in CI or before a commit.
composer test         # Clear config and run the test suite.
```

Run the formatters before committing. New behavior should receive focused feature coverage, especially around authorization, validation, and API responses.
