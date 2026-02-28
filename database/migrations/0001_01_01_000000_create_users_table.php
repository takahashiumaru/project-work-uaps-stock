<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel users hanya jika belum ada
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                // Set engine, charset dan collation agar sesuai dengan CREATE TABLE SQL
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';

                $table->id(); // bigint unsigned auto-increment
                $table->string('fullname'); // NOT NULL
                $table->string('email')->unique(); // NOT NULL UNIQUE
                $table->string('password'); // NOT NULL
                $table->boolean('is_active')->default(true); // tinyint(1) default 1
                $table->enum('gender', ['Male', 'Female']); // enum NOT NULL
                $table->string('job_title'); // NOT NULL
                $table->string('role')->nullable(); // DEFAULT NULL
                $table->string('station'); // NOT NULL
                $table->string('cluster')->nullable();
                $table->string('unit')->nullable();
                $table->string('sub_unit')->nullable();
                $table->string('status')->nullable();
                $table->string('manager')->nullable();
                $table->string('senior_manager')->nullable();
                $table->boolean('is_qantas')->default(false); // tinyint(1) default 0
                $table->date('join_date'); // NOT NULL
                $table->string('salary')->default('0'); // DEFAULT '0'
                $table->date('contract_start')->nullable();
                $table->date('contract_end')->nullable();
                $table->string('phone')->nullable();
                $table->string('pendidikan')->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->string('tempat_lahir')->nullable();
                $table->string('domisili')->nullable();
                $table->text('alamat')->nullable();
                $table->string('no_nik')->nullable();
                $table->string('no_kk')->nullable();
                $table->string('npwp')->nullable();
                $table->string('no_pas')->nullable();
                $table->date('pas_registered')->nullable();
                $table->date('pas_expired')->nullable();
                $table->string('bpjs_kesehatan')->nullable();
                $table->string('bpjs_tk')->nullable();
                $table->string('tim_number')->nullable();
                $table->date('tim_registered')->nullable();
                $table->date('tim_expired')->nullable();
                $table->string('profile_picture')->nullable();
                $table->string('kota_domisili')->nullable();
                $table->string('no_hp')->nullable();
                $table->string('otp_code')->nullable();
                $table->timestamp('otp_expires_at')->nullable();
                $table->timestamps(); // created_at, updated_at (nullable)
            });
        }

        // Buat password_reset_tokens jika belum ada
        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // Buat sessions jika belum ada
        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    public function down(): void
    {
        // Disable foreign key checks to avoid "cannot delete or update a parent row" errors
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');

        Schema::enableForeignKeyConstraints();
    }
};
