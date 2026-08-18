@extends('admin.layouts.admin')

@section('title', 'View Article')
@section('page-title', 'View Article')

@section('content')
    <div class="w-full">
        <div class="admin-card p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Article Details</h2>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.articles.edit', $article) }}" 
                       class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition border border-blue-200">
                        <i class="fas fa-edit mr-2"></i>Edit Article
                    </a>
                    <a href="{{ route('admin.articles.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Articles
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Article Header -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
                        <div class="flex items-start space-x-4">
                            @if($article->featured_image)
                                <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                     alt="{{ $article->title }}" 
                                     class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200">
                            @else
                                <div class="w-24 h-24 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center border-2 border-green-300">
                                    <i class="fas fa-newspaper text-2xl text-green-600"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $article->title }}</h1>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $article->is_published ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200' }}">
                                        {{ $article->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                    <span><i class="fas fa-eye mr-1"></i>{{ $article->views }} views</span>
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $article->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Article Content -->
                    <div class="border-2 border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-file-text mr-2 text-green-500"></i>Article Content
                        </h3>
                        <div class="prose max-w-none">
                            @if($article->excerpt)
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Excerpt:</h4>
                                    <p class="text-blue-700">{{ $article->excerpt }}</p>
                                </div>
                            @endif
                            <div class="whitespace-pre-wrap text-gray-700 leading-relaxed">
                                {{ $article->content }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Article Info -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>Article Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Slug:</label>
                                <p class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $article->slug }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Status:</label>
                                <p class="text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $article->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $article->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Views:</label>
                                <p class="text-sm text-gray-900 font-semibold">{{ $article->views }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Created:</label>
                                <p class="text-sm text-gray-900">{{ $article->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Updated:</label>
                                <p class="text-sm text-gray-900">{{ $article->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                            @if($article->published_at)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Published:</label>
                                    <p class="text-sm text-gray-900">{{ $article->published_at ? $article->published_at->format('M d, Y H:i') : 'Not published' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Publishing Status -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-clock mr-2 text-purple-500"></i>Publishing Status
                        </h3>
                        <div class="space-y-3">
                            @if($article->is_published)
                                <div class="flex items-center text-green-600">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span class="text-sm font-medium">Article is live</span>
                                </div>
                                @if($article->published_at)
                                    <p class="text-xs text-gray-600">
                                        Published {{ $article->published_at->diffForHumans() }}
                                    </p>
                                @endif
                            @else
                                <div class="flex items-center text-yellow-600">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span class="text-sm font-medium">Article is draft</span>
                                </div>
                                @if($article->published_at)
                                    <p class="text-xs text-gray-600">
                                        Scheduled for {{ $article->published_at ? $article->published_at->format('M d, Y H:i') : 'Not scheduled' }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-600">
                                        No publish date set
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-cogs mr-2 text-gray-500"></i>Actions
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.articles.edit', $article) }}" 
                               class="w-full flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition border border-blue-200">
                                <i class="fas fa-edit mr-2"></i>Edit Article
                            </a>
                            
                            <form action="{{ route('admin.articles.destroy', $article) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this article?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition border border-red-200">
                                    <i class="fas fa-trash mr-2"></i>Delete Article
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
