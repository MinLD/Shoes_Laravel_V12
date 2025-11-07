<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary();
    }

    /**
     * Upload và lưu ảnh mới cho sản phẩm.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        foreach ($request->file('images') as $file) {
            // Tự động upload file lên Cloudinary
            $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'shoes',
            ]);

            // Lấy URL và Public ID
            $imageUrl = $result['secure_url'];
            $publicId = $result['public_id'];

            // Lưu vào CSDL của bạn
            $product->images()->create([
                'image_url' => $imageUrl,
                'public_id' => $publicId,
            ]);
        }

        toastify()->success('Đã upload ảnh thành công.');

        return redirect()->route('admin.products.edit', $product)
                         ->with('success', 'Đã upload ảnh thành công!');
    }

    /**
     * Xóa một ảnh khỏi Cloudinary và CSDL.
     */
    public function destroy(Product $product, ProductImage $image)
    {
        // 1. Xóa file trên Cloudinary
        if ($image->public_id) {
            $this->cloudinary->uploadApi()->destroy($image->public_id);
        }

        // 2. Xóa record trong CSDL
        $image->delete();

        toastify()->success('Đã xóa ảnh thành công.');

        return redirect()->route('admin.products.edit', $product)
                         ->with('success', 'Đã xóa ảnh thành công!');
    }
}