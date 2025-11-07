<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductVariant;

class Category_Client_Controller extends Controller
{
   /**
     * Hiển thị trang chi tiết danh mục VÀ xử lý lọc/sắp xếp.
     */
    public function show(Category $category, Request $request)
    {
        // === BƯỚC 1: LẤY DỮ LIỆU ĐỂ LỌC (FACETED DATA) ===
        
        // Lấy tất cả ID sản phẩm thuộc danh mục này
        $productIds = $category->products()->pluck('id');

        // Lấy tất cả Size và Màu SẮC DUY NHẤT từ các biến thể
        // thuộc các sản phẩm trên
        $all_sizes = ProductVariant::whereIn('product_id', $productIds)
                                    ->whereNotNull('size')
                                    ->distinct()
                                    ->orderBy('size')
                                    ->pluck('size');
        
        $all_colors = ProductVariant::whereIn('product_id', $productIds)
                                     ->whereNotNull('color')
                                     ->distinct()
                                     ->orderBy('color')
                                     ->pluck('color');

        
        // === BƯỚC 2: XÂY DỰNG CÂU TRUY VẤN (QUERY) ===

        // Lấy các sản phẩm thuộc danh mục này (Đã tự động lọc 'published')
        $query = $category->products()
                            ->with('images')
                            ->withMin('variants', 'price'); // Vẫn lấy giá rẻ nhất

        // Lọc theo Giá (Price)
        if ($request->filled('min_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }
        if ($request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Lọc theo Size (mảng)
        if ($request->filled('sizes')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereIn('size', $request->sizes);
            });
        }
        
        // Lọc theo Màu (mảng)
        if ($request->filled('colors')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereIn('color', $request->colors);
            });
        }

        // Xử lý Sắp xếp (Sort)
        $sort = $request->input('sort', 'latest'); // Mặc định là 'latest'
        if ($sort == 'price_asc') {
            $query->orderBy('variants_min_price', 'asc');
        } elseif ($sort == 'price_desc') {
            $query->orderBy('variants_min_price', 'desc');
        } else {
            $query->latest(); // Mới nhất (default)
        }
        
        // === BƯỚC 3: LẤY KẾT QUẢ VÀ PHÂN TRANG ===
        
        // Phân trang và tự động gắn tất cả tham số (filter/sort) vào link
        $products = $query->paginate(12)->appends($request->query());

        // Trả về view với tất cả dữ liệu
        return view('categories.show', [
            'category' => $category,
            'products' => $products,
            'all_sizes' => $all_sizes,   // Gửi size ra view
            'all_colors' => $all_colors, // Gửi màu ra view
        ]);
    }
}
