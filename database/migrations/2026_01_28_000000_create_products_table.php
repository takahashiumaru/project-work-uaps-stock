<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            return;
        }

        Schema::create('products', function (Blueprint $table) {
            // Engine/charset/collation sesuai SQL
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id(); // bigint unsigned auto-increment
            $table->string('sku', 50)->unique();
            $table->string('name', 100);
            $table->string('category', 50)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(10);
            $table->string('unit', 20)->default('Pcs');
            $table->string('location', 50)->nullable();

            // last_updated default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            // useCurrentOnUpdate() tersedia di Laravel modern; fallback to useCurrent() if needed
            if (method_exists($table, 'useCurrentOnUpdate')) {
                $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            } else {
                $table->timestamp('last_updated')->useCurrent();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
