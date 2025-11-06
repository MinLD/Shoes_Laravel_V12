<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // Thêm Model Product
use App\Models\ProductVariant; // Thêm Model ProductVariant
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
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
            'image_url' => ['nullable', 'url'],
        ]);

        // Thêm product_id vào data
        $validatedData['product_id'] = $product->id;

        ProductVariant::create($validatedData);
        toastify()->success('Đã thêm biến thể thành công.');
    
        return redirect()->route('admin.products.edit', $product)
                         ->with('success', 'Đã thêm biến thể thành công.');
    }

    /**
     * Cập nhật một biến thể ĐÃ TỒN TẠI.
     */
    public function update(Request $request, ProductVariant $variant)
    {
        $validatedData = $request->validate([
            'color' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url'],
        ]);

        $variant->update($validatedData);

        // $variant->product là sản phẩm cha
        return redirect()->route('admin.products.edit', $variant->product)
                         ->with('success', 'Đã cập nhật biến thể thành công.');
    }

    /**
     * Xóa một biến thể.
     */
    public function destroy(ProductVariant $variant)
    {
        $product = $variant->product; // Lấy sản phẩm cha TRƯỚC khi xóa
        $variant->delete();

        return redirect()->route('admin.products.edit', $product)
                         ->with('success', 'Đã xóa biến thể thành công.');
    }
}