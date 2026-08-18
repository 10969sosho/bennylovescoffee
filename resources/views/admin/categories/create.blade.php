@extends('admin.layouts.admin')

@section('title', 'Create Category')
@section('page-title', 'Create New Category')

@section('content')
    <div class="w-full">
        <div class="admin-card p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Create New Category</h2>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
                @csrf
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <h4 class="font-semibold mb-2">Please fix the following errors:</h4>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-3">
                                Category Name
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-4 futuristic-input @error('name') border-red-500 @enderror"
                                   placeholder="Enter category name"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-3">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full px-4 py-4 futuristic-input @error('description') border-red-500 @enderror"
                                      placeholder="Enter category description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-3 text-sm text-gray-600">Category is active (visible to customers)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Right Column - Image -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Category Image
                            </label>
                            <div class="photo-upload-area p-6 text-center">
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*"
                                       class="hidden"
                                       onchange="previewImage(this)">
                                <label for="image" class="cursor-pointer">
                                    <i class="fas fa-camera text-4xl text-blue-500 mb-4"></i>
                                    <p class="text-gray-600">Click to upload category image</p>
                                    <p class="text-sm text-gray-500">Recommended: 400x400px</p>
                                </label>
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <img id="imageImg" src="" alt="Category image preview" class="w-32 h-32 object-cover rounded-lg mx-auto">
                            </div>
                            @error('image')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel
                    </a>
                    <button type="submit" 
                            class="admin-btn text-white px-8 py-3 rounded-lg">
                        <i class="fas fa-save mr-2"></i>Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const img = document.getElementById('imageImg');
            
            if (input.files && input.files[0]) {
                preview.classList.remove('hidden');
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
@endsection