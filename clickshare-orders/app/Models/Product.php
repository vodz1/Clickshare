<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name', 'description', 'country', 'product_code',
    ];
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'orders_products');
    }
}
