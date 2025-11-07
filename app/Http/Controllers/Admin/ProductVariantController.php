<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // Thêm Model Product
use App\Models\ProductVariant; // Thêm Model ProductVariant
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;
class ProductVariantController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(); // <-- Thêm
    }
    /**
     * Lưu một biến thể MỚI cho một sản phẩm.
     */
    public function store(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'color' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // Đổi 'image_url' thành 'image'
        ]);

        $imageUrl = null;
        $publicId = null;

        // Xử lý upload ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'variants', // Thư mục trên Cloudinary
            ]);
            $imageUrl = $result['secure_url'];
            $publicId = $result['public_id'];
        }

        // Thêm product_id và thông tin ảnh vào data
        $validatedData['product_id'] = $product->id;
        $validatedData['image_url'] = $imageUrl;
        $validatedData['public_id'] = $publicId;

        ProductVariant::create($validatedData);

        return redirect()->route('admin.products.edit', $product)
                         ->with('success', 'Đã thêm biến thể thành công.');
    }

    /**
     * Cập nhật một biến thể.
     */
    public function update(Request $request, ProductVariant $variant)
    {
        $updateData = $request->validate([
            'color' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        // Xử lý nếu có ảnh mới
        if ($request->hasFile('image')) {
            // 1. Xóa ảnh cũ
            if ($variant->public_id) {
                $this->cloudinary->uploadApi()->destroy($variant->public_id);
            }
            
            // 2. Upload ảnh mới
            $file = $request->file('image');
            $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'variants',
            ]);
            
            // 3. Lấy URL và ID mới
            $updateData['image_url'] = $result['secure_url'];
            $updateData['public_id'] = $result['public_id'];
        }

        // Cập nhật CSDL
        $variant->update($updateData);

        return redirect()->route('admin.products.edit', $variant->product)
                         ->with('success', 'Đã cập nhật biến thể thành công.');
    }

    /**
     * Xóa một biến thể.
     */
    public function destroy(ProductVariant $variant)
    {
        $product = $variant->product; // Lấy sản phẩm cha

        // 1. Xóa ảnh trên Cloudinary
        if ($variant->public_id) {
            $this->cloudinary->uploadApi()->destroy($variant->public_id);
        }

        // 2. Xóa biến thể
        $variant->delete();

        return redirect()->route('admin.products.edit', $product)
                         ->with('success', 'Đã xóa biến thể thành công.');
    }
}