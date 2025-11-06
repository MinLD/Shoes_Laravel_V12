<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;


class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'image_url'];
    /**
     * Một danh mục có nhiều sản phẩm.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
