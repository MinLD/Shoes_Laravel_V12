    <x-app-layout>
        {{-- PHẦN HEADER VỚI SELECT --}}
        <x-slot name="header">
            <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">

                    {{-- 
                    1. Dùng onchange để tải lại trang
                    2. 'this.value' sẽ là URL đầy đủ (ví dụ: .../admin?view=categories)
                    --}}
                    <select class="form-control rounded-md border-gray-300 shadow-sm"
                        onchange="window.location.href = this.value;">

                        {{-- 
                        - value: Dùng route() để tạo URL với ?view=...
                        - selected: Kiểm tra $viewType từ Controller
                        --}}
                        <option value="{{ route('admin.dashboard', ['view' => 'users']) }}"
                            {{ $viewType === 'users' ? 'selected' : '' }}>
                            {{ __('Quản lý Người Dùng') }}
                        </option>

                        <option value="{{ route('admin.dashboard', ['view' => 'categories']) }}"
                            {{ $viewType === 'categories' ? 'selected' : '' }}>
                            {{ __('Quản lý Danh Mục Sản Phẩm') }}
                        </option>

                        <option value="{{ route('admin.dashboard', ['view' => 'products']) }}"
                            {{ $viewType === 'products' ? 'selected' : '' }}>
                            {{ __('Quản lý Sản Phẩm') }}
                        </option>
                        <option value="{{ route('admin.dashboard', ['view' => 'orders']) }}"
                            {{ $viewType === 'orders' ? 'selected' : '' }}>
                            {{ __('Quản lý Đơn Hàng') }}
                        </option>
                    </select>
                </h2>

                {{-- PHẦN TÌM KIẾM --}}

            </div>
        </x-slot>

        {{-- PHẦN NỘI DUNG CHÍNH (VỚI IF/ELSE) --}}
        <div class="py-12" x-data="{
            editUser: null,
            isCreatingUser: false,
            allRoles: {{ isset($roles) ? $roles->toJson() : '[]' }},
            openUserModal(isCreating, user = null) {
                this.isCreatingUser = isCreating;
                if (isCreating) {
                    this.editUser = { name: '', email: '', phone_number: '', address: '', role_id: '' };
                } else {
                    this.editUser = user;
                    this.editUser.role_id = user.roles.length > 0 ? user.roles[0].id : '';
                    this.editUser.phone_number = user.profile ? user.profile.phone_number : '';
                    this.editUser.address = user.profile ? user.profile.address : '';
                }
                $dispatch('open-modal', 'user-modal');
            },
            editCategory: null,
            isCreatingCategory: false
        }">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        {{-- Thông báo (sẽ được xử lý bởi Toastify) --}}

                        {{-- =============================================== --}}
                        {{-- BẮT ĐẦU LOGIC IF/ELSE ĐỂ HIỂN THỊ BẢNG --}}
                        {{-- =============================================== --}}

                        @if ($viewType === 'users')

                            <div class="mb-4 flex justify-between ">
                                <div class="w-full">
                                    <form method="GET" action="{{ route('admin.dashboard') }}">
                                        <input type="hidden" name="view" value="{{ $viewType }}">

                                        @if ($viewType === 'products' && !empty($selectedCategoryId))
                                            <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
                                        @endif

                                        <div class="flex">
                                            <x-text-input name="search" type="text" class="block w-full md:w-1/3"
                                                placeholder="Nhập từ khóa (SDT, email, ...)" :value="$search" />
                                            <x-primary-button class="ml-3">{{ __('Tìm kiếm') }}</x-primary-button>
                                        </div>
                                    </form>
                                </div>
                                <div class="flex items-center justify-center w-full max-w-[300px]">
                                    <button @click.prevent="openUserModal(true)" type="button"
                                        class=" text-center inline-flex items-center px-6 py-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Thêm Người Dùng Mới') }}
                                    </button>
                                </div>
                            </div>
                            {{-- 1. BẢNG QUẢN LÝ NGƯỜI DÙNG (Code của bạn) --}}
                            <div class="overflow-x-auto shadow-md sm:rounded-lg">
                                <table class=" min-w-full divide-y divide-gray-200 ">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tên</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Vai trò</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $user->roles->pluck('display_name')->join(', ') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button type="button"
                                                        @click.prevent='openUserModal(false, @json($user))'
                                                        class="text-indigo-600 hover:text-indigo-900 mr-4">
                                                        Chỉnh sửa
                                                    </button>

                                                    <form class="inline"
                                                        action="{{ route('admin.users.destroy', $user->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{-- Rất quan trọng: appends() để giữ ?view=users khi chuyển trang --}}
                                {{ $users->appends(['view' => 'users', 'search' => $search])->links() }}
                            </div>
                        @elseif($viewType === 'categories')
                            {{-- 2. BẢNG QUẢN LÝ DANH MỤC (Code ví dụ) --}}
                            <div class="mb-4 flex justify-between ">
                                <div class="w-full">
                                    <form method="GET" action="{{ route('admin.dashboard') }}">
                                        <input type="hidden" name="view" value="{{ $viewType }}">

                                        @if ($viewType === 'products' && !empty($selectedCategoryId))
                                            <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
                                        @endif

                                        <div class="flex">
                                            <x-text-input name="search" type="text" class="block w-full md:w-1/3"
                                                placeholder="Nhập từ khóa (Tên Danh Mục ...)" :value="$search" />
                                            <x-primary-button class="ml-3">{{ __('Tìm kiếm') }}</x-primary-button>
                                        </div>
                                    </form>
                                </div>
                                <div class="flex items-center justify-center w-full max-w-[300px]">
                                    <button type="button"
                                        @click.prevent="
                isCreatingCategory = true;
                editCategory = {};
                $dispatch('open-modal', 'category-modal');
            "
                                        class="inline-flex items-center px-6 py-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Thêm Danh Mục Mới') }}
                                    </button>
                                </div>
                            </div>

                            <div class="overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ảnh</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tên Danh Mục</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mô tả</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($categories as $category)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($category->image_url)
                                                        <img src="{{ $category->image_url }}"
                                                            alt="{{ $category->name }}"
                                                            class="w-10 h-10 rounded-md object-cover">
                                                    @else
                                                        <span class="text-gray-400">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium">
                                                    {{ $category->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 truncate"
                                                    style="max-width: 300px;">{{ $category->description }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button type="button"
                                                        @click.prevent='
        isCreatingCategory = false;
        editCategory = @json($category);
        $dispatch("open-modal", "category-modal");
    '
                                                        class="text-indigo-600 hover:text-indigo-900 mr-4">
                                                        Chỉnh sửa
                                                    </button>
                                                    <form class="inline"
                                                        action="{{ route('admin.categories.destroy', $category) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Chưa có
                                                    danh mục nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $categories->appends(['view' => 'categories'])->links() }}
                            </div>

                            {{-- =============================================== --}}
                            {{-- 3. BẢNG QUẢN LÝ SẢN PHẨM --}}
                            {{-- =============================================== --}}
                        @elseif($viewType === 'products')
                            <div class="flex justify-between items-center mb-4">
                                <div class="space-y-3 w-full">
                                    <div class=" pb-3 w-full">
                                        <label for="category_filter" class="text-sm font-medium text-gray-700">Lọc
                                            theo danh mục:</label>
                                        <select id="category_filter"
                                            class="mt-1 block w-full md:w-1/3 rounded-md border-gray-300 shadow-sm"
                                            onchange="window.location.href = this.value;">

                                            <option value="{{ route('admin.dashboard', ['view' => 'products']) }}"
                                                {{ !$selectedCategoryId ? 'selected' : '' }}>
                                                -- Tất cả danh mục --
                                            </option>

                                            @foreach ($all_categories as $category)
                                                <option
                                                    value="{{ route('admin.dashboard', ['view' => 'products', 'category_id' => $category->id]) }}"
                                                    {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4 w-full">
                                        <form method="GET" action="{{ route('admin.dashboard') }}">
                                            <input type="hidden" name="view" value="{{ $viewType }}">

                                            @if ($viewType === 'products' && !empty($selectedCategoryId))
                                                <input type="hidden" name="category_id"
                                                    value="{{ $selectedCategoryId }}">
                                            @endif

                                            <div class="flex">
                                                <x-text-input name="search" type="text"
                                                    class="block w-full md:w-1/3"
                                                    placeholder="Nhập từ khóa (Tên Sản Phẩm ...)" :value="$search" />
                                                <x-primary-button
                                                    class="ml-3">{{ __('Tìm kiếm') }}</x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class=" w-full max-w-[300px]  bottom-0">

                                    <a href="{{ route('admin.products.create') }}"
                                        class="px-6 py-4 inline-flex items-center  bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Thêm Sản Phẩm Mới') }}
                                    </a>
                                </div>

                            </div>

                            <div class="overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tên Sản Phẩm</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Danh Mục</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Số Biến Thể</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($products as $product)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium">
                                                    {{ $product->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $product->category->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $product->variants->count() }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-5">

                                                    <div>
                                                        <a href="{{ route('admin.products.edit', $product) }}"
                                                            class="text-indigo-600 hover:text-indigo-900 ">
                                                            Chỉnh sửa
                                                        </a>
                                                    </div>

                                                    <form classG="inline"
                                                        action="{{ route('admin.products.destroy', $product) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa? Thao tác này sẽ xóa tất cả biến thể con!');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Chưa có
                                                    sản phẩm nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $products->appends(['view' => 'products', 'category_id' => $selectedCategoryId])->links() }}
                            </div>

                            {{-- =============================================== --}}
                            {{-- 4. BẢNG QUẢN LÝ ĐƠN HÀNG --}}
                            {{-- =============================================== --}}
                        @elseif($viewType === 'orders')
                            <div class="mb-4 w-full ">
                                <form method="GET" action="{{ route('admin.dashboard') }}">
                                    <input type="hidden" name="view" value="{{ $viewType }}">

                                    @if ($viewType === 'products' && !empty($selectedCategoryId))
                                        <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
                                    @endif

                                    <div class="flex">
                                        <x-text-input name="search" type="text" class="block w-full md:w-1/3"
                                            placeholder="Nhập từ khóa (Mã ĐH, ID Khách hàng...)" :value="$search" />
                                        <x-primary-button class="ml-3">{{ __('Tìm kiếm') }}</x-primary-button>
                                    </div>
                                </form>
                            </div>

                            <h3 class="text-lg font-medium mb-4">Quản lý Đơn Hàng</h3>

                            <div class="overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mã ĐH</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Khách hàng</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tổng tiền</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Trạng thái</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ngày đặt</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($orders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium">
                                                    #{{ $order->id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $order->user->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if ($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                                @if ($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                                                @if ($order->status == 'shipped') bg-green-100 text-green-800 @endif
                                                @if ($order->status == 'cancelled') bg-red-100 text-red-800 @endif
                                            ">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('admin.orders.show', $order) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        Xem chi tiết / Duyệt
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Chưa có
                                                    đơn hàng nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $orders->appends(['view' => 'orders'])->links() }}
                            </div>

                        @endif

                        {{-- =============================================== --}}
                        {{-- KẾT THÚC LOGIC IF/ELSE --}}
                        {{-- =============================================== --}}

                    </div>
                </div>
            </div>
            <x-modal name="user-modal" max-width="lg">
                <template x-if="editUser">
                    <form :action="isCreatingUser ? '{{ route('admin.users.store') }}' : '/admin/' + editUser.id"
                        method="POST" class="p-6">
                        @csrf
                        <template x-if="!isCreatingUser">
                            @method('PATCH')
                        </template>

                        <h2 class="text-lg font-medium text-gray-900">
                            <span
                                x-text="isCreatingUser ? 'Thêm Người Dùng Mới' : 'Chỉnh sửa người dùng: ' + editUser.name"></span>
                        </h2>

                        <div class="mt-6 space-y-4">
                            <div>
                                <x-input-label for="name" :value="__('Tên')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    x-model="editUser.name" required />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                    x-model="editUser.email" required />
                            </div>

                            <div>
                                <x-input-label for="phone_number" :value="__('Số điện thoại')" />
                                <x-text-input id="phone_number" name="phone_number" type="tel"
                                    class="mt-1 block w-full" x-model="editUser.phone_number" />
                            </div>

                            <div>
                                <x-input-label for="address" :value="__('Địa chỉ')" />
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                                    x-model="editUser.address" />
                            </div>

                            <template x-if="isCreatingUser">
                                <div>
                                    <x-input-label for="password" :value="__('Mật khẩu')" />
                                    <x-text-input id="password" name="password" type="password"
                                        class="mt-1 block w-full" required />
                                </div>
                            </template>

                            <template x-if="isCreatingUser">
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" />
                                    <x-text-input id="password_confirmation" name="password_confirmation"
                                        type="password" class="mt-1 block w-full" required />
                                </div>
                            </template>

                            <div>
                                <x-input-label for="role_id" :value="__('Vai trò')" />
                                <select name="role_id" id="role_id" x-model="editUser.role_id"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="">-- Chọn vai trò --</option>
                                    <template x-for="role in allRoles" :key="role.id">
                                        <option :value="role.id" x-text="role.display_name"
                                            :selected="role.id == editUser.role_id"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button @click.prevent="$dispatch('close')">
                                {{ __('Đóng') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-3">
                                <span x-text="isCreatingUser ? 'Tạo Người Dùng' : 'Lưu thay đổi'"></span>
                            </x-primary-button>
                        </div>
                    </form>
                </template>
            </x-modal>
            {{-- =============================================== --}}
            {{-- MODAL THÊM/SỬA DANH MỤC --}}
            {{-- =============================================== --}}
            <x-modal name="category-modal" max-width="lg">

                <template x-if="editCategory">

                    <form
                        :action="isCreatingCategory ? '{{ route('admin.categories.store') }}' :
                            `/admin/categories/${editCategory.id}`"
                        method="POST" class="p-6" enctype="multipart/form-data">
                        @csrf
                        <template x-if="!isCreatingCategory">
                            @method('PATCH')
                        </template>

                        <h2 class="text-lg font-medium text-gray-900">
                            <span x-text="isCreatingCategory ? 'Tạo Danh Mục Mới' : 'Chỉnh sửa Danh Mục'"></span>
                        </h2>

                        <div class="mt-6 space-y-4">
                            <div>
                                <x-input-label for="cat_name" :value="__('Tên Danh Mục')" />
                                <x-text-input id="cat_name" name="name" type="text" class="mt-1 block w-full"
                                    x-model="editCategory.name" required />
                            </div>

                            <div>
                                <x-input-label for="cat_description" :value="__('Mô tả')" />
                                <textarea id="cat_description" name="description" rows="3"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    x-model="editCategory.description"></textarea>
                            </div>

                            <div>
                                <x-input-label for="cat_image" :value="__('Ảnh Danh Mục (Tùy chọn)')" />

                                <template x-if="!isCreatingCategory && editCategory.image_url">
                                    <img :src="editCategory.image_url" alt="Ảnh hiện tại"
                                        class="w-20 h-20 rounded-md object-cover my-2">
                                </template>

                                <input id="cat_image" name="image" type="file"
                                    class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100" />
                                <p class="mt-1 text-xs text-gray-500">Bỏ trống nếu không muốn thay đổi ảnh.</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button @click.prevent="$dispatch('close')">
                                {{ __('Đóng') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-3">
                                <span x-text="isCreatingCategory ? 'Tạo' : 'Lưu thay đổi'"></span>
                            </x-primary-button>
                        </div>
                    </form>
                </template>
            </x-modal>
            {{-- Hết Modal Category --}}

        </div>
    </x-app-layout>
