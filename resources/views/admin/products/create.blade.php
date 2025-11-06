<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('admin.dashboard', ['view' => 'products']) }}" class="text-indigo-600 hover:text-indigo-900">
                Quản lý Sản phẩm
            </a>
            / {{ __('Tạo Sản Phẩm Mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

                    <form method="POST" action="{{ route('admin.products.store') }}">
                        @csrf
                        <div class="space-y-6">
                            
                            <div>
                                <x-input-label for="name" :value="__('Tên Sản Phẩm')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            </div>

                            <div>
                                <x-input-label for="category_id" :value="__('Danh Mục')" />
                                <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">-- Chọn Danh Mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Mô tả')" />
                                <textarea id="description" name="description" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Lưu và Tiếp tục') }}</x-primary-button>
                                
                                <a href="{{ route('admin.dashboard', ['view' => 'products']) }}" class="text-sm text-gray-600 hover:text-gray-900">
                                    {{ __('Hủy bỏ') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>