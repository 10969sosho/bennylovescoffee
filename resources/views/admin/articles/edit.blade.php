@extends('admin.layouts.admin')

@section('title', 'Edit Article')
@section('page-title', 'Edit Article')

@section('content')
    <div class="w-full">
        <div class="admin-card p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Edit Article: {{ $article->title }}</h2>

            <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="articleForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-3">
                                Article Title
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $article->title) }}"
                                   class="w-full px-4 py-4 futuristic-input @error('title') border-red-500 @enderror"
                                   placeholder="Enter article title"
                                   required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-3">
                                Article Excerpt (Max 1000 characters)
                            </label>
                            <textarea id="excerpt" 
                                      name="excerpt" 
                                      rows="4"
                                      maxlength="1000"
                                      class="w-full px-4 py-4 futuristic-input @error('excerpt') border-red-500 @enderror"
                                      placeholder="Enter article excerpt (optional)"
                                      oninput="updateCharCount()">{{ old('excerpt', $article->excerpt) }}</textarea>
                            <div class="flex justify-between items-center mt-2">
                                <div id="charCount" class="text-sm text-gray-500">0 / 1000 characters</div>
                                @error('excerpt')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-3">
                                Featured Image
                            </label>
                            
                            @if($article->featured_image)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                         alt="Current featured image" 
                                         class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                                    <p class="text-sm text-gray-500 mt-2">Current featured image</p>
                                </div>
                            @endif
                            
                            <div class="photo-upload-area p-6 text-center">
                                <input type="file"
                                       id="featured_image"
                                       name="featured_image"
                                       accept="image/*"
                                       class="hidden"
                                       onchange="previewImage(this)">
                                <label for="featured_image" class="cursor-pointer">
                                    <i class="fas fa-image text-4xl text-green-500 mb-4"></i>
                                    <p class="text-gray-600">Click to upload new featured image</p>
                                    <p class="text-sm text-gray-500">Recommended: 1200x630px</p>
                                </label>
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <img id="previewImg" src="" alt="Featured image preview" class="w-32 h-32 object-cover photo-preview mx-auto">
                            </div>
                            @error('featured_image')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_published" 
                                       value="1"
                                       {{ old('is_published', $article->is_published) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <span class="ml-3 text-sm text-gray-600">Publish article immediately</span>
                            </label>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Content Editor -->
                        <div class="border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-edit mr-2 text-green-500"></i>Article Content
                            </h3>
                            
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-3">
                                    Content
                                </label>
                                <textarea id="content" 
                                          name="content" 
                                          rows="15"
                                          class="w-full px-4 py-4 futuristic-input @error('content') border-red-500 @enderror"
                                          placeholder="Write your article content here..."
                                          required>{{ old('content', $article->content) }}</textarea>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Publishing Options -->
                        <div class="border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>Publishing Options
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                                        Schedule Publishing
                                    </label>
                                    <input type="datetime-local" 
                                           id="published_at" 
                                           name="published_at" 
                                           value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                                           class="w-full px-4 py-4 futuristic-input @error('published_at') border-red-500 @enderror">
                                    @error('published_at')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="text-sm text-gray-600">
                                    <p><strong>Current Status:</strong> 
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $article->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $article->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </p>
                                    @if($article->published_at)
                                        <p><strong>Published:</strong> {{ $article->published_at ? $article->published_at->format('M d, Y H:i') : 'Not published' }}</p>
                                    @endif
                                    <p><strong>Views:</strong> {{ $article->views }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Article Preview - Full Width -->
                <div id="articlePreview" class="mt-8 border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-eye mr-2 text-purple-500"></i>Article Preview
                    </h3>
                    <div id="previewContent">
                        <!-- Article preview will be loaded here -->
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('admin.articles.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="admin-btn text-white px-6 py-3 rounded-lg">
                        <i class="fas fa-save mr-2"></i>Update Article
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Character count for excerpt
        function updateCharCount() {
            const excerpt = document.getElementById('excerpt');
            const charCount = document.getElementById('charCount');
            const currentLength = excerpt.value.length;
            charCount.textContent = `${currentLength} / 1000 characters`;
            
            if (currentLength > 900) {
                charCount.classList.add('text-red-500');
                charCount.classList.remove('text-gray-500');
            } else {
                charCount.classList.remove('text-red-500');
                charCount.classList.add('text-gray-500');
            }
        }

        // Article preview
        function updatePreview() {
            const title = document.getElementById('title').value;
            const excerpt = document.getElementById('excerpt').value;
            const content = document.getElementById('content').value;
            
            document.getElementById('previewContent').innerHTML = `
                <div class="space-y-4">
                    ${title ? `<h4 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-2">${title}</h4>` : ''}
                    ${excerpt ? `<div class="bg-blue-50 border-l-4 border-blue-400 p-4"><p class="text-sm text-blue-700 font-medium">Excerpt:</p><p class="text-sm text-blue-600">${excerpt}</p></div>` : ''}
                    ${content ? `<div class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">${content}</div>` : ''}
                </div>
            `;
        }

        // Add event listeners
        document.getElementById('title').addEventListener('input', updatePreview);
        document.getElementById('excerpt').addEventListener('input', updatePreview);
        document.getElementById('content').addEventListener('input', updatePreview);

        // Initialize preview on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });
    </script>
@endsection
