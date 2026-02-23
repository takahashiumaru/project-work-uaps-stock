<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'requests';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'qty_requested',
        'status',
        'request_date',
        'note',
        'response_note',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'status' => 'enum(Pending, Approved, Completed, Rejected)',
        'qty_requested' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

