---
description: Create a new feature with (Model, Migration, Controller)
---

# Workflow: Creating a New Laravel Feature

Use this workflow to quickly generate the basic components for a new model or feature.

1. **Generate Model & Migration**
   Run the artisan command to create a model and its migration:
   // turbo
   `php artisan make:model {ModelName} -m`

2. **Generate Controller**
   Run the artisan command to create a controller (usually resource or simple):
   // turbo
   `php artisan make:controller {ModelName}Controller --resource`

3. **Define DB Schema**
   Edit the migration file in `database/migrations/xxxx_xx_xx_create_{model_name}_table.php`.

4. **Define Model Fields**
   Update the `$fillable` or `$guarded` array in `app/Models/{ModelName}.php`.

5. **Register Route**
   Add the resource route in `routes/web.php` or `routes/api.php`:
   ```php
   Route::resource('{model_name}', {ModelName}Controller::class);
   ```

6. **Test the Endpoint**
   Verify if the controller works by visiting the newly created route.
