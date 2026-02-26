<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->date('work_date');
            $table->string('file_path', 255)->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            // pakai timestamps standar Laravel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_reports');
    }
};
