<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Category_Client_Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Product_Client_Controller;

// 1. Route công khai (Landing Page)
// Bất kỳ ai cũng có thể xem
Route::get('/', [HomeController::class, 'index'])->name('home');
// ===== THÊM ROUTE MỚI CHO TRANG CHI TIẾT (Placeholder) =====
Route::get('/products/{product:slug}', [Product_Client_Controller::class, 'show'])->name('product.show');

Route::get('/categories/{category:slug}', [Category_Client_Controller::class, 'show'])->name('category.show');

// ===== THÊM ROUTE MỚI CHO GIỎ HÀNG =====
// 'auth' middleware bắt buộc user phải đăng nhập mới được thêm vào giỏ
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
});


// 2. TẤT CẢ các route private (cần đăng nhập)
// Chúng ta áp dụng 'role.gate' (dấu chấm) cho CẢ NHÓM
Route::middleware(['auth', 'verified', 'role.gate'])->group(function () {

    // A. Route cho User (Breeze dashboard)
    // Khi Admin vào đây, 'role.gate' sẽ chặn và đẩy về /admin
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard'); // Breeze cần tên 'dashboard' này

    // B. Route cho Admin
    // Khi User vào đây, 'role.gate' sẽ chặn và đẩy về /dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');


    // ===== ROUTE XỬ LÝ USER (CRUD) =====
    Route::delete('/admin/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/admin/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

    // C. Route Profile
    // (Logic tương tự /dashboard)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ===== ROUTE XỬ LÝ CATEGORY (CRUD) =====
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::patch('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // ===== ROUTE XỬ LÝ PRODUCT (CRUD) =====
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::patch('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    // ===== ROUTE XỬ LÝ PRODUCT VARIANTS (CRUD) =====
    // Thêm (store) 1 biến thể mới cho 1 sản phẩm
    Route::post('/admin/products/{product}/variants', [ProductVariantController::class, 'store'])->name('admin.variants.store');
    // Cập nhật (update) 1 biến thể cụ thể
    Route::patch('/admin/products/{product}/variants/{variant}', [ProductVariantController::class, 'update'])->name('admin.variants.update');
    // Xóa (destroy) 1 biến thể cụ thể
    Route::delete('/admin/products/{product}/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('admin.variants.destroy');

    // ===== ROUTE XỬ LÝ PRODUCT IMAGES (Upload/Delete) =====
    Route::post('/admin/products/{product}/images', [ProductImageController::class, 'store'])->name('admin.images.store');
    Route::delete('/admin/products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('admin.images.destroy');

    // ===== ROUTE XỬ LÝ ORDERS (Show/Update) =====
    Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
});


// 3. Các route Xác thực (Login, Register...)
// Để ở cuối và không có middleware 'role.gate'
require __DIR__.'/auth.php';