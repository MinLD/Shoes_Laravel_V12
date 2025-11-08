<x-main-layout>
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        {{-- Hiển thị lỗi nếu có --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Oops! Đã có lỗi xảy ra.</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="text-3xl font-bold text-gray-900 mb-6">
            Giỏ hàng của bạn
        </h1>

        @if ($cart && $cart->items->isNotEmpty())

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-4">

                    @foreach ($cart->items as $item)
                        @if ($item->productVariant)
                            <div class="flex items-center bg-white p-4 rounded-lg shadow-sm">
                                <a href="{{ route('product.show', $item->productVariant->product) }}">
                                    <img src="{{ $item->productVariant->image_url ?? ($item->productVariant->product->images->first()?->image_url ?? 'https://loremflickr.com/800/600/sneaker') }}"
                                        alt="{{ $item->productVariant->product->name }}"
                                        class="w-24 h-24 rounded-md object-cover">
                                </a>

                                <div class="ml-4 flex-1">
                                    <a href="{{ route('product.show', $item->productVariant->product) }}"
                                        class="font-semibold text-gray-800 hover:text-indigo-600">
                                        {{ $item->productVariant->product->name }}
                                    </a>
                                    <p class="text-sm text-gray-500">
                                        @if ($item->productVariant->color)
                                            {{ $item->productVariant->color }} /
                                        @endif
                                        @if ($item->productVariant->size)
                                            Size: {{ $item->productVariant->size }}
                                        @endif
                                    </p>

                                    <form action="{{ route('cart.remove', $item) }}" method="POST" class="mt-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-sm font-medium text-red-600 hover:text-red-500">Xóa</button>
                                    </form>
                                </div>

                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">
                                        {{ number_format($item->productVariant->price, 0, ',', '.') }} đ
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Số lượng: {{ $item->quantity }}
                                    </p>
                                    <p class="font-bold text-gray-900 mt-2">
                                        {{ number_format($item->productVariant->price * $item->quantity, 0, ',', '.') }}
                                        đ
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>

                <aside class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-sm sticky top-8">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Tóm tắt Đơn hàng</h3>

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-medium">{{ number_format($cart->total_amount, 0, ',', '.') }}
                                    đ</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí giao hàng:</span>
                                <span class="font-medium">Miễn phí</span>
                            </div>
                        </div>

                        <div class="border-t my-4"></div>

                        <div class="flex justify-between text-xl font-bold">
                            <span>Tổng cộng:</span>
                            <span>{{ number_format($cart->total_amount, 0, ',', '.') }} đ</span>
                        </div>

                        <a href="{{ route('checkout.index') }}"
                            class="mt-6 w-full inline-flex justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            Tiến hành Thanh toán
                        </a>
                    </div>
                </aside>

            </div>
        @else
            <div class="text-center bg-white p-12 rounded-lg shadow-sm">
                <h2 class="text-2xl font-semibold text-gray-700">Giỏ hàng của bạn đang trống</h2>
                <p class="mt-2 text-gray-500">Hãy tìm vài sản phẩm tuyệt vời và thêm vào đây nhé!</p>
                <a href="{{ route('home') }}"
                    class="mt-6 inline-block px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Tiếp tục mua sắm
                </a>
            </div>
        @endif
    </div>
</x-main-layout>
