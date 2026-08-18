@extends('admin.layouts.admin')

@section('title', 'Add Featured Products')

@section('content')
<div class="featured-products-container p-6">
    <div class="featured-product-card">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-plus text-blue-500 mr-2"></i>
                Add Products to Featured
            </h3>
            <a href="{{ route('admin.featured-products.index') }}" class="featured-action-btn">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Featured Products
            </a>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.featured-products.store') }}" id="featuredForm">
                @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <!-- Product Selection -->
                                <div class="featured-form-group">
                                    <label for="product_search">Search Products</label>
                                    <div class="relative">
                                        <input type="text" 
                                               class="featured-search-input" 
                                               id="product_search" 
                                               placeholder="Type product name to search..."
                                               autocomplete="off">
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                    </div>
                                    <small class="text-gray-500 text-sm mt-1">
                                        Start typing to search for products. Products already featured will not appear.
                                    </small>
                                </div>

                                <!-- Available Products List -->
                                <div class="featured-form-group">
                                    <label>Available Products</label>
                                    <div id="available_products" class="border border-gray-200 rounded-lg p-4 bg-gray-50" style="max-height: 400px; overflow-y: auto;">
                                        @if($availableProducts->count() > 0)
                                            @foreach($availableProducts as $product)
                                                <div class="product-item border-b border-gray-200 py-3 flex items-center hover:bg-blue-50 cursor-pointer transition-colors" 
                                                     data-product-id="{{ $product->id }}" 
                                                     data-product-name="{{ $product->name }}"
                                                     data-product-category="{{ $product->category ? $product->category->name : '' }}"
                                                     data-product-price="{{ $product->price }}"
                                                     data-product-image="{{ $product->main_photo ? asset('storage/' . $product->main_photo) : '' }}"
                                                     onclick="selectProduct(this)">
                                                    <div class="flex-1">
                                                        <div class="flex items-center">
                                                            @if($product->main_photo)
                                                                <img src="{{ asset('storage/' . $product->main_photo) }}" 
                                                                     alt="{{ $product->name }}" 
                                                                     class="featured-product-image mr-3" 
                                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                            @else
                                                                <div class="bg-gray-200 flex items-center justify-center mr-3" 
                                                                     style="width: 50px; height: 50px; border-radius: 8px;">
                                                                    <i class="fas fa-image text-gray-400"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <strong class="text-gray-800">{{ $product->name }}</strong>
                                                                @if($product->category)
                                                                    <br><small class="text-gray-500">{{ $product->category->name }}</small>
                                                                @endif
                                                                <br><small class="text-green-600 font-semibold">
                                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                                    @if($product->discount_price)
                                                                        <span class="text-red-500">
                                                                            → Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                                                        </span>
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <i class="fas fa-plus text-blue-500"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-info-circle text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted mt-2">No available products to feature.</p>
                                                <small class="text-muted">All active products are already featured.</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div>
                                <!-- Selected Products -->
                                <div class="featured-product-card">
                                    <div class="p-4 border-b border-gray-200">
                                        <h5 class="text-lg font-semibold text-gray-800">
                                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                                            Selected Products
                                        </h5>
                                    </div>
                                    <div class="p-4">
                                        <div id="selected_products" class="border border-gray-200 rounded-lg p-3 bg-gray-50" style="max-height: 300px; overflow-y: auto;">
                                            <p class="text-gray-500 text-center">No products selected</p>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <button type="submit" class="featured-action-btn success w-full" id="submitBtn" disabled>
                                                <i class="fas fa-star mr-2"></i>
                                                Add to Featured
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedProductsDiv = document.getElementById('selected_products');
    const submitBtn = document.getElementById('submitBtn');
    const productSearch = document.getElementById('product_search');
    let selectedProducts = [];

    // Select product function
    window.selectProduct = function(productElement) {
        const productId = productElement.dataset.productId;
        const productName = productElement.dataset.productName;
        const productCategory = productElement.dataset.productCategory;
        const productPrice = productElement.dataset.productPrice;
        const productImage = productElement.dataset.productImage;

        // Check if product already selected
        if (selectedProducts.find(p => p.id === productId)) {
            return;
        }

        // Add to selected products
        selectedProducts.push({
            id: productId,
            name: productName,
            category: productCategory,
            price: productPrice,
            image: productImage
        });

        // Update display
        updateSelectedProducts();
        
        // Hide the product from available list
        productElement.style.display = 'none';
    };

    // Update selected products display
    function updateSelectedProducts() {
        if (selectedProducts.length === 0) {
            selectedProductsDiv.innerHTML = '<p class="text-gray-500 text-center">No products selected</p>';
            submitBtn.disabled = true;
        } else {
            selectedProductsDiv.innerHTML = selectedProducts.map(product => `
                <div class="selected-product border-b border-gray-200 py-3 flex items-center">
                    <div class="flex-1">
                        <div class="flex items-center">
                            ${product.image ? 
                                `<img src="${product.image}" alt="${product.name}" class="featured-product-image mr-3" style="width: 40px; height: 40px; object-fit: cover;">` :
                                `<div class="bg-gray-200 flex items-center justify-center mr-3" style="width: 40px; height: 40px; border-radius: 6px;">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>`
                            }
                            <div>
                                <strong class="text-gray-800">${product.name}</strong>
                                ${product.category ? `<br><small class="text-gray-500">${product.category}</small>` : ''}
                                <br><small class="text-green-600 font-semibold">Rp ${parseInt(product.price).toLocaleString('id-ID')}</small>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="text-red-500 hover:text-red-700 ml-3" onclick="removeProduct('${product.id}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
            submitBtn.disabled = false;
        }
    }

    // Remove product function
    window.removeProduct = function(productId) {
        selectedProducts = selectedProducts.filter(p => p.id !== productId);
        updateSelectedProducts();
        
        // Show the product back in available list
        const productElement = document.querySelector(`[data-product-id="${productId}"]`);
        if (productElement) {
            productElement.style.display = 'flex';
        }
    };

    // Product search functionality
    productSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const productItems = document.querySelectorAll('.product-item');
        
        productItems.forEach(item => {
            const productName = item.dataset.productName.toLowerCase();
            if (productName.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Form submission
    document.getElementById('featuredForm').addEventListener('submit', function(e) {
        if (selectedProducts.length === 0) {
            e.preventDefault();
            alert('Please select at least one product to feature.');
            return false;
        }

        // Add hidden inputs for selected products
        selectedProducts.forEach(product => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'product_ids[]';
            hiddenInput.value = product.id;
            this.appendChild(hiddenInput);
        });
    });

    // Initialize
    updateSelectedProducts();
});
</script>
@endsection
