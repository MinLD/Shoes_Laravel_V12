<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PublishedScope implements Scope
{
    /**
     * Tự động áp dụng điều kiện 'status' = 'published'
     */
    public function apply(Builder $builder, Model $model)
    {
        // Chỉ lấy các sản phẩm đã 'published'
        $builder->where('status', 'published');
    }
}