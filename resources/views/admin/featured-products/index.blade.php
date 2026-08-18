@extends('admin.layouts.admin')

@section('title', 'Featured Products Management')

@section('content')
<div class="w-full">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Featured Products Management</h2>
        <a href="{{ route('admin.featured-products.create') }}" class="admin-btn text-white px-6 py-3 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Add Featured Products
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    @if($featuredProducts->count() > 0)
        <!-- Featured Products Table -->
        <div class="admin-card overflow-hidden border-2 border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Product</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Price</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Order</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Featured Date</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y-2 divide-gray-200" id="sortable">
                            @foreach($featuredProducts as $featuredProduct)
                            <tr data-id="{{ $featuredProduct->id }}" class="hover:bg-gray-50 transition border-b border-gray-200">
                                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-20 w-20">
                                            @if($featuredProduct->product->main_photo)
                                                <img src="{{ asset('storage/' . $featuredProduct->product->main_photo) }}" 
                                                     alt="{{ $featuredProduct->product->name }}" 
                                                     class="h-20 w-20 object-cover rounded-lg photo-preview border-2 border-gray-200">
                                            @else
                                                <div class="h-20 w-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-gray-300">
                                                    <i class="fas fa-image text-2xl text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $featuredProduct->product->name }}</div>
                                            @if($featuredProduct->title)
                                                <div class="text-sm text-gray-500">{{ $featuredProduct->title }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                    @if($featuredProduct->product->category)
                                        <span class="text-sm text-gray-900">{{ $featuredProduct->product->category->name }}</span>
                                    @else
                                        <span class="text-sm text-gray-500">No Category</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                    <div class="text-sm text-gray-900">
                                        @if($featuredProduct->product->discount_price)
                                            <div class="text-green-600 font-semibold">Rp {{ number_format($featuredProduct->product->discount_price, 0, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500 line-through">Rp {{ number_format($featuredProduct->product->price, 0, ',', '.') }}</div>
                                        @else
                                            <div class="text-gray-900">Rp {{ number_format($featuredProduct->product->price, 0, ',', '.') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $featuredProduct->sort_order }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $featuredProduct->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $featuredProduct->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                    <div class="text-sm text-gray-900">
                                        {{ $featuredProduct->featured_at ? $featuredProduct->featured_at->format('d M Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <form method="POST" action="{{ route('admin.featured-products.toggle-status', $featuredProduct) }}" 
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-2 {{ $featuredProduct->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition border {{ $featuredProduct->is_active ? 'border-yellow-200' : 'border-green-200' }}">
                                                <i class="fas fa-{{ $featuredProduct->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.featured-products.destroy', $featuredProduct) }}" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to remove this product from featured?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition border border-red-200">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Reorder Info -->
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                <div class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>Drag rows to reorder. Changes are saved automatically.</span>
                                </div>
                            </div>
                        </div>
    @else
        <!-- Empty State -->
        <div class="admin-card p-12 text-center">
            <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-semibold text-gray-600 mb-2">No Featured Products</h3>
            <p class="text-gray-500 mb-6">Start by adding some products to featured section.</p>
            <a href="{{ route('admin.featured-products.create') }}" class="admin-btn text-white px-6 py-3 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Add Featured Products
            </a>
        </div>
    @endif
</div>

<!-- Sortable Script -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable
    const sortable = Sortable.create(document.getElementById('sortable'), {
        animation: 150,
        onEnd: function(evt) {
            const order = [];
            document.querySelectorAll('#sortable tr').forEach((row, index) => {
                order.push(row.dataset.id);
            });
            
            // Update sort order via AJAX
            fetch('{{ route("admin.featured-products.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order: order })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update sort order display
                    document.querySelectorAll('#sortable tr').forEach((row, index) => {
                        const sortOrderBadge = row.querySelector('.badge.bg-secondary');
                        if (sortOrderBadge) {
                            sortOrderBadge.textContent = index + 1;
                        }
                    });
                } else {
                    console.error('Error updating order:', data.message);
                    alert('Error updating order: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error updating featured products order:', error);
                alert('Error updating order: ' + error.message);
            });
        }
    });
});
</script>
@endsection
