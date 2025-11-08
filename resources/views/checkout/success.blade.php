<x-main-layout>
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center bg-white p-12 rounded-lg shadow-xl max-w-2xl mx-auto">

            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mt-6">
                Đặt hàng thành công!
            </h1>
            <p class="mt-3 text-gray-600">
                Cảm ơn bạn đã tin tưởng SHOESHOP. Mã đơn hàng của bạn là:
            </p>

            <div class="my-4 text-2xl font-mono font-semibold text-indigo-600 bg-indigo-50 p-3 rounded-md inline-block">
                #{{ $orderId }}
            </div>

            <p class="text-gray-600">
                Chúng tôi đã gửi một email xác nhận (đến {{ Auth::user()->email }}) với chi tiết đơn hàng của bạn.
            </p>

            <div class="mt-8">
                <a href="{{ route('home') }}"
                    class="inline-block px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</x-main-layout>
