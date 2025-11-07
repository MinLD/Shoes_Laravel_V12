<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
class OrderController extends Controller
{
  /**
     * Hiển thị trang chi tiết của một đơn hàng.
     */
    public function show(Order $order)
    {
        // Tải kèm thông tin 'user' (người đặt) và 'items' (các món hàng)
        $order->load('user', 'items');
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật trạng thái của một đơn hàng (Duyệt đơn).
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,processing,shipped,cancelled'],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        toastify()->success('Đã cập nhật trạng thái đơn hàng.');

        return redirect()->route('admin.orders.show', $order)
                         ->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}