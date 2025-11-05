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
                        <option value="{{ route('admin.users.index', ['view' => 'users']) }}" 
                                {{ ($viewType === 'users') ? 'selected' : '' }}>
                            {{ __('Quản lý Người Dùng') }}
                        </option>
        
                        <option value="{{ route('admin.users.index', ['view' => 'categories']) }}" 
                                {{ ($viewType === 'categories') ? 'selected' : '' }}>
                            {{ __('Quản lý Danh Mục Sản Phẩm') }}
                        </option>
        
                        <option value="{{ route('admin.users.index', ['view' => 'products']) }}"
                                {{ ($viewType === 'products') ? 'selected' : '' }}>
                            {{ __('Quản lý Sản Phẩm') }}
                        </option>
                    </select>
                </h2>
        
                {{-- PHẦN TÌM KIẾM --}}
                <div class="relative w-full max-w-80 ">
                    <input class="form-control mr-5 w-full p-3 rounded-md border-gray-300 shadow-sm" type="text" placeholder="Tim kiem"/>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-8 pointer-events-none"> 
                        <span class="flex justify-center items-center bg-black text-white rounded-full w-6 h-6">?</span>
                    </div>
                </div>
            </div>
        </x-slot>

        {{-- PHẦN NỘI DUNG CHÍNH (VỚI IF/ELSE) --}}
        <div class="py-12" x-data="{
    editUser: null, 
    allRoles: {{ isset($roles) ? $roles->toJson() : '[]' }},
        openEditModal(user) {
        this.editUser = user;
        this.editUser.role_id = user.roles.length > 0 ? user.roles[0].id : '';
        this.editUser.phone_number = user.profile ? user.profile.phone_number : '';
        this.editUser.address = user.profile ? user.profile.address : '';
        $dispatch('open-modal', 'edit-user-modal');
    }
}" >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        {{-- Thông báo (sẽ được xử lý bởi Toastify) --}}

                        {{-- =============================================== --}}
                        {{-- BẮT ĐẦU LOGIC IF/ELSE ĐỂ HIỂN THỊ BẢNG --}}
                        {{-- =============================================== --}}

                        @if($viewType === 'users')
                    
                            {{-- 1. BẢNG QUẢN LÝ NGƯỜI DÙNG (Code của bạn) --}}
                            <div class="overflow-x-auto shadow-md sm:rounded-lg">
                            <table class=" min-w-full divide-y divide-gray-200 ">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vai trò</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
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
                                      <button
    type="button" 
    @click.prevent='openEditModal(@json($user))'
    class="text-indigo-600 hover:text-indigo-900 mr-4">
    Chỉnh sửa
</button>

                                            <form class="inline" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                 @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                            <div class="mt-4">
                                {{-- Rất quan trọng: appends() để giữ ?view=users khi chuyển trang --}}
                                {{ $users->appends(['view' => 'users'])->links() }}
                            </div>

                        @elseif($viewType === 'categories')

                            {{-- 2. BẢNG QUẢN LÝ DANH MỤC (Code ví dụ) --}}
                            <h3 class="text-lg font-semibold mb-4">Quản lý Danh mục</h3>
                            <table class="min-w-full divide-y divide-gray-200 ">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Danh Mục</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Chưa có danh mục nào.</td>
                                    </tr>
                                    </tbody> 
                            </table>
        
                            

                        @elseif($viewType === 'products')

                            {{-- 3. BẢNG QUẢN LÝ SẢN PHẨM (Code ví dụ) --}}
                            <h3 class="text-lg font-semibold mb-4">Quản lý Sản phẩm</h3>
                            {{-- (Bạn tự thêm bảng sản phẩm ở đây) --}}
                            <p>Đây là khu vực quản lý sản phẩm...</p>

                            @if(isset($products))
                            <div class="mt-4">
                                {{ $products->appends(['view' => 'products'])->links() }}
                            </div>
                            @endif

                        @endif
                        
                        {{-- =============================================== --}}
                        {{-- KẾT THÚC LOGIC IF/ELSE --}}
                        {{-- =============================================== --}}

                    </div>
                </div>
            </div>
            <x-modal name="edit-user-modal" max-width="lg">
                <template x-if="editUser">
                    <form :action="`/admin/${editUser.id}`" method="POST" class="p-6">
                        @csrf
                        @method('PATCH')

                        <h2 class="text-lg font-medium text-gray-900">
                            Chỉnh sửa người dùng: <span x-text="editUser.name"></span>
                        </h2>

                        <div class="mt-6 space-y-4">
                            <div>
                                <x-input-label for="name" :value="__('Tên')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" x-model="editUser.name" required />
                            </div>
                            
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" x-model="editUser.email" required />
                            </div>

                            <div>
                                <x-input-label for="phone_number" :value="__('Số điện thoại')" />
                                <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-1 block w-full" x-model="editUser.phone_number" />
                            </div>

                            <div>
                                <x-input-label for="address" :value="__('Địa chỉ')" />
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" x-model="editUser.address" />
                            </div>
                            
                            <div>
                                <x-input-label for="role_id" :value="__('Vai trò')" />
                                <select name="role_id" id="role_id" x-model="editUser.role_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="">-- Chọn vai trò --</option>
                                    <template x-for="role in allRoles" :key="role.id">
                                        <option :value="role.id" x-text="role.display_name" :selected="role.id == editUser.role_id"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                                                <div class="mt-6 flex justify-end">
                            <x-secondary-button @click.prevent="$dispatch('close')">
                                {{ __('Đóng') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-3">
                                {{ __('Lưu thay đổi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </template>
            </x-modal>
        </div>
    </x-app-layout>

