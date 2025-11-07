<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ với danh mục và sản phẩm.
     */
    public function index()
    {
        // 1. Lấy tất cả danh mục
        $categories = Category::all();

        // 2. Lấy 10 sản phẩm mới nhất, đã CÔNG KHAI
        // (GlobalScope tự động lọc 'published')
        $products = Product::latest() // Sắp xếp mới nhất
                            ->with('images') // Tải kèm ảnh
                            ->withMin('variants', 'price') // Lấy giá rẻ nhất
                            ->take(10) // Chỉ lấy 10 sản phẩm
                            ->get();

        // 3. Lấy danh sách ảnh banner
        $bannerPath = public_path('banner');
        $bannerFiles = File::exists($bannerPath) ? File::files($bannerPath) : [];
        $banners = array_map(function ($file) {
            return 'banner/' . $file->getFilename();
        }, $bannerFiles);


        // 4. Trả về view với 3 biến
        return view('welcome', [
            'categories' => $categories,
            'products' => $products,
            'banners' => $banners,
        ]);
    }
}
