<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $table = 'stock_logs';

    public $timestamps = false; // table only has created_at

    protected $fillable = [
        'product_id',
        'type',
        'qty',
        'note',
        'user',
        'created_at',
    ];

    protected $casts = [
        'qty' => 'integer',
        'created_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
