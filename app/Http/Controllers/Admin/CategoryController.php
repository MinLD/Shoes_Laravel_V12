<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str để tạo slug
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Lưu một danh mục mới.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'], // Yêu cầu là một URL (Cloudinary)
        ]);

        Category::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']), // Tự động tạo slug
            'description' => $validatedData['description'],
            'image_url' => $validatedData['image_url'],
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
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
        ]);

        $category->update([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']), // Cập nhật slug
            'description' => $validatedData['description'],
            'image_url' => $validatedData['image_url'],
        ]);

        toastify()->success('Đã cập nhật danh mục thành công.');

        return redirect()->route('admin.dashboard', ['view' => 'categories'])
                         ->with('success', 'Đã cập nhật danh mục thành công.');
    }
    /**
     * Xóa một danh mục.
     */
    public function destroy(Category $category)
    {
        // (Lưu ý: Nếu xóa danh mục, các sản phẩm con cũng sẽ bị xóa
        //  do chúng ta đã cài 'onDelete(cascade)' trong migration)
        $category->delete();
        toastify()->success('Đã xóa danh mục thành công.');

        return redirect()->route('admin.dashboard', ['view' => 'categories'])
                         ->with('success', 'Đã xóa danh mục thành công.');
    }
    
}
