<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('requests')) {
            return;
        }

        Schema::create('requests', function (Blueprint $table) {
            // ID standar
            $table->id();

            // Buat product_id sebagai unsignedBigInteger (sesuai tipe id di products)
            $table->unsignedBigInteger('product_id');

            // jumlah yang diminta
            $table->integer('qty_requested');

            $table->enum('status', ['Pending', 'Approved', 'Completed', 'Rejected'])->default('Pending');
            $table->timestamp('request_date')->useCurrent();
            $table->text('note')->nullable();
            $table->text('response_note')->nullable();

            // index agar lookup lebih cepat
            $table->index('product_id');

            // Tambahkan foreign key hanya jika tabel products sudah ada
            if (Schema::hasTable('products')) {
                $table->foreign('product_id')->references('id')->on('products')
                    ->onUpdate('cascade')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
};