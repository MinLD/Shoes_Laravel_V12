<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Category; // <-- Thêm Model Category
use App\Models\Product;
use App\Models\Scopes\PublishedScope;
use App\Models\Order;

class DashboardController extends Controller

{
    /**
     * Hiển thị trang admin dashboard dựa trên tham số 'view'.
     */
    public function index(Request $request)
    {
        // Lấy 'view' từ URL, mặc định là 'users'
        $viewType = $request->query('view', 'users');
        $search = $request->query('search');

        $data = [];
        $data['viewType'] = $viewType;
        $data['search'] = $search;

        // Lấy dữ liệu dựa trên 'viewType'
        if ($viewType === 'users') {
            $query = User::with('roles', 'profile');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search}%")
                      ->orWhereHas('profile', function ($profileQuery) use ($search) {
                          $profileQuery->where('phone_number', 'LIKE', "%{$search}%");
                      });
                });
            }

        $data['users'] = $query->paginate(15);
        $data['roles'] = Role::all();

        } elseif ($viewType === 'categories') {
           $query = Category::query();
            
            // Logic tìm kiếm Category (theo Tên)
            if ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            }
            
            $data['categories'] = $query->paginate(15);

        } elseif ($viewType === 'products') {
        // --- BẮT ĐẦU NÂNG CẤP ---
           $selectedCategoryId = $request->query('category_id');
            $data['all_categories'] = Category::all();
            $data['selectedCategoryId'] = $selectedCategoryId;

            $query = Product::withoutGlobalScope(PublishedScope::class)
                                ->with('category', 'variants');

            // Lọc theo Category
            if ($selectedCategoryId) {
                $query->where('category_id', $selectedCategoryId);
            }
            
            // Logic tìm kiếm Product (theo Tên)
            if ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            }

            $data['products'] = $query->latest()->paginate(15);
        }
        elseif ($viewType === 'orders') {
            $query = Order::with('user');

            // Logic tìm kiếm Order (theo Mã ĐH - ID)
            if ($search) {
                // Tìm kiếm ID chính xác
                $query->where('id', $search);
            }
            
            $data['orders'] = $query->latest()->paginate(20);
        }

        // Trả về view với mảng $data
        return view('admin.dashboard', $data);
    }
}