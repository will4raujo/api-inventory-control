<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category_id',
        'supplier_id',
        'cost_price',
        'sale_price',
        'stock',
        'min_stock',
        'max_stock',
        'expiration_date'
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
