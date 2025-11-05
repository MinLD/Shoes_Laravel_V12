<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sử dụng firstOrCreate để tránh tạo trùng lặp
        // Nếu role 'admin' chưa có, nó sẽ được tạo
        Role::firstOrCreate(
            ['name' => 'admin'], // Điều kiện kiểm tra
            ['display_name' => 'Quản Trị Viên'] // Dữ liệu sẽ tạo nếu chưa tồn tại
        );

        // Tương tự cho 'user'
        Role::firstOrCreate(
            ['name' => 'user'],
            ['display_name' => 'Khách Hàng']
        );
        
    
    }
}
