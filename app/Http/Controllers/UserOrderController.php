<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class UserOrderController extends Controller
{
    public function cancel(Order $order)
    {
        // 1. Kiểm tra Bảo mật: Đơn hàng này có phải của tôi không?
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        // 2. Kiểm tra Nghiệp vụ: Đơn hàng có đang "chờ xử lý" không?
        if ($order->status !== 'pending') {
            return redirect()->back()->withErrors(['error' => 'Không thể hủy đơn hàng đã được xử lý hoặc đã hủy.']);
        }

        // 3. Bắt đầu Transaction (để đảm bảo an toàn)
        try {
            DB::beginTransaction();

            // 4. Cập nhật trạng thái đơn hàng
            $order->update(['status' => 'cancelled']);

            // 5. HOÀN LẠI TỒN KHO (Rất quan trọng)
            $order->load('items.productVariant'); // Tải kèm các biến thể
            foreach ($order->items as $item) {
                // Kiểm tra xem biến thể có còn tồn tại không
                if ($item->productVariant) {
                    // Tăng (increment) số lượng tồn kho trở lại
                    $item->productVariant->increment('stock_quantity', $item->quantity);
                }
            }
            
            DB::commit(); // Hoàn tất

            return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công.');

        } catch (\Exception $e) {
            DB::rollBack(); // Hoàn tác nếu có lỗi
            logger('Lỗi hủy đơn hàng: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Đã có lỗi xảy ra, vui lòng thử lại.']);
        }
    }
}
