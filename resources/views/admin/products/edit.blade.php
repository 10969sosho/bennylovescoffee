@extends('admin.layouts.admin')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="w-full">
        <div class="admin-card p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Edit Product: {{ $product->name }}</h2>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf
                @method('PUT')
                
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
                
                <!-- Single Column Layout -->
                <div class="space-y-8">
                    <!-- Product Images Upload Grid -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Product Images
                        </label>
                        <div class="w-1/2">
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Main Photo (Position 1) -->
                                <div class="aspect-square">
                                    <div class="photo-upload-box h-full w-full border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-blue-400 transition" onclick="triggerFileInput('main_photo')">
                                        <input type="file" 
                                               id="main_photo" 
                                               name="main_photo" 
                                               accept="image/*"
                                               class="hidden"
                                               onchange="previewImage(this, 'mainPreview', 'mainImg')">
                                        <div id="mainPreview" class="hidden w-full h-full relative">
                                            <img id="mainImg" src="" alt="Main product image" class="w-full h-full object-cover rounded-lg">
                                            <div class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer hover:bg-red-600" onclick="removeImage('main_photo', 'mainPreview')">
                                                <i class="fas fa-times text-xs"></i>
                                            </div>
                                        </div>
                                        @if($product->main_photo)
                                            <div id="mainCurrent" class="w-full h-full relative">
                                                <img src="{{ asset('storage/' . $product->main_photo) }}" alt="Current main image" class="w-full h-full object-cover rounded-lg">
                                                <div class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer hover:bg-red-600" onclick="removeImage('main_photo', 'mainCurrent')">
                                                    <i class="fas fa-times text-xs"></i>
                                                </div>
                                            </div>
                                        @else
                                            <div id="mainPlaceholder" class="text-center">
                                                <i class="fas fa-camera text-xl text-blue-500 mb-1"></i>
                                                <p class="text-xs text-gray-600">Main Image</p>
                                                <p class="text-xs text-gray-500">1:1</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Additional Photos (Positions 2-6) -->
                                @for($i = 1; $i <= 5; $i++)
                                <div class="aspect-square">
                                    <div class="photo-upload-box h-full w-full border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-blue-400 transition" onclick="triggerFileInput('photo_{{ $i }}')">
                                        <input type="file" 
                                               id="photo_{{ $i }}" 
                                               name="photos[]" 
                                               accept="image/*"
                                               class="hidden"
                                               onchange="previewImage(this, 'photoPreview_{{ $i }}', 'photoImg_{{ $i }}')">
                                        <div id="photoPreview_{{ $i }}" class="hidden w-full h-full relative">
                                            <img id="photoImg_{{ $i }}" src="" alt="Additional photo {{ $i }}" class="w-full h-full object-cover rounded-lg">
                                            <div class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer hover:bg-red-600" onclick="removeImage('photo_{{ $i }}', 'photoPreview_{{ $i }}')">
                                                <i class="fas fa-times text-xs"></i>
                                            </div>
                                        </div>
                                        @if(isset($product->photos[$i-1]))
                                            <div id="photoCurrent_{{ $i }}" class="w-full h-full relative">
                                                <img src="{{ asset('storage/' . $product->photos[$i-1]) }}" alt="Current photo {{ $i }}" class="w-full h-full object-cover rounded-lg">
                                                <div class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer hover:bg-red-600" onclick="removeImage('photo_{{ $i }}', 'photoCurrent_{{ $i }}')">
                                                    <i class="fas fa-times text-xs"></i>
                                                </div>
                                            </div>
                                        @else
                                            <div id="photoPlaceholder_{{ $i }}" class="text-center">
                                                <i class="fas fa-plus text-xl text-gray-400 mb-1"></i>
                                                <p class="text-xs text-gray-600">Photo {{ $i + 1 }}</p>
                                                <p class="text-xs text-gray-500">Optional</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                        
                        <div class="mt-4 text-sm text-gray-600">
                            <p><strong>Main Image:</strong> Required, will be displayed as primary product image</p>
                            <p><strong>Additional Photos:</strong> Optional, up to 5 additional images</p>
                            <p><strong>Recommended:</strong> 800x800px or higher, 1:1 aspect ratio</p>
                        </div>
                        
                        @error('main_photo')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        @error('photos.*')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-3">
                                Product Name
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}"
                                   class="w-full px-4 py-4 futuristic-input @error('name') border-red-500 @enderror"
                                   placeholder="Enter product name"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    <!-- Product Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-3">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                  rows="6"
                                      class="w-full px-4 py-4 futuristic-input @error('description') border-red-500 @enderror"
                                  placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    <!-- Category Selection -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-3">
                                Category
                            </label>
                                <select id="category_id" 
                                        name="category_id"
                                class="w-full px-4 py-4 futuristic-input @error('category_id') border-red-500 @enderror"
                                        required>
                            <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    <!-- Brand Selection -->
                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-3">
                            Brand (Optional)
                        </label>
                        <select id="brand_id" 
                                name="brand_id" 
                                class="w-full px-4 py-4 futuristic-input @error('brand_id') border-red-500 @enderror">
                            <option value="">Select a brand (optional)</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Weight Input -->
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-3">
                            Weight (Grams)
                        </label>
                        <input type="number" 
                               id="weight" 
                               name="weight" 
                               value="{{ old('weight', $product->weight) }}"
                               class="w-full px-4 py-4 futuristic-input @error('weight') border-red-500 @enderror"
                               placeholder="Enter weight in grams"
                               min="0"
                               required>
                        @error('weight')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Variant Selection -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
                        <div class="flex items-center mb-4">
                            <input type="checkbox" 
                                   id="use_variants" 
                                   name="use_variants" 
                                   value="1"
                                   {{ old('use_variants', $product->hasVariants()) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   onchange="toggleVariantFields()">
                            <label for="use_variants" class="ml-3 text-lg font-medium text-gray-700">
                                Use Product Variants
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Check this if your product has different variants (size, color, etc.) with different prices and stock
                        </p>
                    </div>

                    <!-- Standard Product Fields (shown when no variants) -->
                    <div id="standardFields" class="space-y-6">
                        <div class="border-2 border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-3">
                                    Stock Quantity
                                </label>
                                <input type="number" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock', $product->stock) }}"
                                       class="w-full px-4 py-4 futuristic-input @error('stock') border-red-500 @enderror"
                                       placeholder="Enter stock quantity"
                                           min="0">
                                @error('stock')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-3">
                                        Regular Price
                                </label>
                                <input type="number" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $product->price) }}"
                                       class="w-full px-4 py-4 futuristic-input @error('price') border-red-500 @enderror"
                                       placeholder="Enter regular price"
                                           step="0.01"
                                           min="0"
                                           oninput="calculateDiscountedPrice()">
                                @error('price')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                        </div>

                                <div>
                                    <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-3">
                                        Discount Percentage
                                    </label>
                                    <input type="number" 
                                           id="discount_percentage" 
                                           name="discount_percentage" 
                                           value="{{ old('discount_percentage', $product->discount_percentage) }}"
                                           class="w-full px-4 py-4 futuristic-input @error('discount_percentage') border-red-500 @enderror"
                                           placeholder="Enter discount percentage"
                                           min="0"
                                           max="100"
                                           oninput="calculateDiscountedPrice()">
                                    @error('discount_percentage')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Price After Discount
                                    </label>
                                    <div class="w-full px-4 py-4 bg-gray-100 border-2 border-gray-300 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-semibold text-gray-800" id="discountedPrice">Rp 0</span>
                                            <span class="text-sm text-gray-500" id="discountInfo">No discount applied</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Calculated automatically based on regular price and discount</p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-3 text-sm text-gray-600">Product is active (visible to customers)</span>
                            </label>
                        </div>
                    </div>
                        </div>
                    </div>

                    <!-- Variant Fields (shown when variants are enabled) -->
                    <div id="variantFields" class="space-y-6" style="display: none;">
                        <div class="border-2 border-blue-200 rounded-lg p-6 bg-blue-50">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Variants</h3>
                            <p class="text-sm text-gray-600 mb-4">Add different variants of your product with individual pricing and stock</p>
                            
                            <div id="variantsContainer">
                                @if($product->variants->count() > 0)
                                    @foreach($product->variants as $index => $variant)
                                        <div class="variant-item border border-gray-200 rounded-lg p-4 mb-4 bg-white" data-variant="{{ $index + 1 }}">
                                            <div class="flex justify-between items-center mb-4">
                                                <h4 class="text-md font-semibold text-gray-700">Variant {{ $index + 1 }}</h4>
                                                <button type="button" 
                                                        class="text-red-600 hover:text-red-800 transition" 
                                                        onclick="removeVariant('{{ $index + 1 }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Variant Name</label>
                                                    <input type="text" 
                                                           name="variants[{{ $index + 1 }}][name]" 
                                                           value="{{ $variant->name }}"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                           placeholder="e.g., Small, Red, etc."
                                                           required>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                                                    <input type="number" 
                                                           name="variants[{{ $index + 1 }}][quantity]" 
                                                           value="{{ $variant->quantity }}"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                           placeholder="Enter stock"
                                                           min="0"
                                                           required>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                                                    <input type="number" 
                                                           name="variants[{{ $index + 1 }}][price]" 
                                                           value="{{ $variant->price }}"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                           placeholder="Enter price"
                                                           step="0.01"
                                                           min="0"
                                                           required>
                                                </div>
                                    </div>
                                </div>
                                    @endforeach
                            @endif
                            </div>
                            
                            <button type="button" 
                                    id="addVariantBtn" 
                                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i>Add Variant
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ route('admin.products') }}" 
                       class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel
                    </a>
                    <button type="submit" 
                            class="admin-btn text-white px-8 py-3 rounded-lg">
                        <i class="fas fa-save mr-2"></i>Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .photo-upload-box {
            position: relative;
            min-height: 80px;
            transition: all 0.3s ease;
        }
        
        .photo-upload-box:hover {
            border-color: #3B82F6;
            background-color: #F8FAFC;
        }
        
        .photo-upload-box .absolute {
            position: absolute;
        }
        
        .aspect-square {
            aspect-ratio: 1 / 1;
        }
        
        /* Form Input Styling */
        .futuristic-input {
            border: 2px solid #E5E7EB !important;
            transition: all 0.3s ease;
        }
        
        .futuristic-input:focus {
            border-color: #3B82F6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .futuristic-input:hover {
            border-color: #9CA3AF;
        }
        
        /* Select Dropdown Styling */
        select.futuristic-input {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        /* Textarea Styling */
        textarea.futuristic-input {
            resize: vertical;
            min-height: 120px;
        }
        
        /* Checkbox Styling */
        input[type="checkbox"] {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #D1D5DB;
            border-radius: 0.25rem;
            transition: all 0.2s ease;
        }
        
        input[type="checkbox"]:checked {
            background-color: #3B82F6;
            border-color: #3B82F6;
        }
        
        input[type="checkbox"]:hover {
            border-color: #9CA3AF;
        }
        
        /* Variant Input Styling */
        .variant-item input {
            border: 2px solid #E5E7EB;
            transition: all 0.3s ease;
        }
        
        .variant-item input:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .variant-item input:hover {
            border-color: #9CA3AF;
        }
        
        /* Price After Discount Styling */
        #discountedPrice {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1F2937;
        }
        
        #discountInfo {
            transition: all 0.3s ease;
        }
        
        .bg-gray-100 {
            background-color: #F3F4F6;
            border-color: #D1D5DB;
        }
        
        @media (max-width: 768px) {
            .w-1\/2 {
                width: 100%;
            }
            .grid-cols-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .grid-cols-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        let variantCount = Number('{{ $product->variants->count() }}');

        // Trigger file input
        function triggerFileInput(inputId) {
            document.getElementById(inputId).click();
        }

        // Image preview
        function previewImage(input, previewId, imgId) {
            const preview = document.getElementById(previewId);
            const img = document.getElementById(imgId);
            const placeholder = document.getElementById(previewId.replace('Preview', 'Placeholder'));
            const current = document.getElementById(previewId.replace('Preview', 'Current'));
            
            if (input.files && input.files[0]) {
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
                if (current) current.classList.add('hidden');
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
                if (placeholder) placeholder.classList.remove('hidden');
                if (current) current.classList.remove('hidden');
            }
        }

        // Remove image
        function removeImage(inputId, elementId) {
            const input = document.getElementById(inputId);
            const element = document.getElementById(elementId);
            const preview = document.getElementById(elementId.replace('Current', 'Preview'));
            const placeholder = document.getElementById(elementId.replace('Current', 'Placeholder'));
            
            // Clear the file input
            if (input) input.value = '';
            
            // Hide current and show placeholder
            if (element) element.classList.add('hidden');
            if (preview) preview.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
        }

        // Toggle between standard and variant fields
        function toggleVariantFields() {
            const useVariants = document.getElementById('use_variants').checked;
            const standardFields = document.getElementById('standardFields');
            const variantFields = document.getElementById('variantFields');
            
            if (useVariants) {
                standardFields.style.display = 'none';
                variantFields.style.display = 'block';
                
                // Make standard fields optional
                document.getElementById('stock').removeAttribute('required');
                document.getElementById('price').removeAttribute('required');
            } else {
                standardFields.style.display = 'block';
                variantFields.style.display = 'none';
                
                // Make standard fields required
                document.getElementById('stock').setAttribute('required', 'required');
                document.getElementById('price').setAttribute('required', 'required');
            }
        }

        // Add new variant
        function addVariant() {
            variantCount++;
            const container = document.getElementById('variantsContainer');
            
            const variantHtml = `
                <div class="variant-item border border-gray-200 rounded-lg p-4 mb-4 bg-white" data-variant="${variantCount}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-semibold text-gray-700">Variant ${variantCount}</h4>
                        <button type="button" 
                                class="text-red-600 hover:text-red-800 transition" 
                                onclick="removeVariant(${variantCount})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Variant Name</label>
                            <input type="text" 
                                   name="variants[${variantCount}][name]" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., Small, Red, etc."
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                            <input type="number" 
                                   name="variants[${variantCount}][quantity]" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter stock"
                                   min="0"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                            <input type="number" 
                                   name="variants[${variantCount}][price]" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter price"
                                   step="0.01"
                                   min="0"
                                   required>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', variantHtml);
        }

        // Remove variant
        function removeVariant(variantNumber) {
            const variantItem = document.querySelector(`[data-variant="${variantNumber}"]`);
            if (variantItem) {
                variantItem.remove();
            }
        }

        // Calculate discounted price
        function calculateDiscountedPrice() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const discountPercentage = parseFloat(document.getElementById('discount_percentage').value) || 0;
            
            const discountedPrice = price - (price * discountPercentage / 100);
            const discountAmount = price * discountPercentage / 100;
            
            const discountedPriceElement = document.getElementById('discountedPrice');
            const discountInfoElement = document.getElementById('discountInfo');
            
            if (price > 0) {
                discountedPriceElement.textContent = 'Rp ' + discountedPrice.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                
                if (discountPercentage > 0) {
                    discountInfoElement.textContent = `Save Rp ${discountAmount.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})} (${discountPercentage}%)`;
                    discountInfoElement.className = 'text-sm text-green-600 font-medium';
                } else {
                    discountInfoElement.textContent = 'No discount applied';
                    discountInfoElement.className = 'text-sm text-gray-500';
                }
            } else {
                discountedPriceElement.textContent = 'Rp 0';
                discountInfoElement.textContent = 'Enter regular price first';
                discountInfoElement.className = 'text-sm text-gray-500';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add variant button event listener
            document.getElementById('addVariantBtn').addEventListener('click', addVariant);
            
            // Initialize variant toggle
            toggleVariantFields();
            
            // Initialize discounted price calculation
            calculateDiscountedPrice();
        });
    </script>
@endsection