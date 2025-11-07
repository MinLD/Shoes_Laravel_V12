<x-main-layout>

    <section class="relative">
        <div id="banner-swiper" class="swiper">
            <div class="swiper-wrapper">
                @forelse ($banners as $banner)
                    <div class="swiper-slide bg-gray-200 h-96">
                        <img src="{{ asset($banner) }}" alt="Banner Image" class="w-full h-full object-cover">
                    </div>
                @empty
                    <div class="swiper-slide bg-gray-200 h-96 flex items-center justify-center">
                        <div class="text-center px-4">
                            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Bộ Sưu Tập Giày Mới</h1>
                            <p class="text-lg text-gray-600 mt-2">Khám phá ngay</p>
                        </div>
                    </div>
                    <div class="swiper-slide bg-gray-800 h-96 flex items-center justify-center">
                        <div class="text-center px-4">
                            <h1 class="text-3xl sm:text-4xl font-bold text-white">Giảm Giá Mùa Hè</h1>
                            <p class="text-lg text-gray-300 mt-2">Lên đến 50%</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </section>

    <section class="py-12 bg-white">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                Khám phá Danh mục
            </h2>

            <div id="categories-swiper" class="swiper relative pb-10">
                <div class="swiper-wrapper">

                    @forelse ($categories as $category)
                        <a href="{{ route('category.show', $category) }}"
                            class="swiper-slide w-48 text-center group transition-transform duration-300 ease-in-out hover:scale-105">

                            <div
                                class="w-full h-48 bg-gray-200 rounded-lg overflow-hidden transition-shadow duration-300 ease-in-out group-hover:shadow-lg">
                                <img src="{{ $category->image_url ?? 'https://loremflickr.com/600/400/shoe' }}"
                                    alt="{{ $category->name }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="mt-2 text-md font-semibold text-gray-700 group-hover:text-indigo-600">
                                {{ $category->name }}
                            </h3>
                        </a>
                    @empty
                        <p>Không có danh mục nào.</p>
                    @endforelse

                </div>
                <div class="swiper-button-prev cat-prev -left-2"></div>
                <div class="swiper-button-next cat-next -right-2"></div>
            </div>
        </div>
    </section>

    <section class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">
            Sản Phẩm Nổi Bật
        </h2>

        <div id="products-swiper" class="swiper relative pb-10">
            <div class="swiper-wrapper">

                @forelse ($products as $product)
                    <div
                        class="swiper-slide w-72 border rounded-lg bg-white shadow-sm overflow-hidden group transition-all duration-300 ease-in-out hover:shadow-xl hover:scale-105">
                        <div class="w-full h-48 bg-gray-200">
                            <img src="{{ $product->images->first()?->image_url ?? 'https://loremflickr.com/800/600/sneaker' }}"
                                alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-gray-800 group-hover:text-indigo-600 truncate">
                                {{ $product->name }}</h3>
                            <p class="text-gray-700 mt-1">
                                {{ number_format($product->variants_min_price ?? 0, 0, ',', '.') }} đ
                            </p>
                            <a href="{{ route('product.show', $product) }}"
                                class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-500">Xem
                                chi tiết</a>
                        </div>
                    </div>
                @empty
                    <p>Không có sản phẩm nào để hiển thị.</p>
                @endforelse

            </div>
            <div class="swiper-button-prev prod-prev -left-2"></div>
            <div class="swiper-button-next prod-next -right-2"></div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <img src="https://loremflickr.com/800/600/person,shoe" alt="Chất lượng"
                        class="w-full h-80 object-cover rounded-lg shadow-md">
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase text-indigo-600">Chất lượng hàng đầu</h3>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900">
                        Được thiết kế cho sự thoải mái
                    </h2>
                    <p class="mt-4 text-lg text-gray-600">
                        Khám phá bộ sưu tập giày của chúng tôi, được chế tác từ những vật liệu tốt nhất để mang lại sự
                        thoải mái và phong cách bền bỉ. Dù bạn đang chạy marathon hay đi dạo phố, chúng tôi đều có đôi
                        giày hoàn hảo cho bạn.
                    </p>
                    <a href="#"
                        class="mt-6 inline-block px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Mua sắm ngay
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Khởi tạo Banner Swiper
            const bannerSwiper = new Swiper('#banner-swiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });

            // 2. Khởi tạo Categories Swiper
            const categoriesSwiper = new Swiper('#categories-swiper', {
                slidesPerView: 2,
                spaceBetween: 16,
                navigation: {
                    nextEl: '.cat-next',
                    prevEl: '.cat-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 4,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 6,
                        spaceBetween: 24,
                    },
                }
            });

            // 3. Khởi tạo Products Swiper
            const productsSwiper = new Swiper('#products-swiper', {
                slidesPerView: 1,
                spaceBetween: 16,
                navigation: {
                    nextEl: '.prod-next',
                    prevEl: '.prod-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 24,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 24,
                    },
                }
            });

        });
    </script>

</x-main-layout>

