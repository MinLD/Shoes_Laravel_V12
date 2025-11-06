<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Hash; // <-- Thêm
use Illuminate\Validation\Rules\Password; // <-- Thêm
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
class UserController extends Controller

{   
    /**
     * Tạo một người dùng mới.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu (bao gồm cả mật khẩu)
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],

        ]);
        

        // 2. Tạo User
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            
        ]);
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id], // Search condition
            [
                'phone_number' => $validatedData['phone_number'],
                'address' => $validatedData['address'],
                'avatar' => 'default_avatar.png' // Set on create
            ]
        );
        
        
        // 3. Gán Role
        $user->roles()->sync([$validatedData['role_id']]);

        
        // (Lưu ý: Profile sẽ được tự động tạo bởi hàm boot() trong Model User)
        toastify()->success('Đã tạo người dùng thành công.');

        return redirect()->route('admin.dashboard', ['view' => 'users'])
                         ->with('success', 'Đã tạo người dùng thành công.');
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
        return redirect()->route('admin.dashboard', ['view' => 'users']);
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
    