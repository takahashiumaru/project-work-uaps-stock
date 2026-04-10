---
description: Refresh database and seed initial or dummy data
---

# Workflow: Database Reset & Seeding

Use this to quickly reset the database and seed it with necessary data.

1. **Refresh Migrations**
   Resets and runs all migrations from scratch.
   // turbo
   `php artisan migrate:refresh`

2. **Seed Data**
   Runs the database seeders defined in `DatabaseSeeder.php`.
   // turbo
   `php artisan db:seed`

3. **Check Connection**
   If the migration fails, verify the `.env` settings for DB connection.
   // turbo
   `php artisan db:show`

4. **Wipe Database (Caution)**
   Only if standard refresh fails:
   // turbo
   `php artisan db:wipe && php artisan migrate`
