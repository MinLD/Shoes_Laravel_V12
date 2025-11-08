<x-main-layout>
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">

        @if ($cart->items->isEmpty())
            <div class="text-center bg-white p-12 rounded-lg shadow-sm">
                <h2 class="text-2xl font-semibold text-gray-700">Giỏ hàng của bạn đang trống</h2>
                <a href="{{ route('home') }}"
                    class="mt-6 inline-block px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Tiếp tục mua sắm
                </a>
            </div>
        @else
            <h1 class="text-3xl font-bold text-gray-900 mb-6">
                Thông tin Thanh toán
            </h1>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-lg font-semibold mb-4">Thông tin Giao hàng (COD)</h2>
                        <div class="space-y-4">

                            <div>
                                <x-input-label for="name" :value="__('Họ và Tên')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name', $user->name)" required />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email (Để nhận xác nhận)')" />
                                <x-text-input id="email" name="email" type="email"
                                    class="mt-1 block w-full bg-gray-100" :value="$user->email" readonly disabled />
                            </div>

                            <div>
                                <x-input-label for="phone_number" :value="__('Số điện thoại')" />
                                <x-text-input id="phone_number" name="phone_number" type="tel"
                                    class="mt-1 block w-full" :value="old('phone_number', $user->profile?->phone_number)" required />
                            </div>

                            <div>
                                <x-input-label for="shipping_address" :value="__('Địa chỉ nhận hàng')" />
                                <textarea id="shipping_address" name="shipping_address" rows="3"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required>{{ old('shipping_address', $user->profile?->address) }}</textarea>
                            </div>

                        </div>
                    </div>

                    <aside class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-lg shadow-sm sticky top-8">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Tóm tắt Đơn hàng</h3>

                            <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                                @foreach ($cart->items as $item)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->productVariant->image_url ?? ($item->productVariant->product->images->first()?->image_url ?? 'https://loremflickr.com/800/600/sneaker') }}"
                                            alt="{{ $item->productVariant->product->name }}"
                                            class="w-16 h-16 rounded-md object-cover">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $item->productVariant->product->name }}</p>
                                            <p class="text-xs text-gray-500">SL: {{ $item->quantity }}</p>
                                        </div>
                                        <p class="text-sm font-medium">
                                            {{ number_format($item->productVariant->price * $item->quantity, 0, ',', '.') }}
                                            đ</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t pt-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tạm tính:</span>
                                    <span class="font-medium">{{ number_format($cart->total_amount, 0, ',', '.') }}
                                        đ</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phí giao hàng:</span>
                                    <span class="font-medium">Miễn phí</span>
                                </div>
                                <div class="flex justify-between text-xl font-bold text-gray-900 pt-2">
                                    <span>Tổng cộng:</span>
                                    <span>{{ number_format($cart->total_amount, 0, ',', '.') }} đ</span>
                                </div>
                            </div>

                            <button type="submit"
                                class="mt-6 w-full inline-flex justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Đặt Hàng (COD)
                            </button>
                        </div>
                    </aside>
                </div>
            </form>
        @endif
    </div>
</x-main-layout>
