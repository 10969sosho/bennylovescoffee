@extends('admin.layouts.admin')

@section('title', 'Brands Management')

@section('content')
    <div class="w-full">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-3 sm:mb-4">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Brands Management</h2>
            <a href="{{ route('admin.brands.create') }}" class="admin-btn text-white px-6 py-3 rounded-lg w-full sm:w-auto text-center">
                <i class="fas fa-plus mr-2"></i>Add New Brand
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="admin-card p-3 sm:p-4 md:p-5 mb-4 border-2 border-blue-200">
            <div class="flex items-center mb-2 sm:mb-2.5">
                <i class="fas fa-search text-blue-500 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Search & Filters</h3>
            </div>
            <form method="GET" action="{{ route('admin.brands.index') }}" class="space-y-4">
                <!-- Search Bar -->
                <div class="border-2 border-gray-200 rounded-lg p-2.5 sm:p-3.5 bg-gray-50">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2 sm:mb-2.5">
                        <i class="fas fa-search mr-2 text-blue-500"></i>Search Brands
                    </label>
                    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                        <div class="w-full flex-1">
                            <div class="relative">
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       dir="ltr"
                                       autocomplete="off"
                                       spellcheck="false"
                                       value="{{ request('search') }}"
                                       placeholder="Search by brand name or description..."
                                       class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="w-full sm:w-auto grid grid-cols-2 sm:flex gap-2">
                            <button type="submit" class="admin-btn text-white py-2.5 px-5 rounded-lg border-2 border-blue-600 flex justify-center items-center">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                            <a href="{{ route('admin.brands.index') }}" class="py-2.5 px-5 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex justify-center items-center">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="border-2 border-gray-200 rounded-lg p-2.5 sm:p-3.5 bg-gray-50">
                    <label class="block text-sm font-medium text-gray-700 mb-2 sm:mb-2.5">
                        <i class="fas fa-filter mr-2 text-blue-500"></i>Filter Options
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select id="sort" name="sort" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Search Results Info -->
        @if(request()->hasAny(['search', 'status', 'sort']))
        <div class="admin-card p-4 mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-filter text-blue-500"></i>
                    <span class="text-sm text-gray-600">
                        @if(request('search'))
                            Searching for: <strong>"{{ request('search') }}"</strong>
                        @endif
                        @if(request('status'))
                            | Status: <strong>{{ ucfirst(request('status')) }}</strong>
                        @endif
                        @if(request('sort'))
                            | Sort: <strong>{{ ucfirst(str_replace('_', ' ', request('sort'))) }}</strong>
                        @endif
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    Found {{ $brands->total() }} brand(s)
                </div>
            </div>
        </div>
        @endif

        <!-- Brands Table -->
        <div class="admin-card overflow-hidden border-2 border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Brand</th>
                            <th class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Description</th>
                            <th class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Products</th>
                            <th class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Status</th>
                            <th class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Created</th>
                            <th class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y-2 divide-gray-200">
                        @forelse($brands as $brand)
                        <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                            <td class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        @if($brand->image)
                                            <img src="{{ Storage::url($brand->image) }}" 
                                                 alt="{{ $brand->name }}" 
                                                 class="h-16 w-16 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                        @else
                                            <div class="h-16 w-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center border-2 border-blue-300">
                                                <i class="fas fa-award text-blue-600 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $brand->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $brand->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900 max-w-xs">
                                    {{ $brand->description ?: 'No description provided' }}
                                </div>
                            </td>
                            <td class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-900">{{ $brand->products_count ?? 0 }}</span>
                                    <span class="ml-2 text-xs text-gray-500">products</span>
                                </div>
                            </td>
                            <td class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $brand->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-2.5 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900">{{ $brand->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $brand->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-2.5 sm:px-4 py-2.5 sm:py-3 whitespace-nowrap text-sm font-medium">
                                <div class="whitespace-nowrap flex items-center space-x-2">
                                    <a href="{{ route('admin.brands.show', $brand) }}"
                                       class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition border border-gray-200"
                                       title="View Brand">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.brands.edit', $brand) }}" 
                                       class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition border border-blue-200"
                                       title="Edit Brand">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this brand?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition border border-red-200"
                                                title="Delete Brand">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 sm:px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-award text-4xl mb-4"></i>
                                <p class="text-lg">No brands found</p>
                                <p class="text-sm">Try adjusting your filters or create a new brand</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($brands->hasPages())
            <div class="px-2.5 sm:px-4 py-3 border-t border-gray-200">
                {{ $brands->links() }}
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
            $('a[href="{{ route('admin.brands.index') }}"]').on('click', function() {
                $('#search').val('');
            });
        });
    </script>
@endsection