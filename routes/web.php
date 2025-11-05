<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

// 1. Route công khai (Landing Page)
// Bất kỳ ai cũng có thể xem
Route::get('/', function () {
    return view('welcome');
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
    Route::get('/admin', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/admin/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/admin/{user}', [UserController::class, 'update'])->name('admin.users.update');

    // C. Route Profile
    // (Logic tương tự /dashboard)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 3. Các route Xác thực (Login, Register...)
// Để ở cuối và không có middleware 'role.gate'
require __DIR__.'/auth.php';