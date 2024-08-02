<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 'client_name', 'phone_number', 'product_code', 'final_price', 'quantity',
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'orders_products');
    }
}
