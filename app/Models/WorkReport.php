<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkReport extends Model
{
    use HasFactory;

    protected $table = 'work_reports';

    // timestamps Eloquent diaktifkan lagi karena tabel sudah punya created_at & updated_at
    public $timestamps = true;

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
