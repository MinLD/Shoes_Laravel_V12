<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next)
    {
        // Phải chắc chắn là user đã đăng nhập
        if (!Auth::check()) {
            return redirect('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. LOGIC CHO ADMIN
        // Nếu user là admin...
        if ($user->hasRole('admin') ) {
            // ...nhưng đang cố truy cập vào các route KHÔNG PHẢI 'admin' hoặc 'logout'
            // thì đẩy về /admin.
            if (!$request->is('admin') && !$request->is('admin/*') && !$request->is('logout')) {
                return redirect('/admin');
            }
        }
        
        // 2. LOGIC CHO USER (Hoặc role bất kỳ không phải Admin)
        // Nếu user không phải admin...
        if (!$user->hasRole('admin')) {
             // ...nhưng đang cố vào route 'admin'
             // thì đẩy họ về trang chủ '/'.
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect('/');
            }
        }

        // 3. Nếu mọi thứ ổn, cho phép request đi tiếp
        return $next($request);
    }
}