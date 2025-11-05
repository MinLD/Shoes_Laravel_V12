<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
class UserController extends Controller
{
   public function index(Request $request)
    {
        // 3. Lấy tham số 'view' từ URL, nếu không có thì mặc định là 'users'
        $viewType = $request->query('view', 'users');

        $data = [];
        $data['viewType'] = $viewType; // Gửi biến này sang view
        $data['roles'] = Role::all();

        // 4. Logic IF/ELSE để lấy đúng dữ liệu
        if ($viewType === 'users') {
            $data['users'] = User::with('roles', 'profile')->paginate(15);
        
        } elseif ($viewType === 'categories') {
            // (Bạn cần tự tạo Model/Controller cho cái này)
            // $data['categories'] = Category::paginate(15); // Ví dụ
            "data['categories'] = Category::paginate(15);";
        
        } elseif ($viewType === 'products') {
            // (Bạn cần tự tạo Model/Controller cho cái này)
            // $data['products'] = Product::paginate(15); // Ví dụ
            "data['products'] = Product::paginate(15);";
        
        }

        // 5. Trả về view với mảng $data
        return view('admin.dashboard', $data);
    }

    /**
     * Cập nhật thông tin user.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validate dữ liệu
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            
            // Validate trường của Profile
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            
            // Validate Role
            'role_id' => ['required', 'integer', 'exists:roles,id']
        ]);

        // 2. Cập nhật bảng 'users'
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ]);

        // 3. Cập nhật bảng 'profiles'
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id], // Điều kiện tìm
            [
                'phone_number' => $validatedData['phone_number'],
                'address' => $validatedData['address'],
            ]
        );
        
        // 4. Cập nhật Role (dùng sync để thay thế role cũ)
        $user->roles()->sync([$validatedData['role_id']]);

        toastify()->success('Cập nhật người dùng thành công.');
        return redirect()->route('admin.users.index', ['view' => 'users']);
    }
    public function destroy(User $user)
    {
        // Bảo vệ: Admin không thể tự xóa chính mình
        if($user->id===Auth::id()){
            return back()->withErrors(['error' => 'Bạn không thể tự xóa chính mình.']);
        }
        $user->delete();
        toastify()->success('Đã xóa người dùng thành công.');
        return redirect()->route('admin.dashboard', ['view' => 'users']);
    }
}
    