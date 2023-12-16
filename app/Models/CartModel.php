<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'size',
        'color',
        'quantity',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productById($productId)
    {
        return $this->products()->find($productId);
    }

    public function products()
    {
        return $this->belongsTo(ProductsModel::class, 'product_id', 'id');
    }
}
