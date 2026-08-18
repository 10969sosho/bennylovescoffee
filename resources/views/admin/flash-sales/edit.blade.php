@extends('admin.layouts.admin')

@section('title', 'Edit Flash Sale')

@section('content')
    <div class="w-full">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Edit Flash Sale</h2>
            <a href="{{ route('admin.flash-sales.index') }}" 
               class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.flash-sales.update', $flashSale) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Flash Sale Info -->
            <div class="admin-card p-6 border-2 border-orange-200">
                <div class="flex items-center mb-4">
                    <i class="fas fa-bolt text-orange-500 mr-2"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Flash Sale Information</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $flashSale->title) }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                               placeholder="Enter flash sale title"
                               required>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                  placeholder="Enter flash sale description">{{ old('description', $flashSale->description) }}</textarea>
                    </div>
                    
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                        <input type="datetime-local" 
                               id="start_time" 
                               name="start_time" 
                               value="{{ old('start_time', $flashSale->start_time ? $flashSale->start_time->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                               required>
                    </div>
                    
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                        <input type="datetime-local" 
                               id="end_time" 
                               name="end_time" 
                               value="{{ old('end_time', $flashSale->end_time ? $flashSale->end_time->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                               required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $flashSale->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        <span class="ml-2 text-sm text-gray-700">Activate flash sale immediately</span>
                    </label>
                </div>
            </div>

            <!-- Products Selection -->
            <div class="admin-card p-6 border-2 border-orange-200">
                <div class="flex items-center mb-4">
                    <i class="fas fa-box text-orange-500 mr-2"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Flash Sale Products</h3>
                </div>
                
                <div id="products-container">
                    @foreach($flashSale->items as $index => $item)
                    <div class="product-item border-2 border-gray-200 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                                <select name="products[{{ $index }}][product_id]" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition product-select"
                                        required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->price }}"
                                                data-name="{{ $product->name }}"
                                                data-image="{{ $product->main_photo ? asset('storage/' . $product->main_photo) : '' }}"
                                                {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Flash Price *</label>
                                <input type="number" 
                                       name="products[{{ $index }}][flash_price]" 
                                       step="0.01"
                                       min="0"
                                       value="{{ old('products.'.$index.'.flash_price', $item->flash_price) }}"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition flash-price"
                                       placeholder="0.00"
                                       required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                                <input type="number" 
                                       name="products[{{ $index }}][flash_stock]" 
                                       min="1"
                                       value="{{ old('products.'.$index.'.flash_stock', $item->flash_stock) }}"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                       placeholder="0"
                                       required>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="button" 
                                        class="w-full px-4 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition border border-red-200 remove-product">
                                    <i class="fas fa-trash mr-2"></i>Remove
                                </button>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg product-preview">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 product-image-container">
                                    @if($item->product->main_photo)
                                        <img src="{{ asset('storage/' . $item->product->main_photo) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="h-12 w-12 object-cover rounded">
                                    @else
                                        <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="font-medium text-gray-900 product-name">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        Original: <span class="original-price font-semibold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span> | 
                                        Flash: <span class="flash-price-display font-bold text-red-600">Rp {{ number_format($item->flash_price, 0, ',', '.') }}</span> | 
                                        Discount: <span class="discount-percentage font-bold text-green-600">
                                            -{{ round((($item->product->price - $item->flash_price) / $item->product->price) * 100) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    <button type="button" 
                            id="add-product" 
                            class="px-6 py-3 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition border border-orange-200">
                        <i class="fas fa-plus mr-2"></i>Add Another Product
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.flash-sales.index') }}" 
                   class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="admin-btn text-white px-8 py-3 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update Flash Sale
                </button>
            </div>
        </form>
    </div>

    <script>
        let productIndex = {{ $flashSale->items->count() }};

        // Add product functionality
        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const template = container.querySelector('.product-item').cloneNode(true);
            
            // Update indices
            template.querySelectorAll('select, input').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, '[' + productIndex + ']'));
                }
            });
            
            // Clear values
            template.querySelectorAll('input, select').forEach(input => {
                if (input.type !== 'button') {
                    input.value = '';
                }
            });
            
            // Hide preview
            template.querySelector('.product-preview').style.display = 'none';
            
            container.appendChild(template);
            productIndex++;
        });

        // Remove product functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                const productItem = e.target.closest('.product-item');
                if (document.querySelectorAll('.product-item').length > 1) {
                    productItem.remove();
                } else {
                    alert('At least one product is required');
                }
            }
        });

        // Product selection and price calculation
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                const productItem = e.target.closest('.product-item');
                const selectedOption = e.target.selectedOptions[0];
                const flashPriceInput = productItem.querySelector('.flash-price');
                const preview = productItem.querySelector('.product-preview');
                
                if (selectedOption.value) {
                    const originalPrice = parseFloat(selectedOption.dataset.price);
                    const productName = selectedOption.dataset.name;
                    const productImage = selectedOption.dataset.image;
                    
                    // Set default flash price (70% of original) if not already set
                    if (!flashPriceInput.value) {
                        flashPriceInput.value = (originalPrice * 0.7).toFixed(2);
                    }
                    
                    // Update preview
                    productItem.querySelector('.product-name').textContent = productName;
                    productItem.querySelector('.original-price').textContent = 'Rp ' + originalPrice.toLocaleString('id-ID');
                    
                    // Update image
                    const imageContainer = productItem.querySelector('.product-image-container');
                    if (productImage) {
                        imageContainer.innerHTML = `<img src="${productImage}" alt="${productName}" class="h-12 w-12 object-cover rounded">`;
                    } else {
                        imageContainer.innerHTML = '<div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center"><i class="fas fa-image text-gray-400"></i></div>';
                    }
                    
                    preview.style.display = 'block';
                    updatePreview(productItem);
                } else {
                    preview.style.display = 'none';
                }
            }
            
            if (e.target.classList.contains('flash-price')) {
                const productItem = e.target.closest('.product-item');
                updatePreview(productItem);
            }
        });

        function updatePreview(productItem) {
            const originalPrice = parseFloat(productItem.querySelector('.product-select').selectedOptions[0]?.dataset.price || 0);
            const flashPrice = parseFloat(productItem.querySelector('.flash-price').value || 0);
            
            if (originalPrice > 0 && flashPrice > 0) {
                const discount = Math.round(((originalPrice - flashPrice) / originalPrice) * 100);
                
                productItem.querySelector('.flash-price-display').textContent = 'Rp ' + flashPrice.toLocaleString('id-ID');
                productItem.querySelector('.discount-percentage').textContent = '-' + discount + '%';
            }
        }

        // Initialize previews for existing products
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.product-item').forEach(item => {
                const select = item.querySelector('.product-select');
                if (select.value) {
                    updatePreview(item);
                }
            });
        });
    </script>
@endsection
