<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- Thêm
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Hiển thị trang kết quả tìm kiếm (Sử dụng Meilisearch).
     */
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->route('home');
        }

        // Dùng Laravel Scout / Meilisearch để tìm kiếm
        $products = Product::search($query) 
                            ->query(function ($builder) {
                                // Tải kèm ảnh và giá rẻ nhất
                                $builder->with('images')->withMin('variants', 'price');
                            })
                            ->paginate(12)
                            ->appends(['q' => $query]); // Giữ lại từ khóa 'q'

        // Trả về view
        return view('search.index', [
            'products' => $products,
            'query' => $query,
        ]);
    }
    public function suggestions(Request $request)
    {
        $query = $request->input('q');

        // Nếu query quá ngắn, trả về rỗng
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Meilisearch cực nhanh cho việc này
        $products = Product::search($query)
                            ->query(function ($builder) {
                                $builder->with('images'); // Lấy ảnh
                            })
                            ->take(5) // Chỉ lấy 5 kết quả
                            ->get();

        // Định dạng lại dữ liệu cho gọn gàng
        $formattedProducts = $products->map(function ($product) {
            return [
                'name' => $product->name,
                // Tạo URL
                'url' => route('product.show', $product),
                // Lấy ảnh
                'image' => $product->images->first()?->image_url ?? 'https://loremflickr.com/800/600/sneaker'
            ];
        });

        return response()->json($formattedProducts);
    }
}