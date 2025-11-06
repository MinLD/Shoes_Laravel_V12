<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Cart;
use App\Models\ProductVariant;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = ['cart_id', 'product_variant_id', 'quantity'];

    /**
     * Món hàng này thuộc về (1) giỏ hàng.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Món hàng này là (1) biến thể sản phẩm.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
