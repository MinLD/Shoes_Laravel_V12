<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
    'product_id', 
    'image_url', 
    'public_id'
];

    /**
     * Ảnh này thuộc về một sản phẩm.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
