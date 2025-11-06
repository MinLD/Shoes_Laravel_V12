<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CartItem;
use App\Models\OrderItem;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'color',
        'size',
        'price',
        'stock_quantity',
        'image_url',
    ];

    /**
     * Biến thể này thuộc về một sản phẩm.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Biến thể này có thể nằm trong (N) mục của giỏ hàng.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Biến thể này có thể nằm trong (N) mục của đơn hàng.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
