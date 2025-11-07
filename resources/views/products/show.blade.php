<x-main-layout>
    <div x-data="productVariantSelector(
        {{ $variantsJson }}
    )" class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <div>
                <div id="gallery-main" class="swiper w-full rounded-lg shadow-md overflow-hidden">
                    <div class="swiper-wrapper">
                        <div x-show="selectedVariant && selectedVariant.image_url" class="swiper-slide">
                            <img :src="selectedVariant.image_url" alt="Ảnh biến thể" class="w-full h-96 object-cover">
                        </div>

                        @forelse ($product->images as $image)
                            <div class="swiper-slide">
                                <img src="{{ $image->image_url }}" alt="{{ $product->name }}"
                                    class="w-full h-96 object-cover">
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <img src="https://loremflickr.com/800/800/shoe" alt="{{ $product->name }}"
                                    class="w-full h-96 object-cover">
                            </div>
                        @endforelse
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>

                <div id="gallery-thumbs" class="swiper mt-4 w-full">
                    <div class="swiper-wrapper">
                        <div x-show="selectedVariant && selectedVariant.image_url"
                            class="swiper-slide h-24 w-24 rounded-md overflow-hidden cursor-pointer border-2 border-transparent">
                            <img :src="selectedVariant.image_url" alt="Ảnh biến thể" class="w-full h-full object-cover">
                        </div>

                        @foreach ($product->images as $image)
                            <div
                                class="swiper-slide h-24 w-24 rounded-md overflow-hidden cursor-pointer border-2 border-transparent">
                                <img src="{{ $image->image_url }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div>
                <div class="mb-2 text-sm text-gray-500">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a> /
                    <a href="{{ route('category.show', $product->category) }}"
                        class="hover:text-indigo-600">{{ $product->category->name }}</a>
                </div>

                <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

                <div class="mt-4">
                    <span class="text-3xl font-bold text-indigo-600"
                        x-text="selectedVariant ? formatCurrency(selectedVariant.price) : 'Vui lòng chọn Size & Màu'">
                    </span>
                    <span x-show="!selectedVariant" class="text-gray-500 text-sm ml-2">(Từ
                        {{ number_format($product->variants_min_price ?? 0, 0, ',', '.') }} đ)</span>
                </div>

                <div class="mt-4 text-sm font-medium" x-show="selectedVariant"
                    :class="{
                        'text-green-600': selectedVariant && selectedVariant.stock_quantity > 0,
                        'text-red-600': selectedVariant && selectedVariant.stock_quantity <= 0
                    }">
                    <span
                        x-text="selectedVariant && selectedVariant.stock_quantity > 0 ? `Còn hàng (${selectedVariant.stock_quantity} sản phẩm)` : 'Hết hàng'"></span>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="mt-6">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <input type="hidden" name="product_variant_id" :value="selectedVariant ? selectedVariant.id : ''">

                    @if ($colors->isNotEmpty())
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Màu sắc: <span x-text="selectedColor"
                                    class="font-bold"></span></h3>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach ($colors as $color)
                                    <button type="button" @click="selectColor('{{ $color }}')"
                                        :class="{
                                            'ring-2 ring-indigo-500': selectedColor == '{{ $color }}',
                                            'ring-1 ring-gray-300': selectedColor != '{{ $color }}'
                                        }"
                                        class="px-4 py-2 rounded-md text-sm font-medium focus:outline-none">
                                        {{ $color }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($sizes->isNotEmpty())
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-900">Size: <span x-text="selectedSize"
                                    class="font-bold"></span></h3>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach ($sizes as $size)
                                    <button type="button" @click="selectSize('{{ $size }}')"
                                        :class="{
                                            'ring-2 ring-indigo-500': selectedSize == '{{ $size }}',
                                            'ring-1 ring-gray-300': selectedSize != '{{ $size }}',
                                            'opacity-50 cursor-not-allowed': !isSizeAvailable('{{ $size }}')
                                        }"
                                        :disabled="!isSizeAvailable('{{ $size }}')"
                                        class="px-4 py-2 rounded-md text-sm font-medium focus:outline-none">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        <x-input-label for="quantity" :value="__('Số lượng')" />
                        <x-text-input id="quantity" name="quantity" type="number" value="1" min="1"
                            class="w-24 mt-1" />
                    </div>

                    <div class="mt-8 flex gap-4">
                        <button type="submit" :disabled="!selectedVariant || selectedVariant.stock_quantity <= 0"
                            :class="{
                                'bg-indigo-600 hover:bg-indigo-700': selectedVariant && selectedVariant.stock_quantity >
                                    0,
                                'bg-gray-400 cursor-not-allowed': !selectedVariant || selectedVariant.stock_quantity <=
                                    0
                            }"
                            class="flex-1 px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span
                                x-text="!selectedVariant ? 'Vui lòng chọn Size/Màu' : (selectedVariant.stock_quantity <= 0 ? 'Đã hết hàng' : 'Thêm vào Giỏ hàng')"></span>
                        </button>

                        <button type="button"
                            class="px-6 py-3 border border-gray-300 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">
                            Mua ngay
                        </button>
                    </div>

                </form>

                <div class="mt-10">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Mô tả sản phẩm</h3>
                    <div class="mt-4 prose text-gray-600">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hàm Alpine.js để quản lý biến thể
        function productVariantSelector(variants) {
            return {
                allVariants: variants,
                selectedColor: null,
                selectedSize: null,
                selectedVariant: null,

                init() {
                    // Nếu chỉ có 1 màu, tự động chọn
                    let colors = {{ Illuminate\Support\Js::from($colors) }};
                    if (colors.length === 1) {
                        this.selectedColor = colors[0];
                    }
                    // Nếu chỉ có 1 size, tự động chọn
                    let sizes = {{ Illuminate\Support\Js::from($sizes) }};
                    if (sizes.length === 1) {
                        this.selectedSize = sizes[0];
                    }
                    this.updateSelectedVariant();
                },

                selectColor(color) {
                    this.selectedColor = color;
                    this.updateSelectedVariant();
                },

                selectSize(size) {
                    this.selectedSize = size;
                    this.updateSelectedVariant();
                },

                // Kiểm tra xem 1 size có khả dụng với màu đã chọn không
                isSizeAvailable(size) {
                    if (!this.selectedColor) return true; // Nếu chưa chọn màu, thì mọi size đều "có vẻ" available
                    let key = String(this.selectedColor).toLowerCase() + '-' + String(size).toLowerCase();
                    return this.allVariants[key] !== undefined;
                },

                // Hàm chính: Tìm biến thể dựa trên lựa chọn
                updateSelectedVariant() {
                    if (this.selectedColor && this.selectedSize) {
                        let key = String(this.selectedColor).toLowerCase() + '-' + String(this.selectedSize).toLowerCase();
                        this.selectedVariant = this.allVariants[key] || null;
                    } else {
                        this.selectedVariant = null;
                    }
                },

                // Hàm format tiền
                formatCurrency(amount) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(amount);
                }
            };
        }

        // Khởi tạo Swiper
        document.addEventListener('DOMContentLoaded', function() {
            // Swiper Thumbs (ảnh nhỏ)
            var galleryThumbs = new Swiper('#gallery-thumbs', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
            });

            // Swiper Main (ảnh to)
            var galleryMain = new Swiper('#gallery-main', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: galleryThumbs,
                },
            });
        });
    </script>
</x-main-layout>
