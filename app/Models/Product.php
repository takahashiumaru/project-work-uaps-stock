<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;

    protected $fillable = [
        'sku',
        'name',
        'category',
        'stock',
        'min_stock',
        'unit',
        'location',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class, 'product_id');
    }
}
