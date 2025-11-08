<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Mail\OrderPlaced;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang Form Thanh toán.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Lấy giỏ hàng và tải kèm mọi thứ
        /** @var \App\Models\User $user */

        $cart = $user->cart()->firstOrCreate();
        $cart->load('items.productVariant.product');

        // Nếu giỏ hàng trống, quay về trang chủ
        if ($cart->items->isEmpty()) {
            return redirect()->route('home')->withErrors(['error' => 'Giỏ hàng của bạn đang trống.']);
        }
        
        // Tải profile để điền sẵn
        $user->load('profile');

        return view('checkout.index', compact('cart', 'user'));
    }

    /**
     * Xử lý việc đặt hàng.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        $cart = $user->cart()->with('items.productVariant')->first();

        // 1. Validate Form (địa chỉ, sđt)
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:500'],
        ]);

        // 2. Kiểm tra lần cuối (Giỏ hàng rỗng hoặc Tồn kho)
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('home')->withErrors(['error' => 'Giỏ hàng của bạn đang trống.']);
        }

        foreach ($cart->items as $item) {
            // Đảm bảo productVariant còn tồn tại
            if (!$item->productVariant) {
                return redirect()->route('cart.index')->withErrors([
                    'error' => 'Một sản phẩm trong giỏ hàng không còn tồn tại. Vui lòng xóa đi.'
                ]);
            }
            
            if ($item->productVariant->stock_quantity < $item->quantity) {
                return redirect()->route('cart.index')->withErrors([
                    'error' => 'Sản phẩm "' . $item->productVariant->product->name . '" không đủ tồn kho.'
                ]);
            }
        }

        // 3. Bắt đầu Transaction (Đảm bảo an toàn CSDL)
        $order = null; // Khởi tạo $order là null
        
        try {
            DB::beginTransaction();

            // 4. Tạo Đơn hàng (Order)
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $cart->total_amount,
                'status' => 'pending',
                'shipping_address' => $validatedData['shipping_address'],
                'phone_number' => $validatedData['phone_number'],
            ]);

            // 5. Chuyển CartItems thành OrderItems
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'product_name' => $item->productVariant->product->name . 
                                      ($item->productVariant->color ? ' - ' . $item->productVariant->color : '') .
                                      ($item->productVariant->size ? ' - Size ' . $item->productVariant->size : ''),
                    'price' => $item->productVariant->price,
                ]);

                // 6. Trừ Tồn Kho
                $item->productVariant->decrement('stock_quantity', $item->quantity);
            }

            // 7. Xóa Giỏ hàng
            $cart->items()->delete();

            // 8. Gửi Mail
            $order->load('items', 'user');
            Mail::to($user->email)->send(new OrderPlaced($order));

            // 9. Hoàn tất
            DB::commit();
            
            // 10. Chuyển hướng đến trang Thành Công
            return redirect()->route('checkout.success')->with('order_id', $order->id);

        } catch (\Exception $e) {
            // Nếu bất kỳ bước nào ở trên thất bại, nó sẽ nhảy vào đây
            DB::rollBack();
            
            // Ghi log lỗi (để bạn debug)
            logger('Lỗi khi đặt hàng: ' . $e->getMessage());
            
            // Trả về trang giỏ hàng với thông báo lỗi
            return redirect()->route('cart.index')->withErrors(['error' => 'Đã có lỗi xảy ra khi đặt hàng. Vui lòng thử lại. Lỗi: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Hiển thị trang đặt hàng thành công.
     */
    public function success()
    {
        $orderId = session('order_id');

        if (!$orderId) {
            return redirect()->route('home');
        }

        return view('checkout.success', compact('orderId'));
    }
}