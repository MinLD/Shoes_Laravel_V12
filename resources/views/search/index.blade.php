<x-main-layout>
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mt-2">
                Kết quả tìm kiếm cho: "{{ $query }}"
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Tìm thấy {{ $products->total() }} sản phẩm.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <main class="lg:col-span-4">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

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
                        <div class="lg:col-span-4 text-center text-gray-500 py-16">
                            <p class="text-lg">Không tìm thấy sản phẩm nào khớp với từ khóa của bạn.</p>
                            <p class="mt-2 text-sm">Vui lòng thử tìm kiếm với từ khóa khác.</p>
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
