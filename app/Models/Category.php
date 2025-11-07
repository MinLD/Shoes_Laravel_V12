<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'image_url','public_id',];
    /**
     * Một danh mục có nhiều sản phẩm.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
