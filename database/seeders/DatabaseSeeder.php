<?php

namespace Database\Seeders;

// Cần import tất cả các Model
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===============================================
        // BƯỚC 1: TẠO ROLES VÀ ADMIN
        // ===============================================
        $this->call(RoleSeeder::class);
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // Tài khoản Admin: admin@example.com / password
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('11111111'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->roles()->sync([$adminRole->id]);

        // ===============================================
        // BƯỚC 2: TẠO ẢNH PLACEHOLDER DUY NHẤT
        // ===============================================
        $placeholder = 'https://placehold.co/600x400/E2E8F0/333333?text=SHOESHOP';

        // ===============================================
        // BƯỚC 3: TẠO 10 DANH MỤC (THƯƠNG HIỆU)
        // ===============================================
        $cat_nike = Category::firstOrCreate(['slug' => 'nike'], ['name' => 'Nike', 'description' => 'Just Do It.', 'image_url' => $placeholder, 'public_id' => null]);
        $cat_adidas = Category::firstOrCreate(['slug' => 'adidas'], ['name' => 'Adidas', 'description' => 'Thương hiệu 3 sọc.', 'image_url' => $placeholder, 'public_id' => null]);
        $cat_puma = Category::firstOrCreate(['slug' => 'puma'], ['name' => 'Puma', 'description' => 'Thương hiệu con báo.', 'image_url' => $placeholder, 'public_id' => null]);
        $cat_converse = Category::firstOrCreate(['slug' => 'converse'], ['name' => 'Converse', 'description' => 'Chuck Taylor.', 'image_url' => $placeholder, 'public_id' => null]);
        $cat_vans = Category::firstOrCreate(['slug' => 'vans'], ['name' => 'Vans', 'description' => 'Off The Wall.', 'image_url' => $placeholder, 'public_id' => null]);
        $cat_newbalance = Category::firstOrCreate(['slug' => 'new-balance'], ['name' => 'New Balance', 'description' => 'Giày chạy bộ và retro.', 'image_url' => $placeholder, 'public_id' => null]);
        $cat_asics = Category::firstOrCreate(['slug' => 'asics'], ['name' => 'ASICS', 'description' => 'Công nghệ đệm GEL.', 'image_url' => $placeholder, 'public_id' => null]);
        $cat_drmartens = Category::firstOrCreate(['slug' => 'dr-martens'], ['name' => 'Dr. Martens', 'description' => 'Đế giày AirWair.', 'image_url' => $placeholder, 'public_id' => null]);
        $cat_salomon = Category::firstOrCreate(['slug' => 'salomon'], ['name' => 'Salomon', 'description' => 'Giày chạy địa hình.', 'image_url' => $placeholder, 'public_id' => null]);
        $cat_hoka = Category::firstOrCreate(['slug' => 'hoka'], ['name' => 'Hoka', 'description' => 'Đế giày dày (maximalist).', 'image_url' => $placeholder, 'public_id' => null]);

        // ===============================================
        // BƯỚC 4: TẠO DỮ LIỆU SẢN PHẨM VÀ BIẾN THỂ
        // ===============================================

        // ----- DANH MỤC NIKE (10 SẢN PHẨM, 3 HẾT HÀNG) -----

        // SẢN PHẨM 1 (Nike Air Force 1) - CÒN HÀNG
        $p_af1 = Product::firstOrCreate(
            ['slug' => 'nike-air-force-1-07'],
            [
                'category_id' => $cat_nike->id,
                'name' => "Nike Air Force 1 '07",
                "description" => "Huyền thoại tiếp tục sống mãi trong Nike Air Force 1 '07. Đôi giày bóng rổ này mang đến một diện mạo mới cho những gì bạn biết rõ nhất.
                **Chất liệu:** Lớp phủ da thật và da tổng hợp ở mặt trên tăng thêm phong cách di sản, độ bền và khả năng hỗ trợ.
                **Công năng:** Ban đầu được thiết kế cho môn bóng rổ hiệu suất cao, phần đệm Nike Air mang lại sự thoải mái nhẹ nhàng. Kiểu dáng cổ thấp tạo thêm vẻ ngoài gọn gàng, hợp lý.
                **Tính năng:** Các lỗ đục ở mũi giày giúp tăng cường khả năng thông gió. Đế giữa bằng bọt xốp mang lại cảm giác nhẹ nhàng, êm ái, trong khi đế ngoài bằng cao su không đánh dấu tăng thêm lực kéo.
                **Xuất xứ:** Việt Nam/Indonesia.
                **Độ bền:** Cực kỳ bền bỉ, là một trong những đôi giày 'workhorse' đáng tin cậy nhất.",
                'status' => 'published',
            ]
        );
        $v_af1_1 = $p_af1->variants()->firstOrCreate(['color' => 'Trắng', 'size' => '40'], ['price' => 2900000, 'stock_quantity' => 50, 'image_url' => $placeholder]);
        $p_af1->variants()->firstOrCreate(['color' => 'Trắng', 'size' => '41'], ['price' => 2900000, 'stock_quantity' => 50, 'image_url' => $placeholder]);
        $p_af1->variants()->firstOrCreate(['color' => 'Trắng', 'size' => '42'], ['price' => 2900000, 'stock_quantity' => 30, 'image_url' => $placeholder]);
        $p_af1->variants()->firstOrCreate(['color' => 'Đen', 'size' => '41'], ['price' => 2900000, 'stock_quantity' => 20, 'image_url' => $placeholder]);
        $p_af1->images()->create(['image_url' => $placeholder, 'public_id' => null]);
        $p_af1->images()->create(['image_url' => $placeholder, 'public_id' => null]);

        // SẢN PHẨM 2 (Nike Pegasus 40) - CÒN HÀNG
        $p_pegasus = Product::firstOrCreate(['slug' => 'nike-pegasus-40'], [
            'category_id' => $cat_nike->id, 'name' => 'Nike Pegasus 40',
            "description" => "Độ nảy hoàn hảo cho mọi cú chạy, hỗ trợ đàn hồi. Đây là phiên bản thứ 40 của dòng giày Pegasus huyền thoại.
            **Công năng:** Được thiết kế cho các bài chạy hàng ngày (daily trainer) ở mọi cự ly.
            **Tính năng:** Kết hợp công nghệ đệm React (êm ái) và hai bộ Zoom Air (ở mũi và gót chân) tạo độ nảy.
            **Chất liệu:** Lưới kỹ thuật (engineered mesh) ở thân giày được cải tiến để thoáng khí và vừa vặn hơn.
            **Độ bền:** Đế ngoài bằng cao su Waffle-inspired cung cấp lực kéo tuyệt vời và độ bền cao trên mặt đường nhựa.",
            'status' => 'published',
        ]);
        $p_pegasus->variants()->firstOrCreate(['color' => 'Đen/Trắng', 'size' => '42'], ['price' => 3200000, 'stock_quantity' => 25, 'image_url' => $placeholder]);
        $p_pegasus->variants()->firstOrCreate(['color' => 'Đen/Trắng', 'size' => '43'], ['price' => 3200000, 'stock_quantity' => 15, 'image_url' => $placeholder]);
        $p_pegasus->variants()->firstOrCreate(['color' => 'Xanh Neon', 'size' => '42'], ['price' => 3250000, 'stock_quantity' => 10, 'image_url' => $placeholder]);
        
        // SẢN PHẨM 3 (Nike Dunk Low) - CÒN HÀNG
        Product::firstOrCreate(['slug' => 'nike-dunk-low-retro'], [
            'category_id' => $cat_nike->id, 'name' => 'Nike Dunk Low Retro',
            'description' => "Từ sân bóng rổ đến đường phố, Nike Dunk Low trở lại với lớp phủ da sắc nét và phối màu cổ điển.
            **Chất liệu:** Thân giày bằng da cao cấp mang lại vẻ ngoài cổ điển và cảm giác mềm mại.
            **Tính năng:** Đế giữa bằng bọt xốp cung cấp đệm nhẹ, nhạy bén. Cổ giày có đệm, kiểu dáng thấp tạo cảm giác thoải mái.
            **Xuất xứ:** Indonesia.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Trắng/Đen (Panda)', 'size' => '40'], ['price' => 2800000, 'stock_quantity' => 40, 'image_url' => $placeholder]);

        // SẢN PHẨM 4 (Nike Vomero 17) - CÒN HÀNG
        Product::firstOrCreate(['slug' => 'nike-vomero-17'], [
            'category_id' => $cat_nike->id, 'name' => 'Nike Vomero 17',
            "description" => "Đôi giày chạy bộ cao cấp dành cho người tìm kiếm sự êm ái tối đa.
            **Công năng:** Hoàn hảo cho các bài chạy phục hồi (recovery runs) hoặc chạy cự ly dài (long runs).
            **Tính năng:** Lần đầu tiên kết hợp 2 loại bọt cao cấp: ZoomX (siêu nảy) ở trên và Cushlon (siêu bền) ở dưới.
            **Chất liệu:** Lưới kỹ thuật được thiết kế phức tạp để thoáng khí và hỗ trợ ở những vùng trọng yếu.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Xám/Đỏ', 'size' => '42'], ['price' => 4500000, 'stock_quantity' => 15, 'image_url' => $placeholder]);

        // SẢN PHẨM 5 (Nike Air Jordan 1) - CÒN HÀNG
        Product::firstOrCreate(['slug' => 'air-jordan-1-mid'], [
            'category_id' => $cat_nike->id, 'name' => 'Air Jordan 1 Mid',
            'description' => "Lấy cảm hứng từ mẫu AJ1 gốc, phiên bản Mid này duy trì vẻ ngoài mang tính biểu tượng mà bạn yêu thích trong khi cung cấp lựa chọn màu sắc và chất liệu da sắc nét.
            **Chất liệu:** Da thật và da tổng hợp kết hợp.
            **Tính năng:** Bộ đệm Air-Sole ở gót chân cung cấp đệm nhẹ.
            **Công năng:** Phù hợp cho đi lại hàng ngày (casual wear) với phong cách bóng rổ di sản.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Bred Toe (Đỏ/Đen)', 'size' => '41'], ['price' => 3800000, 'stock_quantity' => 22, 'image_url' => $placeholder]);
        
        // SẢN PHẨM 6 (Nike Metcon 9) - CÒN HÀNG
        Product::firstOrCreate(['slug' => 'nike-metcon-9'], [
            'category_id' => $cat_nike->id, 'name' => 'Nike Metcon 9',
            'description' => "Đôi giày tập luyện (training) hàng đầu.
            **Công năng:** Thiết kế cho các bài tập cường độ cao (HIIT), nâng tạ (weightlifting), và CrossFit.
            **Tính năng:** Đế giày có tấm Hyperlift ở gót chân lớn hơn để tăng cường sự ổn định khi nâng tạ. Phần bọc cao su ở bên hông giúp bám dây thừng khi leo (rope climb).
            **Độ bền:** Rất bền bỉ ở những khu vực chịu mài mòn cao.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Xanh lính', 'size' => '42'], ['price' => 3600000, 'stock_quantity' => 18, 'image_url' => $placeholder]);

        // SẢN PHẨM 7 (Nike - Bản nháp) - KHÔNG TÍNH
        Product::firstOrCreate(['slug' => 'nike-air-max-ban-nhap'], [
            'category_id' => $cat_nike->id, 'name' => 'Nike Air Max (Bản nháp)',
            'description' => "Sản phẩm đang chờ cập nhật thông tin kho hàng và giá cả.",
            'status' => 'draft', // <-- Trạng thái Nháp
        ]);

        // SẢN PHẨM 8 (Nike Invincible 3) - HẾT HÀNG (1)
        Product::firstOrCreate(['slug' => 'nike-invincible-3-het-hang'], [
            'category_id' => $cat_nike->id, 'name' => 'Nike Invincible 3 (Hết hàng)',
            'description' => "Đệm ZoomX tối đa, mang lại cảm giác êm ái nhất cho các bài chạy dài. Đôi giày này đã bán hết do nhu cầu cao.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Trắng/Xanh', 'size' => '41'], ['price' => 4800000, 'stock_quantity' => 0, 'image_url' => $placeholder]); // <-- Hết hàng

        // SẢN PHẨM 9 (Nike Vaporfly 3) - HẾT HÀNG (2)
        Product::firstOrCreate(['slug' => 'nike-vaporfly-3-het-hang'], [
            'category_id' => $cat_nike->id, 'name' => 'Nike Vaporfly 3 (Hết hàng)',
            'description' => "Giày đua (racing shoe) nhanh nhất của Nike với tấm carbon. Đã bán hết trước ngày thi đấu.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Trắng/Đỏ (Prototype)', 'size' => '42'], ['price' => 6500000, 'stock_quantity' => 0, 'image_url' => $placeholder]); // <-- Hết hàng

        // SẢN PHẨM 10 (Nike Blazer Mid 77) - HẾT HÀNG (3)
        Product::firstOrCreate(['slug' => 'nike-blazer-mid-77-het-hang'], [
            'category_id' => $cat_nike->id, 'name' => "Nike Blazer Mid '77 (Hết hàng)",
            'description' => "Phong cách vintage cổ điển, đế giày lưu hóa. Đã bán hết.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Trắng/Đen', 'size' => '40'], ['price' => 2500000, 'stock_quantity' => 0, 'image_url' => $placeholder]); // <-- Hết hàng

        // ----- DANH MỤC ADIDAS (3 SẢN PHẨM) -----
        $p_superstar = Product::firstOrCreate(
            ['slug' => 'adidas-superstar'],
            [
                'category_id' => $cat_adidas->id,
                'name' => 'Adidas Superstar',
                "description" => "Ra mắt vào năm 1970, giày adidas Superstar là đôi giày thể thao bóng rổ cổ thấp bằng da đầu tiên.
                **Tính năng:** Phiên bản này vẫn giữ nguyên vẻ ngoài mang tính biểu tượng với phần mũi giày vỏ sò (shell toe) đặc trưng, 3 Sọc răng cưa và logo gót chân. 
                **Chất liệu:** Thân giày bằng da nguyên tấm mang lại cảm giác cao cấp và độ bền. Lót giày bằng vải dệt tạo sự thoải mái. Đế ngoài bằng cao su với họa tiết xương cá cổ điển cung cấp độ bám.
                **Công năng:** Là một biểu tượng thời trang, phù hợp cho mọi hoạt động hàng ngày.",
                'status' => 'published',
            ]
        );
        $v_stansmith_1 = $p_superstar->variants()->firstOrCreate(['color' => 'Trắng/Sọc Đen', 'size' => '39'], ['price' => 2600000, 'stock_quantity' => 60, 'image_url' => $placeholder]);
        $p_superstar->variants()->firstOrCreate(['color' => 'Trắng/Sọc Đen', 'size' => '40'], ['price' => 2600000, 'stock_quantity' => 60, 'image_url' => $placeholder]);
        $p_superstar->variants()->firstOrCreate(['color' => 'Trắng/Sọc Đen', 'size' => '41'], ['price' => 2600000, 'stock_quantity' => 40, 'image_url' => $placeholder]);
        $p_superstar->images()->create(['image_url' => $placeholder, 'public_id' => null]);
        
        $p_ultraboost = Product::firstOrCreate(
            ['slug' => 'adidas-ultraboost-light'],
            [
                'category_id' => $cat_adidas->id,
                'name' => 'Adidas Ultraboost Light',
                "description" => "Trải nghiệm năng lượng bùng nổ với Ultraboost Light mới, phiên bản Ultraboost nhẹ nhất từ trước đến nay. 
                **Tính năng:** Phép màu nằm ở đế giữa Light BOOST, một thế hệ adidas BOOST mới. Thiết kế phân tử độc đáo của nó làm cho nó trở thành bọt BOOST nhẹ nhất cho đến nay.
                **Chất liệu:** Thân giày PRIMEKNIT+ co giãn, ôm vừa vặn theo chuyển động của chân.
                **Công năng:** Phù hợp cho chạy bộ hàng ngày, mang lại sự êm ái và hoàn trả năng lượng vượt trội.",
                'status' => 'published',
            ]
        );
        $v_ultraboost_1 = $p_ultraboost->variants()->firstOrCreate(['color' => 'Trắng/Đỏ', 'size' => '42'], ['price' => 5200000, 'stock_quantity' => 12, 'image_url' => $placeholder]);
        $p_ultraboost->variants()->firstOrCreate(['color' => 'Trắng/Đỏ', 'size' => '43'], ['price' => 5200000, 'stock_quantity' => 10, 'image_url' => $placeholder]);
        
        Product::firstOrCreate(
            ['slug' => 'adidas-samba-og'],
            [
                'category_id' => $cat_adidas->id,
                'name' => 'Adidas Samba OG',
                "description" => "Một biểu tượng của phong cách đường phố. Giày Samba OG giữ nguyên di sản với thân giày bằng da lộn mềm mại và 3 sọc tương phản. 
                **Chất liệu:** Da lộn và da cao cấp.
                **Tính năng:** Đế ngoài bằng cao su (gumsole) cổ điển, mang lại vẻ ngoài retro và độ bám tốt.
                **Công năng:** Ban đầu là giày bóng đá trong nhà, nay là biểu tượng thời trang hàng ngày.",
                'status' => 'published',
            ]
        )->variants()->firstOrCreate(['color' => 'Trắng/Đen', 'size' => '41'], ['price' => 2800000, 'stock_quantity' => 30, 'image_url' => $placeholder]);

        // ----- DANH MỤC PUMA (3 SẢN PHẨM) -----
        $p_puma_suede = Product::firstOrCreate(['slug' => 'puma-suede-classic-xxi'], [
            'category_id' => $cat_puma->id, 'name' => 'Puma Suede Classic XXI',
            "description" => "Suede Classic XXI mang đến vẻ ngoài cổ điển, nổi tiếng của PUMA.
            **Chất liệu:** Phần thân giày bằng da lộn toàn bộ (full suede upper).
            **Tính năng:** Một số điểm nhấn hiện đại giúp nâng tầm chất lượng và cảm giác thoải mái của một đôi giày vốn đã rất tuyệt vời. Lót giày êm ái.
            **Công năng:** Phù hợp cho phong cách thời trang đường phố (streetwear).",
            'status' => 'published',
        ]);
        $p_puma_suede->variants()->firstOrCreate(['color' => 'Đỏ/Trắng', 'size' => '41'], ['price' => 2000000, 'stock_quantity' => 25, 'image_url' => $placeholder]);
        $p_puma_suede->variants()->firstOrCreate(['color' => 'Đen/Trắng', 'size' => '42'], ['price' => 2000000, 'stock_quantity' => 15, 'image_url' => $placeholder]);

        Product::firstOrCreate(['slug' => 'puma-mb-03-toxic'], [
            'category_id' => $cat_puma->id, 'name' => 'Puma MB.03 Toxic',
            'description' => 'Mẫu giày signature của LaMelo Ball, được thiết kế cho sân bóng rổ.',
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Xanh/Hồng', 'size' => '44'], ['price' => 4500000, 'stock_quantity' => 10, 'image_url' => $placeholder]);

        Product::firstOrCreate(['slug' => 'puma-velocity-nitro-2'], [
            'category_id' => $cat_puma->id, 'name' => 'Puma Velocity Nitro 2',
            'description' => 'Giày chạy bộ đa năng với đệm Nitro Foam êm ái.',
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Cam', 'size' => '42'], ['price' => 2800000, 'stock_quantity' => 15, 'image_url' => $placeholder]);
        
        // ----- CÁC DANH MỤC KHÁC (1 SẢN PHẨM MỖI LOẠI) -----
        
        Product::firstOrCreate(['slug' => 'converse-chuck-70'], [
            'category_id' => $cat_converse->id, 'name' => 'Converse Chuck 70',
            "description" => "Phiên bản nâng cấp của Chuck Taylor All Star cổ điển, với chất liệu canvas dày dặn hơn (12oz), đế giày ngả vàng cổ điển (vintage) và đường may chắc chắn hơn.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Đen Cao Cổ', 'size' => '41'], ['price' => 1800000, 'stock_quantity' => 40, 'image_url' => $placeholder]);
        
        Product::firstOrCreate(['slug' => 'vans-old-skool'], [
            'category_id' => $cat_vans->id, 'name' => 'Vans Old Skool',
            "description" => "Đôi giày trượt ván cổ điển của Vans và là đôi giày đầu tiên mang trên mình sọc jazz (jazz stripe) mang tính biểu tượng.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Đen/Trắng', 'size' => '42'], ['price' => 1600000, 'stock_quantity' => 50, 'image_url' => $placeholder]);

        Product::firstOrCreate(['slug' => 'new-balance-574'], [
            'category_id' => $cat_newbalance->id, 'name' => 'New Balance 574',
            "description" => "Dòng giày 574 là một biểu tượng của sự khéo léo và độc đáo. Công nghệ đệm ENCAP ở đế giữa mang lại sự hỗ trợ và độ bền tối đa.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Xám', 'size' => '41'], ['price' => 2200000, 'stock_quantity' => 30, 'image_url' => $placeholder]);

        Product::firstOrCreate(['slug' => 'asics-gel-kayano-30'], [
            'category_id' => $cat_asics->id, 'name' => 'ASICS GEL-Kayano 30',
            "description" => "Mang lại sự ổn định và êm ái tối đa. Công nghệ 4D GUIDANCE SYSTEM™ mới giúp cung cấp sự ổn định thích ứng.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Xanh Dương', 'size' => '44'], ['price' => 4500000, 'stock_quantity' => 10, 'image_url' => $placeholder]);
        
        Product::firstOrCreate(['slug' => 'dr-martens-1460'], [
            'category_id' => $cat_drmartens->id, 'name' => 'Dr. Martens 1460',
            "description" => "Đôi bốt 8 lỗ huyền thoại. Không thể nhầm lẫn. Với da Smooth cổ điển và đế AirWair siêu bền.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Đen Nhám', 'size' => '42'], ['price' => 4800000, 'stock_quantity' => 15, 'image_url' => $placeholder]);
        
        $p_salomon = Product::firstOrCreate(['slug' => 'salomon-xt-6'], [
            'category_id' => $cat_salomon->id, 'name' => 'Salomon XT-6',
            "description" => "Là lựa chọn hàng đầu của các vận động viên chạy địa hình (trail running) cự ly siêu dài.",
            'status' => 'published',
        ]);
        $v_salomon_1 = $p_salomon->variants()->firstOrCreate(['color' => 'Đen/Bạc', 'size' => '41'], ['price' => 5500000, 'stock_quantity' => 8, 'image_url' => $placeholder]);

        Product::firstOrCreate(['slug' => 'hoka-clifton-9'], [
            'category_id' => $cat_hoka->id, 'name' => 'Hoka Clifton 9',
            "description" => "Phiên bản thứ chín của dòng Clifton đã ra mắt, nhẹ hơn và êm ái hơn bao giờ hết.",
            'status' => 'published',
        ])->variants()->firstOrCreate(['color' => 'Trắng Kem', 'size' => '43'], ['price' => 3800000, 'stock_quantity' => 18, 'image_url' => $placeholder]);
        
        
        // ===============================================
        // BƯỚC 5: TẠO 1 USER KHÁCH HÀNG VÀ 1 ĐƠN HÀNG MẪU
        // ===============================================

        $customerUser = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Khách Hàng Mẫu',
                'password' => Hash::make('11111111'),
                'email_verified_at' => now(),
            ]
        );
        $customerUser->roles()->sync([$userRole->id]);

        $customerUser->profile()->updateOrCreate(
            ['user_id' => $customerUser->id],
            [
                'phone_number' => '0987654321',
                'address' => '123 Đường ABC, Phường 4, Quận 5, TP. HCM',
            ]
        );

        // Tạo Đơn hàng (Order)
        $order = Order::firstOrCreate(
            ['user_id' => $customerUser->id, 'status' => 'pending'],
            [
                'total_amount' => 8100000, // (Sẽ tính lại)
                'shipping_address' => $customerUser->profile->address,
                'phone_number' => $customerUser->profile->phone_number,
            ]
        );

        // Mua 1 cái AF1 (Trắng, 40)
        $item1 = $order->items()->firstOrCreate(
            ['product_variant_id' => $v_af1_1->id],
            [
                'quantity' => 1,
                'product_name' => "{$p_af1->name} - {$v_af1_1->color} - Size {$v_af1_1->size}",
                'price' => $v_af1_1->price,
            ]
        );

        // Mua 2 cái Superstar (Trắng/Sọc Đen, 39)
        $item2 = $order->items()->firstOrCreate(
            ['product_variant_id' => $v_stansmith_1->id],
            [
                'quantity' => 2,
                'product_name' => "{$p_superstar->name} - {$v_stansmith_1->color} - Size {$v_stansmith_1->size}",
                'price' => $v_stansmith_1->price,
            ]
        );
        
        // Cập nhật lại tổng tiền
        $totalAmount = ($item1->price * $item1->quantity) + ($item2->price * $item2->quantity);
        $order->update(['total_amount' => $totalAmount]);
    }
}