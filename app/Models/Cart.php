<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\CartItem;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];
    protected $appends = ['total_amount'];

    public function getTotalAmountAttribute()
    {
        // Đảm bảo 'items.productVariant' đã được tải
        if ( ! $this->relationLoaded('items')) {
            $this->load('items.productVariant');
        }

        return $this->items->sum(function ($item) {
            // Kiểm tra xem biến thể còn tồn tại không
            if ($item->productVariant) {
                return $item->quantity * $item->productVariant->price;
            }
            return 0; // Nếu biến thể đã bị xóa
        });
    }

    /**
     * Giỏ hàng này thuộc về (1) User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Giỏ hàng này có (N) món hàng (CartItem).
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Quan hệ N-N tiện lợi:
     * Lấy tất cả các BIẾN THỂ SẢN PHẨM trong giỏ hàng.
     */
    public function productVariants(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariant::class, 'cart_items')
                    ->withPivot('quantity'); // Lấy kèm số lượng
    }
}
