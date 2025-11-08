<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Đơn hàng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <h2 class="text-lg font-medium text-gray-900">
                    Lịch sử Đơn hàng
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Xem lại các đơn hàng bạn đã đặt và trạng thái của chúng.
                </p>

                @if (session('success'))
                    <p class="mt-4 text-sm text-green-600">{{ session('success') }}</p>
                @endif
                @if ($errors->any())
                    <p class="mt-4 text-sm text-red-600">{{ $errors->first() }}</p>
                @endif

                <div class="mt-6 space-y-6">
                    @forelse ($orders as $order)
                        <div class="p-4 border rounded-lg">

                            <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                                <div>
                                    <h3 class="font-semibold text-gray-900">Đơn hàng #{{ $order->id }}</h3>
                                    <p class="text-sm text-gray-500">Ngày đặt:
                                        {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 mt-2 sm:mt-0 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                    @if ($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                                    @if ($order->status == 'shipped') bg-green-100 text-green-800 @endif
                                    @if ($order->status == 'cancelled') bg-red-100 text-red-800 @endif
                                ">
                                    {{ ucfirst($order->status) }} </span>
                            </div>

                            <div class="border-t my-4"></div>
                            <div class="space-y-3">
                                @foreach ($order->items as $item)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->productVariant?->image_url ?? ($item->productVariant?->product?->images?->first()?->image_url ?? 'https://loremflickr.com/800/600/sneaker') }}"
                                            alt="{{ $item->product_name }}"
                                            class="w-12 h-12 rounded-md object-cover flex-shrink-0">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">{{ $item->product_name }}</p>
                                            <p class="text-xs text-gray-500">SL: {{ $item->quantity }} | Giá:
                                                {{ number_format($item->price, 0, ',', '.') }} đ</p>
                                        </div>
                                        <p class="text-sm font-semibold">
                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t mt-4 pt-4 flex justify-between items-center">
                                <span class="font-bold text-gray-900">Tổng cộng:
                                    {{ number_format($order->total_amount, 0, ',', '.') }} đ</span>

                                @if ($order->status == 'pending')
                                    <form action="{{ route('my-orders.cancel', $order) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-sm font-medium text-red-600 hover:text-red-500">Hủy đơn</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Bạn chưa có đơn hàng nào.</p>
                    @endforelse

                    <div class="mt-4">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

