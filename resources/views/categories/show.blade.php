<x-main-layout>
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">

        <div class="mb-6">
            <a href="{{ route('home') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                &larr; Quay lại Trang chủ
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">
                {{ $category->name }}
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <aside class="lg:col-span-1">
                <form method="GET" action="{{ route('category.show', $category) }}">
                    <div class="bg-white p-4 rounded-lg shadow-sm sticky top-8">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Bộ Lọc</h3>

                        <div class="space-y-6">

                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700">Sắp xếp
                                    theo</label>
                                <select id="sort" name="sort"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất
                                    </option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                        Giá: Thấp đến Cao</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                        Giá: Cao đến Thấp</option>
                                </select>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Giá</h4>
                                <div class="flex space-y-2 flex-col">
                                    <x-text-input name="min_price" type="number" placeholder="Từ (đ)"
                                        :value="request('min_price')" />
                                    <x-text-input name="max_price" type="number" placeholder="Đến (đ)"
                                        :value="request('max_price')" />
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Size</h4>
                                <div class="mt-2 space-y-1 max-h-40 overflow-y-auto">
                                    @foreach ($all_sizes as $size)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="sizes[]" value="{{ $size }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ in_array($size, request('sizes', [])) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600">{{ $size }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Màu Sắc</h4>
                                <div class="mt-2 space-y-1 max-h-40 overflow-y-auto">
                                    @foreach ($all_colors as $color)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="colors[]" value="{{ $color }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ in_array($color, request('colors', [])) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600">{{ $color }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-4 border-t">
                                <x-primary-button class="w-full justify-center">{{ __('Lọc') }}</x-primary-button>
                            </div>

                        </div>
                    </div>
                </form>
            </aside>

            <main class="lg:col-span-3">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($products as $product)
                        <div
                            class="border rounded-lg bg-white shadow-sm overflow-hidden group transition-all duration-300 ease-in-out hover:shadow-xl hover:scale-105">
                            <div class="w-full h-48 bg-gray-200">
                                <a href="{{ route('product.show', $product) }}">
                                    <img src="{{ $product->images->first()?->image_url ?? 'https://loremflickr.com/800/600/sneaker' }}"
                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </a>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg text-gray-800 group-hover:text-indigo-600 truncate">
                                    <a href="{{ route('product.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-gray-700 mt-1">
                                    {{ number_format($product->variants_min_price ?? 0, 0, ',', '.') }} đ
                                </p>
                                <a href="{{ route('product.show', $product) }}"
                                    class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 lg:col-span-3 text-center text-gray-500 py-16">
                            <p class="text-lg">Không tìm thấy sản phẩm nào.</p>
                            <p class="mt-2 text-sm">Vui lòng thử điều chỉnh bộ lọc của bạn.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            </main>

        </div>
    </div>
</x-main-layout>
