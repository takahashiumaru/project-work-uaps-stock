<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {
            // Tambah kolom jika belum ada
            if (!Schema::hasColumn('work_reports', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('work_reports', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {
            if (Schema::hasColumn('work_reports', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('work_reports', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
};

class WorkReport extends Model
{
    use HasFactory;

    protected $table = 'work_reports';

    // matikan timestamps agar Eloquent tidak mengisi created_at / updated_at
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'work_date',
        'file_path',
        'status',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];
}
