@extends('admin.layouts.admin')

@section('title', 'Articles Management')

@section('content')
    <div class="w-full">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Articles Management</h2>
            <a href="{{ route('admin.articles.create') }}" class="admin-btn text-white px-6 py-3 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Create Article
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="admin-card p-6 mb-6 border-2 border-green-200">
            <div class="flex items-center mb-4">
                <i class="fas fa-search text-green-500 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Search & Filters</h3>
            </div>
            <form method="GET" action="{{ route('admin.articles.index') }}" class="space-y-6">
                <!-- Search Bar -->
                <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-search mr-2 text-green-500"></i>Search Articles
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by title or content..."
                                       class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="admin-btn text-white px-6 py-3 rounded-lg border-2 border-green-600">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                            <a href="{{ route('admin.articles.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-filter mr-2 text-green-500"></i>Filter Options
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="">All Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select id="sort" name="sort" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title A-Z</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title Z-A</option>
                                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Views</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Articles Table -->
        <div class="admin-card overflow-hidden border-2 border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Article</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Excerpt</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Views</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Published</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y-2 divide-gray-200">
                        @forelse($articles as $article)
                        <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-20 w-20">
                                        @if($article->featured_image)
                                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                                 alt="{{ $article->title }}" 
                                                 class="h-20 w-20 object-cover rounded-lg border-2 border-gray-200">
                                        @else
                                            <div class="h-20 w-20 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center border-2 border-green-300">
                                                <i class="fas fa-newspaper text-2xl text-green-600"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $article->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $article->slug }}</div>
                                        <div class="text-xs text-gray-400">{{ $article->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 border-r border-gray-200">
                                <div class="text-sm text-gray-900 max-w-xs">
                                    <div class="line-clamp-3">
                                        {{ $article->excerpt ?: Str::limit(strip_tags($article->content), 120) }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900">
                                    <div class="font-semibold text-lg">{{ $article->views }}</div>
                                    <div class="text-xs text-gray-500">views</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $article->is_published ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200' }}">
                                    {{ $article->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900">
                                    @if($article->published_at)
                                        <div class="font-semibold">{{ $article->published_at ? $article->published_at->format('M d, Y') : 'Not published' }}</div>
                                        <div class="text-xs text-gray-500">{{ $article->published_at ? $article->published_at->format('H:i') : '' }}</div>
                                    @else
                                        <div class="text-gray-400">Not published</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.articles.show', $article) }}" 
                                       class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition border border-gray-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.articles.edit', $article) }}" 
                                       class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition border border-blue-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this article?')">
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-newspaper text-4xl mb-4"></i>
                                <p class="text-lg">No articles found</p>
                                <p class="text-sm">Create your first article to get started</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($articles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $articles->links() }}
            </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

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
            $('a[href="{{ route('admin.articles.index') }}"]').on('click', function() {
                $('#search').val('');
            });
        });
    </script>
@endsection
