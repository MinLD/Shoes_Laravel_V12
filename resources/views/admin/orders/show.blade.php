<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('admin.dashboard', ['view' => 'orders']) }}" class="text-indigo-600 hover:text-indigo-900">
                Quản lý Đơn Hàng
            </a>
            / Chi tiết Đơn hàng #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-1 space-y-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Cập nhật Trạng thái</h3>

                        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div>
                                <x-input-label for="status" :value="__('Trạng thái đơn hàng')" />
                                <select name="status" id="status"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Đang chờ
                                        xử lý (Pending)</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                        Đang xử lý (Processing)</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đã giao
                                        hàng (Shipped)</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã
                                        hủy (Cancelled)</option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <x-primary-button>{{ __('Cập nhật') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Thông tin Khách hàng</h3>
                        <div class="space-y-2 text-sm">
                            <p><strong>Tên:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>

                            <h4 class="font-medium pt-2">Thông tin Giao hàng (Snapshot):</h4>
                            <p><strong>SĐT:</strong> {{ $order->phone_number }}</p>
                            <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="md:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Các Sản phẩm đã đặt</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sản phẩm (Snapshot)</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Số lượng</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Đơn giá (Snapshot)</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tổng</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                                {{ $item->product_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ number_format($item->price, 0, ',', '.') }} đ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right font-medium text-gray-700">TỔNG
                                            CỘNG:</td>
                                        <td class="px-6 py-3 text-right font-medium text-gray-900">
                                            {{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
