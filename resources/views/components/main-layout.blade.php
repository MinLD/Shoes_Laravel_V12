<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .swiper-button-next,
        .swiper-button-prev {
            color: #4f46e5;
            /* Màu indigo */
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">

        <x-site-header />

        <main class="flex-grow">
            {{ $slot }}
        </main>

        <x-site-footer />

    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('searchComponent', () => ({
                query: '{{ request('q', '') }}', // Lấy query từ URL (nếu có)
                results: [],
                isOpen: false,
                isLoading: false,

                fetchSuggestions() {
                    // Nếu query dưới 2 ký tự, đóng lại
                    if (this.query.length < 2) {
                        this.isOpen = false;
                        this.results = [];
                        return;
                    }

                    this.isLoading = true;

                    // Gọi API chúng ta vừa tạo
                    fetch(`/api/search-suggestions?q=${this.query}`)
                        .then(response => response.json())
                        .then(data => {
                            this.results = data;
                            this.isOpen = true;
                            this.isLoading = false;
                        });
                },

                // Hàm chuyển hướng khi bấm Enter (submit form)
                submitSearch() {
                    // Chuyển hướng đến trang search đầy đủ
                    window.location.href = `/search?q=${this.query}`;
                }
            }));
        });
    </script>

</body>

</html>
</body>

</html>

