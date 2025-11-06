<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Category; // <-- Thêm Model Category
use App\Models\Product;
use App\Models\Scopes\PublishedScope;
class DashboardController extends Controller
{
    /**
     * Hiển thị trang admin dashboard dựa trên tham số 'view'.
     */
    public function index(Request $request)
    {
        // Lấy 'view' từ URL, mặc định là 'users'
        $viewType = $request->query('view', 'users');
        $data = [];
        $data['viewType'] = $viewType;

        // Lấy dữ liệu dựa trên 'viewType'
        if ($viewType === 'users') {
            $data['users'] = User::with('roles', 'profile')->paginate(15);
            $data['roles'] = Role::all(); // Cần cho modal edit user

        } elseif ($viewType === 'categories') {
            $data['categories'] = Category::paginate(15); // Lấy danh mục

        } elseif ($viewType === 'products') {
           // Tải kèm 'category' và 'variants' để hiển thị
            $data['products'] = Product::withoutGlobalScope(PublishedScope::class)
                                       ->with('category', 'variants')
                                       ->latest() // Sắp xếp mới nhất lên đầu
                                       ->paginate(15);
        }

        // Trả về view với mảng $data
        return view('admin.dashboard', $data);
    }
}