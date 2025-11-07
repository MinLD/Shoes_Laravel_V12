<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\ProductVariant;
use App\Models\CartItem;
class CartController extends Controller
{
   /**
     * Hiển thị trang Giỏ hàng.
     */
    public function index()
    {
        // Lấy giỏ hàng của user (hoặc tạo mới)
        $cart = Auth::user()->cart()->firstOrCreate();
        
        // Tải kèm (eager load) các quan hệ cần thiết để hiển thị
        $cart->load('items.productVariant.product.images');
        
        return view('cart.index', compact('cart'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $variantId = $request->input('product_variant_id');
        $quantity = $request->input('quantity');
        
        $cart = Auth::user()->cart()->firstOrCreate(); 

        $existingItem = $cart->items()
                             ->where('product_variant_id', $variantId)
                             ->first();

        $variant = ProductVariant::find($variantId);
        
        // Tính toán số lượng tồn kho
        $currentInCart = $existingItem ? $existingItem->quantity : 0;
        $requestedStock = $currentInCart + $quantity;

        if ($variant->stock_quantity < $requestedStock) {
            return redirect()->back()->withErrors(['error' => 'Số lượng tồn kho không đủ (chỉ còn ' . $variant->stock_quantity . ').']);
        }
        
        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }
        
        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    /**
     * Xóa một món hàng khỏi giỏ hàng.
     */
    public function remove(CartItem $cartItem)
    {
        // Kiểm tra bảo mật: User này có sở hữu món hàng này không?
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa món hàng này.');
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
