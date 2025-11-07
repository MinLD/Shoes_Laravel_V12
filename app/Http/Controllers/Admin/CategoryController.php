<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str để tạo slug
use Illuminate\Validation\Rule;
use Cloudinary\Cloudinary;
use App\Models\ProductVariant;
class CategoryController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(); // <-- Thêm
    }
    /**
     * Lưu một danh mục mới.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // Đổi 'image_url' thành 'image'
        ]);

        $imageUrl = null;
        $publicId = null;

        // Xử lý upload ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'categories', // Thư mục trên Cloudinary
            ]);
            $imageUrl = $result['secure_url'];
            $publicId = $result['public_id'];
        }

        Category::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'description' => $validatedData['description'],
            'image_url' => $imageUrl, // Lưu URL
            'public_id' => $publicId,  // Lưu Public ID
        ]);

        toastify()->success('Đã tạo danh mục thành công.');

        return redirect()->route('admin.dashboard', ['view' => 'categories'])
                         ->with('success', 'Đã tạo danh mục thành công.');
    }

    /**
     * Cập nhật một danh mục.
     */
    public function update(Request $request, Category $category)
    {
        $updateData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);
        
        // Cập nhật slug
        $updateData['slug'] = Str::slug($updateData['name']);

        // Xử lý nếu có ảnh mới
        if ($request->hasFile('image')) {
            // 1. Xóa ảnh cũ trên Cloudinary (nếu có)
            if ($category->public_id) {
                $this->cloudinary->uploadApi()->destroy($category->public_id);
            }
            
            // 2. Upload ảnh mới
            $file = $request->file('image');
            $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'categories',
            ]);
            
            // 3. Lấy URL và ID mới
            $updateData['image_url'] = $result['secure_url'];
            $updateData['public_id'] = $result['public_id'];
        }

        // Cập nhật CSDL
        $category->update($updateData);

        toastify()->success('Đã cập nhật danh mục thành công.');

        return redirect()->route('admin.dashboard', ['view' => 'categories'])
                         ->with('success', 'Đã cập nhật danh mục thành công.');
    }
    /**
     * Xóa một danh mục.
     */
    public function destroy(Category $category)
    {
        // 1. Xóa ảnh trên Cloudinary (nếu có)
        if ($category->public_id) {
            $this->cloudinary->uploadApi()->destroy($category->public_id);
        }

        // 2. Xóa danh mục
        $category->delete();
        toastify()->success('Đã xóa danh mục thành công.');


        return redirect()->route('admin.dashboard', ['view' => 'categories'])
                         ->with('success', 'Đã xóa danh mục thành công.');
    }
    
}
