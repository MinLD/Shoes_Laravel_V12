<x-app-layout
>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('admin.dashboard', ['view' => 'products']) }}" class="text-indigo-600 hover:text-indigo-900">
                Quản lý Sản phẩm
            </a>
            / Chỉnh sửa: {{ $product->name }}
        </h2>
    </x-slot>
<div  x-data="{ 
        tab: 'details',
        
        // --- Thêm logic cho Biến thể ---
        showVariantModal: false,
        isCreatingVariant: false,
        editVariant: null,
        
        openVariantModal(isCreating, variant = null) {
            this.isCreatingVariant = isCreating;
            if (isCreating) {
                this.editVariant = {}; // Tạo rỗng
            } else {
                this.editVariant = variant; // Nạp dữ liệu
            }
            $dispatch('open-modal', 'variant-modal');
        }
    }">
    <div x-data="{ tab: 'details' }" 
   >
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="mb-4 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button 
                            @click.prevent="tab = 'details'"
                            :class="{ 'border-indigo-500 text-indigo-600': tab === 'details', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'details' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Thông tin cơ bản
                        </button>
                        <button 
                            @click.prevent="tab = 'variants'"
                            :class="{ 'border-indigo-500 text-indigo-600': tab === 'variants', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'variants' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Biến thể (Size/Màu/Giá)
                        </button>
                        <button 
                            @click.prevent="tab = 'images'"
                            :class="{ 'border-indigo-500 text-indigo-600': tab === 'images', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'images' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Thư viện Ảnh
                        </button>
                    </nav>
                </div>

                <div x-show="tab === 'details'">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            
                            @if ($errors->any())
                                <div class="mb-4">
                                    <div class="font-medium text-red-600">{{ __('Rất tiếc! Có lỗi xảy ra.') }}</div>
                                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.products.update', $product) }}">
                                @csrf
                                @method('PATCH')
                                <div class="space-y-6">
                                    
                                    <div>
                                        <x-input-label for="name" :value="__('Tên Sản Phẩm')" />
                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required autofocus />
                                    </div>

                                    <div>
                                        <x-input-label for="category_id" :value="__('Danh Mục')" />
                                        <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="description" :value="__('Mô tả')" />
                                        <textarea id="description" name="description" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                                    </div>

                                    <div class="block mt-4">
                                        <label for="status" class="inline-flex items-center">
                                            <input id="status" type="checkbox" name="status" value="published"
                                                {{ old('status', $product->status) == 'published' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">{{ __('Công khai sản phẩm (Hiển thị cho khách hàng)') }}</span>
                                        </label>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <x-primary-button>{{ __('Lưu Thay Đổi') }}</x-primary-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'variants'">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h3 class="text-lg font-medium">Quản lý Biến thể (Size/Màu/Giá)</h3>
            
            <div class="mt-4 mb-4 flex justify-end">
                <x-primary-button
                    @click.prevent="openVariantModal(true)">
                    {{ __('Thêm Biến Thể Mới') }}
                </x-primary-button>
            </div>

            <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ảnh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Màu Sắc</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn Kho</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($product->variants as $variant)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($variant->image_url)
                                <img src="{{ $variant->image_url }}" alt="{{ $variant->name }}" class="w-10 h-10 rounded-md object-cover">
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $variant->color ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $variant->size ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($variant->price, 0, ',', '.') }} đ</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $variant->stock_quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            
                        <button
    type="button" 
    @click.prevent='openVariantModal(false, @json($variant))'
    class="text-indigo-600 hover:text-indigo-900 mr-4">
    Chỉnh sửa
</button>

                            <form class="inline" action="{{ route('admin.variants.destroy', ['product' => $product, 'variant' => $variant]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa biến thể này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Chưa có biến thể nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            
        </div>
    </div>
