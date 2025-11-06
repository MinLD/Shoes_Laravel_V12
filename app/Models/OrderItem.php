<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Order;
use App\Models\ProductVariant;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'quantity',
        'product_name', // Snapshot
        'price',        // Snapshot
    ];

    /**
     * Món hàng này thuộc về (1) đơn hàng.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Món hàng này (tham chiếu) tới (1) biến thể sản phẩm.
     */
    public function productVariant(): BelongsTo
    {
        // Quan hệ này có thể trả về null nếu sản phẩm gốc bị xóa
        return $this->belongsTo(ProductVariant::class);
    }
}
