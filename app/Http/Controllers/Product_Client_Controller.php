<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
class Product_Client_Controller extends Controller
{
  /**
     * Hiển thị trang chi tiết sản phẩm.
     */
    public function show(Product $product)
    {
        // $product đã được tìm thấy (kể cả 'draft' nhờ resolveRouteBinding)
        // Nhưng chúng ta phải kiểm tra thủ công
       

        // Tải tất cả các quan hệ cần thiết
        $product->load('images', 'variants', 'category');

        // Lấy các tùy chọn duy nhất để hiển thị (lọc bỏ null/trống)
        $sizes = $product->variants->pluck('size')->unique()->filter()->values();
        $colors = $product->variants->pluck('color')->unique()->filter()->values();

        // Lấy tất cả các biến thể (dưới dạng JSON) để đưa vào Alpine.js
        $variantsJson = $product->variants->keyBy(function ($variant) {
            // Tạo một key duy nhất, ví dụ: "Đen-42"
            return strtolower($variant->color . '-' . $variant->size);
        })->toJson();

        return view('products.show', [
            'product' => $product,
            'sizes' => $sizes,
            'colors' => $colors,
            'variantsJson' => $variantsJson,
        ]);
    }
}
