@extends('admin.layouts.admin')

@section('title', 'Flash Sales Management')

@section('content')
    <div class="w-full">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Flash Sales Management</h2>
            <a href="{{ route('admin.flash-sales.create') }}" class="admin-btn text-white px-6 py-3 rounded-lg">
                <i class="fas fa-bolt mr-2"></i>Create Flash Sale
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="admin-card p-6 mb-6 border-2 border-orange-200">
            <div class="flex items-center mb-4">
                <i class="fas fa-search text-orange-500 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Search & Filters</h3>
            </div>
            <form method="GET" action="{{ route('admin.flash-sales.index') }}" class="space-y-4">
                <!-- Search Bar -->
                <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-search mr-2 text-orange-500"></i>Search Flash Sales
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       dir="ltr"
                                       autocomplete="off"
                                       spellcheck="false"
                                       value="{{ request('search') }}"
                                       placeholder="Search by product name..."
                                       class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="admin-btn text-white px-6 py-3 rounded-lg border-2 border-orange-600">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                            <a href="{{ route('admin.flash-sales.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-filter mr-2 text-orange-500"></i>Filter Options
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select id="sort" name="sort" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="start_time" {{ request('sort') == 'start_time' ? 'selected' : '' }}>Start Time</option>
                                <option value="end_time" {{ request('sort') == 'end_time' ? 'selected' : '' }}>End Time</option>
                                <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>Highest Discount</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Flash Sales Table -->
        <div class="admin-card overflow-hidden border-2 border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Flash Sale</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Products</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Time Period</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y-2 divide-gray-200">
                        @forelse($flashSales as $flashSale)
                        <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <div class="h-16 w-16 bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg flex items-center justify-center border-2 border-orange-300">
                                            <i class="fas fa-bolt text-2xl text-orange-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $flashSale->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $flashSale->description }}</div>
                                        <div class="text-xs text-gray-400">{{ $flashSale->getTotalItems() }} products</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="space-y-2">
                                    @foreach($flashSale->items->take(3) as $item)
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                @if($item->product->main_photo)
                                                    <img src="{{ asset('storage/' . $item->product->main_photo) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="h-8 w-8 object-cover rounded border">
                                                @else
                                                    <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center">
                                                        <i class="fas fa-image text-xs text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-2">
                                                <div class="font-medium text-gray-900">{{ $item->product->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $item->getRemainingStock() }} / {{ $item->flash_stock }} stock</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-red-600">Rp {{ number_format($item->flash_price, 0, ',', '.') }}</div>
                                            <div class="text-xs text-green-600">-{{ $item->getDiscountPercentage() }}%</div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($flashSale->items->count() > 3)
                                    <div class="text-xs text-gray-500 text-center">
                                        +{{ $flashSale->items->count() - 3 }} more products
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900">
                                    <div class="font-semibold">{{ $flashSale->start_time->format('M d, Y H:i') }}</div>
                                    <div class="text-xs text-gray-500">to {{ $flashSale->end_time->format('M d, Y H:i') }}</div>
                                    @if($flashSale->isActive())
                                        <div class="text-xs text-green-600 font-semibold">
                                            {{ $flashSale->end_time->diffForHumans() }} left
                                        </div>
                                    @elseif($flashSale->isUpcoming())
                                        <div class="text-xs text-blue-600 font-semibold">
                                            Starts {{ $flashSale->start_time->diffForHumans() }}
                                        </div>
                                    @else
                                        <div class="text-xs text-red-600 font-semibold">
                                            Ended {{ $flashSale->end_time->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                @php
                                    $status = $flashSale->getStatus();
                                    $statusClasses = [
                                        'active' => 'bg-green-100 text-green-800 border-green-200',
                                        'upcoming' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'expired' => 'bg-red-100 text-red-800 border-red-200'
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $statusClasses[$status] }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.flash-sales.show', $flashSale) }}" 
                                       class="px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition border border-green-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" 
                                       class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition border border-blue-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.flash-sales.destroy', $flashSale) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this flash sale?')">
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
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-bolt text-4xl mb-4"></i>
                                <p class="text-lg">No flash sales found</p>
                                <p class="text-sm">Create your first flash sale to get started</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($flashSales->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $flashSales->links() }}
            </div>
            @endif
        </div>
    </div>

    <script>
        // Initialize Select2 for filters
        $(document).ready(function() {
            $('#status, #sort').select2({
                placeholder: 'Select option...',
                allowClear: true,
                width: '100%'
            });

            // Auto-submit form when filters change
            $('#status, #sort').on('change', function() {
                $(this).closest('form').submit();
            });

            // Search with Enter key
            $('#search').on('keypress', function(e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });

            // Clear search when clear button is clicked
            $('a[href="{{ route('admin.flash-sales.index') }}"]').on('click', function() {
                $('#search').val('');
            });
        });
    </script>
@endsection
