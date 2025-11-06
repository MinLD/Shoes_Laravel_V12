<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'shipping_address',
        'phone_number',
    ];

    /**
     * Đơn hàng này thuộc về (1) User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Đơn hàng này có (N) món hàng (OrderItem).
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
