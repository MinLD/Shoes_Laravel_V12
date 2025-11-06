<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class ProductController extends Controller
{
    /**
     * Hiển thị form tạo sản phẩm mới.
     * (Đây là trang admin.products.create)
     */
    public function create()
    {
        // Lấy tất cả danh mục để đưa vào dropdown
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới vào CSDL.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        // 2. Tạo sản phẩm
        $product = Product::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']), // Tự động tạo slug
            'category_id' => $validatedData['category_id'],
            'description' => $validatedData['description'],
        ]);
        toastify()->success('Đã tạo sản phẩm thành công!');


        // 3. Chuyển hướng đến trang CHỈNH SỬA để thêm biến thể/ảnh
        return redirect()->route('admin.products.edit', $product)
                         ->with('success', 'Đã tạo sản phẩm! Bây giờ hãy thêm các biến thể và hình ảnh.');
    }
    /**
     * Hiển thị form chỉnh sửa sản phẩm.
     * (Đây là trang admin.products.edit)
     */
    public function edit(Product $product)
    {
        // $product đã tự động được tìm thấy (nhờ Bước 1)
        $categories = Category::all();
        
        // Tải kèm các biến thể và ảnh (chuẩn bị cho bước sau)
        $product->load('variants', 'images');
        toastify()->success('Đã tìm thấy sản phẩm.');
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm trong CSDL.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validate dữ liệu
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        // 2. Cập nhật sản phẩm
        $product->update([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'category_id' => $validatedData['category_id'],
            'description' => $validatedData['description'],
            
            // XỬ LÝ NÚT "CÔNG KHAI":
            // Nếu checkbox 'status' được tick (gửi 'published'), thì là 'published'.
            // Nếu không tick (không gửi gì), thì là 'draft'.
            'status' => $request->has('status') ? 'published' : 'draft',
        ]);
        toastify()->success('Đã cập nhật sản phẩm thành công!');

        // 3. Quay lại trang edit
        return redirect()->route('admin.products.edit', $product)
                         ->with('success', 'Đã cập nhật sản phẩm thành công!');
    }
    /**
     * Xóa một sản phẩm.
     * (Variants và Images sẽ bị xóa theo nhờ 'onDelete(cascade)')
     */
    public function destroy(Product $product)
    {
        $product->delete();
        toastify()->success('Đã xóa sản phẩm thành công.');

        return redirect()->route('admin.dashboard', ['view' => 'products'])
                         ->with('success', 'Đã xóa sản phẩm thành công.');
    }
}
