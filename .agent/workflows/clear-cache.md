---
description: Clear all application caches for debugging or deployment
---

# Workflow: Clearing Laravel Cache

Use this to reset various caches for troubleshooting or after code updates.

1. **Clear Application Cache**
   // turbo
   `php artisan cache:clear`

2. **Clear Config Cache**
   // turbo
   `php artisan config:clear`

3. **Clear Route Cache**
   // turbo
   `php artisan route:clear`

4. **Clear Compiled Views**
   // turbo
   `php artisan view:clear`

5. **Reset Everything (Batch)**
   // turbo
   `php artisan optimize:clear`

6. **Re-optimize for Production**
   // turbo
   `php artisan optimize`
---
## 💡 Troubleshooting
If changes are not appearing in the browser, always run `php artisan optimize:clear`.
Check `storage/logs/laravel.log` for any unexpected errors.
