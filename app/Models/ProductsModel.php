<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'img',
        'name',
        'price',
        'size',
        'color',
        'quantity',
        'description',
        'status',
        'sold',
    ];

    public function carts()
    {
        return $this->hasMany(CartModel::class);
    }
}
