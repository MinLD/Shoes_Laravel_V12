<header x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-200">

    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex-shrink-0">
                <a href="/" class="text-2xl font-bold text-gray-900">
                    SHOE<span class="text-indigo-600">SHOP</span>
                </a>
            </div>

            <div x-data="searchComponent()" @click.away="isOpen = false"
                class="hidden sm:flex flex-1 px-8 lg:px-16 relative">
                <form @submit.prevent="submitSearch" class="w-full">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="search" name="q" id="q_desktop" x-model="query"
                            @input.debounce.300ms="fetchSuggestions()" @focus="if (results.length > 0) isOpen = true"
                            autocomplete="off"
                            class="block w-full rounded-md border-0 py-2.5 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            placeholder="Tìm kiếm sản phẩm...">
                    </div>
                </form>

                <div x-show="isOpen"
                    class="absolute z-10 w-full mt-1 top-full bg-white rounded-lg shadow-lg border max-h-96 overflow-y-auto"
                    style="display: none;">

                    <div x-show="isLoading" class="p-4 text-sm text-gray-500">Đang tìm...</div>
                    <div x-show="!isLoading && results.length === 0 && query.length > 1"
                        class="p-4 text-sm text-gray-500">
                        Không tìm thấy kết quả nào cho "<span x-text="query"></span>"
                    </div>
                    <template x-for="result in results" :key="result.url">
                        <a :href="result.url" class="flex items-center p-3 hover:bg-gray-100">
                            <img :src="result.image" alt=""
                                class="w-12 h-12 object-cover rounded-md flex-shrink-0">
                            <span class="ml-3 text-sm font-medium text-gray-700" x-text="result.name"></span>
                        </a>
                    </template>
                </div>
            </div>

            <div class="hidden sm:flex items-center space-x-4">
                <a href="{{ route('cart.index') }}" class="p-2 text-gray-500 hover:text-gray-700">
                    <span class="sr-only">Giỏ hàng</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Trang
                        cá nhân</a>
                    <span class="text-gray-300">|</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-sm font-medium text-gray-700 hover:text-gray-900">
                            {{ __('Đăng xuất') }}
                        </a>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Đăng
                        nhập</a>
                    <a href="{{ route('register') }}"
                        class="ml-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Đăng ký
                    </a>
                @endauth
            </div>

            <div class="flex sm:hidden items-center space-x-2">
                <a href="{{ route('cart.index') }}" class="p-2 text-gray-500 hover:text-gray-700">
                    <span class="sr-only">Giỏ hàng</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </a>
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <span class="sr-only">Mở menu</span>
                    <svg class="h-6 w-6" x-show="!open" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg class="h-6 w-6" x-show="open" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <nav class="hidden sm:block bg-white shadow-sm -mt-px">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-12 space-x-8">
                <a href="/"
                    class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium text-gray-900">Trang
                    Chủ</a>
                <a href="#"
                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Giày
                    Nam</a>
                <a href="#"
                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Giày
                    Nữ</a>
                <a href="#"
                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Khuyến
                    Mãi</a>
            </div>
        </div>
    </nav>

    <div x-show="open" @click.away="open = false" class="sm:hidden fixed inset-0 z-50" style="display: none;">

        <div class="fixed inset-0 bg-black bg-opacity-25" aria-hidden="true"></div>

        <div class="relative max-w-xs w-full bg-white h-full shadow-xl p-4">
            <div class="flex justify-end">
                <button @click="open = false"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <span class="sr-only">Đóng menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div x-data="searchComponent()" @click.away="isOpen = false" class="mt-4 relative">
                <form @submit.prevent="submitSearch" class="w-full">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="search" name="q" id="q_mobile" x-model="query"
                            @input.debounce.300ms="fetchSuggestions()" @focus="if (results.length > 0) isOpen = true"
                            autocomplete="off"
                            class="block w-full rounded-md border-0 py-2 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            placeholder="Tìm kiếm...">
                    </div>
                </form>

                <div x-show="isOpen"
                    class="absolute z-10 w-full mt-1 bg-white rounded-lg shadow-lg border max-h-80 overflow-y-auto"
                    style="display: none;">

                    <div x-show="isLoading" class="p-3 text-sm text-gray-500">Đang tìm...</div>
                    <div x-show="!isLoading && results.length === 0 && query.length > 1"
                        class="p-3 text-sm text-gray-500">
                        Không tìm thấy...
                    </div>
                    <template x-for="result in results" :key="result.url">
                        <a :href="result.url" class="flex items-center p-3 hover:bg-gray-100">
                            <img :src="result.image" alt=""
                                class="w-10 h-10 object-cover rounded-md flex-shrink-0">
                            <span class="ml-2 text-sm font-medium text-gray-700" x-text="result.name"></span>
                        </a>
                    </template>
                </div>
            </div>

            <nav class="mt-6 space-y-1">
                <a href="/"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 bg-gray-100">Trang Chủ</a>
                <a href="#"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">Giày
                    Nam</a>
                <a href="#"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">Giày
                    Nữ</a>
                <a href="#"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">Khuyến
                    Mãi</a>
            </nav>

            <div class="mt-6 border-t border-gray-200 pt-4 space-y-2">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">Trang
                        cá nhân</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                            {{ __('Đăng xuất') }}
                        </a>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">Đăng
                        nhập</a>
                    <a href="{{ route('register') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">Đăng
                        ký</a>
                @endauth
            </div>

        </div>
    </div>
</header>
