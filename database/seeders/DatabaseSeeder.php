<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; 
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductImage;

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



        // 3. TẠO 100 USERS KHÁCH HÀNG (Mật khẩu '11111111' từ Factory)
        $users = User::factory(100)->create();
        foreach ($users as $user) {
            $user->roles()->attach(Role::where('name', 'user')->first()->id);
            // Gán thông tin profile giả
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone_number' => fake()->phoneNumber(),
                    'address' => fake()->address(),
                ]
            );
        }

        // 4. TẠO 20 DANH MỤC
        $categories = Category::factory(20)->create();

        // 5. TẠO 100 SẢN PHẨM
        // Mỗi sản phẩm có 3-5 biến thể, 4-6 ảnh, và thuộc 1 trong 20 danh mục
        Product::factory(100)
            ->recycle($categories) // Gán ngẫu nhiên category đã tạo
            ->has(
                ProductVariant::factory(fake()->numberBetween(3, 5)), // Tạo 3-5 biến thể con
                'variants'
            )
            ->has(
                ProductImage::factory(fake()->numberBetween(4, 6)), // Tạo 4-6 ảnh gallery
                'images'
            )
            ->create();
        
        // 6. TẠO 200 ĐƠN HÀNG
      $variants = ProductVariant::has('product')->with('product')->get();

        Order::factory(200)
            ->recycle($users) // Gán ngẫu nhiên user đã tạo
            ->create()
            ->each(function (Order $order) use ($variants) {
                // Với mỗi đơn hàng, tạo 1-3 món hàng
                $items = $variants->random(fake()->numberBetween(1, 3));
                $totalAmount = 0;

                foreach ($items as $variant) {
                    $quantity = fake()->numberBetween(1, 2);
                    $price = $variant->price; 
                    $totalAmount += $price * $quantity;

                    // Tạo OrderItem với dữ liệu snapshot chính xác
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_variant_id' => $variant->id,
                        'quantity' => $quantity,
                        'price' => $price, // Snapshot giá
                        'product_name' => $variant->product->name . " - " . $variant->color . " - " . $variant->size, // Snapshot tên
                    ]);
                }
                
                // Cập nhật tổng tiền cho đơn hàng
                $order->update(['total_amount' => $totalAmount]);
            });
    }
}