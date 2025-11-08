<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
class Product extends Model
{
    use HasFactory, Searchable;
    use HasFactory;
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'status'];

    protected static function booted(): void
    {
        // Tự động thêm 'where status = published' vào MỌI truy vấn
        static::addGlobalScope(new PublishedScope);
    }
    /**
     * Ghi đè phương thức tìm kiếm model của Route.
     * Tự động tắt Global Scope để tìm cả bản nháp (draft).
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Tắt GlobalScope PublishedScope KHI TÌM BẰNG ROUTE
        return $this->withoutGlobalScope(PublishedScope::class)
                    ->where($field ?? $this->getRouteKeyName(), $value)
                    ->firstOrFail();
    }

    /**
     * Sản phẩm này thuộc về một danh mục.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Sản phẩm này có nhiều ảnh (gallery).
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Sản phẩm này có nhiều biến thể (loại).
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function toSearchableArray(): array
    {
        // Tải các mối quan hệ
        $this->load('category', 'variants');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            
            // Tìm kiếm theo tên danh mục
            'category_name' => $this->category?->name, 
            
            // Tìm kiếm theo các màu sắc có sẵn
            'colors' => $this->variants->pluck('color')->unique()->filter()->all(),
            
            // Tìm kiếm theo các size có sẵn
            'sizes' => $this->variants->pluck('size')->unique()->filter()->all(),
        ];
    }
}