</div>

               

                <div x-show="tab === 'images'">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h3 class="text-lg font-medium">Quản lý Thư viện Ảnh</h3>
            <p class="mt-2 text-sm text-gray-600 mb-4">
                Upload nhiều ảnh cho sản phẩm. Các ảnh này sẽ hiển thị trong thư viện ảnh (gallery) của trang chi tiết sản phẩm.
            </p>

            <form action="{{ route('admin.images.store', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <x-input-label for="images" :value="__('Chọn ảnh mới (có thể chọn nhiều ảnh)')" />
                    <input id="images" name="images[]" type="file" multiple
                           class="mt-1 block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100" />
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4">
                    <x-primary-button>{{ __('Upload Ảnh') }}</x-primary-button>
                </div>
            </form>

            <div class="mt-8">
                <h4 class="text-md font-medium">Các ảnh hiện tại:</h4>
                @if($product->images->isEmpty())
                    <p class="mt-2 text-sm text-gray-500">Sản phẩm này chưa có ảnh nào trong thư viện.</p>
                @else
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach ($product->images as $image)
                            <div classs="relative group border rounded-lg overflow-hidden">
                                <img src="{{ $image->image_url }}" alt="Ảnh sản phẩm" class="h-40 w-full object-cover">
                                <div class="p-2">
                                    <form action="{{ route('admin.images.destroy', ['product' => $product, 'image' => $image]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ảnh này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type-="submit" class="text-red-600 hover:text-red-900 text-sm w-full text-center">
                                            Xóa ảnh
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
            </div>
        </div>
    </div>


    {{-- =============================================== --}}
    {{-- MODAL THÊM/SỬA BIẾN THỂ --}}
    {{-- =============================================== --}}
 <x-modal name="variant-modal" max-width="lg">
        <template x-if="editVariant">
            
            <form 
                :action="isCreatingVariant ? '{{ route('admin.variants.store', $product) }}' : `/admin/variants/${editVariant.id}`" 
                method="POST" class="p-6" enctype="multipart/form-data"
            >
                @csrf
                <template x-if="!isCreatingVariant">
                    @method('PATCH')
                </template>

                <h2 class="text-lg font-medium text-gray-900">
                    <span x-text="isCreatingVariant ? 'Tạo Biến Thể Mới' : 'Chỉnh sửa Biến Thể'"></span>
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Nhập thông tin cho phiên bản này của sản phẩm (ví dụ: Màu Đen, Size 42).
                </p>

                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <x-input-label for="var_color" :value="__('Màu Sắc (tùy chọn)')" />
                        <x-text-input id="var_color" name="color" type="text" class="mt-1 block w-full" x-model="editVariant.color" placeholder="Vd: Đen, Trắng/Đỏ" />
                    </div>

                    <div>
                        <x-input-label for="var_size" :value="__('Size (tùy chọn)')" />
                        <x-text-input id="var_size" name="size" type="text" class="mt-1 block w-full" x-model="editVariant.size" placeholder="Vd: 41, 42.5, L" />
                    </div>

                    <div>
                        <x-input-label for="var_price" :value="__('Giá (bắt buộc)')" />
                        <x-text-input id="var_price" name="price" type="number" step="1000" min="0" class="mt-1 block w-full" x-model="editVariant.price" required />
                    </div>

                    <div>
                        <x-input-label for="var_stock" :value="__('Số lượng Tồn kho (bắt buộc)')" />
                        <x-text-input id="var_stock" name="stock_quantity" type="number" min="0" class="mt-1 block w-full" x-model="editVariant.stock_quantity" required />
                    </div>
                    
                    <div class="sm:col-span-2">
                        <x-input-label for="var_image" :value="__('Ảnh Đại Diện (cho màu này)')" />
                        <template x-if="!isCreatingVariant && editVariant.image_url">
                            <img :src="editVariant.image_url" alt="Ảnh hiện tại" class="w-20 h-20 rounded-md object-cover my-2">
                        </template>
                        <input id="var_image" name="image" type="file"
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
                        <span x-text="isCreatingVariant ? 'Tạo' : 'Lưu thay đổi'"></span>
                    </x-primary-button>
                </div>
            </form>
        </template>
    </x-modal>
    
</div>
</x-app-layout>