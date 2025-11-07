<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'public_id',
    ];

    /**
     * Biến thể này thuộc về một sản phẩm.
     */
   public function product(): BelongsTo
    {
        // Chỉ định rõ: 'product_id' (ở bảng này) liên kết với 'id' (ở bảng products)
        return $this->belongsTo(Product::class, 'product_id', 'id');
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
