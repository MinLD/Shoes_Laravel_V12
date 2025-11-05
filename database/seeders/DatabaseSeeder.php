<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; 
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    // 1. Chạy RoleSeeder trước
        $this->call(RoleSeeder::class);

        // 2. Lấy Role 'admin'
        // Chúng ta giả định RoleSeeder đã chạy và tạo ra role 'admin'
        $adminRole = Role::where('name', 'admin')->first();

        // 3. Tạo User Admin
        // Sử dụng firstOrCreate để tránh tạo trùng lặp admin@example.com
        $adminUser = User::firstOrCreate(
            [
                'email' => 'admin@example.com' // Email để kiểm tra
            ],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345678'), // Đặt password mặc định là 'password'
                'email_verified_at' => now(), // Xác thực email luôn
            ]
        );
        
        // 4. Gán quyền Admin cho User này
        // Sử dụng syncWithoutDetaching để gắn role mà không xóa các role cũ (nếu có)
        // và tránh lỗi nếu đã tồn tại
        $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);

        // Ghi chú:
        // Bạn không cần tạo Profile ở đây, vì hàm boot() trong User.php
        // đã tự động làm việc đó khi $adminUser được tạo.
        
        // Bạn có thể tạo thêm các user mẫu khác ở đây nếu muốn
        // \App\Models\User::factory(10)->create();
    }
}