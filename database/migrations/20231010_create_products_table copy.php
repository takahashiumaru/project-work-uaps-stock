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
        Schema::create('requests', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('qty_requested')->notNullable();
            $table->enum('status', ['Pending', 'Approved', 'Completed', 'Rejected'])->default('Pending');
            $table->timestamp('request_date')->useCurrent();
            $table->text('note')->nullable();
            $table->text('response_note')->nullable();
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
