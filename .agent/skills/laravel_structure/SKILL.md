---
name: Laravel Structure Expert
description: A skill to quickly understand the standard Laravel directory structure and naming conventions to save tokens and improve efficiency.
---

# Laravel Directory Organization Guide

This skill provides a high-level map of the Laravel framework structure. Use this to locate files quickly without extensive directory listing.

## 📂 Core Application (`/app`)
- **`Models/`**: Eloquent models representing database tables logic.
- **`Http/Controllers/`**: Logic for handling web and API requests.
- **`Http/Middleware/`**: Request filters (authentication, logging, etc.).
- **`Http/Requests/`**: Custom Form Request classes for validation.
- **`Providers/`**: Service providers for bootstrapping the application.
- **`Services/`** (Common Pattern): Custom business logic classes to keep controllers thin.
- **`Repositories/`** (Common Pattern): Data access logic abstraction.

## 📂 Database (`/database`)
- **`migrations/`**: PHP classes for defining and modifying database schema.
- **`seeders/`**: Initial or dummy data population.
- **`factories/`**: Blueprint definitions for generating model instances for tests/seeding.

## 📂 Routing & Entry Points (`/routes`)
- **`web.php`**: Routes for the browser interface (includes CSRF protection).
- **`api.php`**: Routes for API endpoints (stateless, uses `api` middleware).
- **`console.php`**: Custom artisan command definitions.

## 📂 Frontend & Assets (`/resources`)
- **`views/`**: Blade templates for HTML rendering.
- **`js/`**: Vue, React, or vanilla JS components.
- **`css/`**: Styling files (Sass, Tailwind, or standard CSS).
- **`lang/`**: Localization and translation strings.

## 📂 Configuration & Public (`/config` & `/public`)
- **`/config/`**: Global settings (app, database, mail, etc.).
- **`/public/`**: Publicly accessible assets (images, compiled CSS/JS, `index.php`).

## 📂 Storage & Logs (`/storage`)
- **`logs/`**: Application log files.
- **`framework/`**: Cached templates, session data, and other internal files.
- **`app/public/`**: User-uploaded files (usually symlinked to `/public/storage`).

## 📂 Testing (`/tests`)
- **`Feature/`**: Integration tests (testing routes and full request cycles).
- **`Unit/`**: Isolated logic tests (testing single methods/classes).

---
## 🔄 Logic Flow (Typical Web/API Request)
1. **Entry**: `public/index.php` -> `bootstrap/app.php`.
2. **Routing**: `routes/web.php` or `routes/api.php` maps the URL to a controller.
3. **Validation**: `app/Http/Requests` (optional) validates the incoming data.
4. **Handling**: `app/Http/Controllers` handles logic (often delegating to `app/Services`).
5. **Data**: `app/Models` interacts with the DB via Eloquent.
6. **Response**: `resources/views` (Blade) for HTML or a JSON response for APIs.

## 🔍 Common Searching Patterns
- **Routes**: `routes/*.php`
- **Logic**: `app/Http/Controllers/*.php` or `app/Services/*.php`
- **Templates**: `resources/views/**/*.blade.php`
- **DB Schema**: `database/migrations/*.php`
- **Config**: `config/*.php`
- **Tests**: `tests/Feature/*.php`

## 💡 Efficiency Tips
1. **Namespace Guessing**: Laravel follows PSR-4. `App\Models\User` is always in `app/Models/User.php`.
2. **Reverse Search**: If you find a route in `web.php` pointing to `[PostController::class, 'index']`, search in `app/Http/Controllers/PostController.php`.
3. **Check .env**: Always check the root `.env` for database connections and API keys before debugging connection issues.
