<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip creating the table if it already exists to avoid "table exists" errors
        if (Schema::hasTable('stock_logs')) {
            return;
        }

        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id')->nullable();
            $table->enum('type', ['In', 'Out', 'Adjustment']);
            $table->integer('qty');
            $table->text('note')->nullable();

            // match your SQL intent: created_at default current timestamp
            $table->timestamp('created_at')->useCurrent();

            // keep name compatible with your SQL
            $table->string('user', 50)->default('Admin SBY');

            $table->index('product_id');

            // Tambahkan foreign key hanya jika tabel products sudah ada
            if (Schema::hasTable('products')) {
                $table->foreign('product_id')
                    ->references('id')->on('products')
                    ->onDelete('restrict');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
