@extends('admin.layouts.admin')

@section('title', 'Upload Banner')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Upload New Hero Banner</h1>
            <p class="text-gray-600 mt-2">Upload banner for the top section of the homepage</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="admin-btn">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Banners
        </a>
    </div>

    <!-- Simple Upload Form -->
    <div class="admin-card">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="max-w-2xl mx-auto">
                <!-- Image Upload -->
                <div class="text-center">
                    <div class="photo-upload-area rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 transition">
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/*"
                               class="hidden"
                               onchange="previewImage(this)"
                               required>
                        <label for="image" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 mb-6"></i>
                            <p class="text-2xl font-medium text-gray-600 mb-4">Click to upload banner image</p>
                            <p class="text-lg text-gray-500 mb-2">PNG, JPG, GIF up to 2MB</p>
                            <p class="text-sm text-gray-400">Recommended: 1920x1080px (16:9 ratio)</p>
                        </label>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Preview -->
                <div id="imagePreview" class="hidden mt-8">
                    <label class="block text-lg font-medium text-gray-700 mb-4 text-center">Preview</label>
                    <div class="relative max-w-4xl mx-auto">
                        <img id="previewImg" 
                             src="" 
                             alt="Preview" 
                             class="w-full h-64 object-cover rounded-lg border-2 border-gray-200">
                        <button type="button" 
                                onclick="removeImage()" 
                                class="absolute top-4 right-4 bg-red-500 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-red-600 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>


                <!-- Banner Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
                    <h4 class="font-semibold text-blue-800 mb-3 text-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Hero Banner Guidelines
                    </h4>
                    <ul class="text-sm text-blue-700 space-y-2 text-center">
                        <li>• Use high-quality images (1920x1080px recommended)</li>
                        <li>• 16:9 aspect ratio works best</li>
                        <li>• Keep text minimal and readable</li>
                        <li>• Consider mobile viewing experience</li>
                        <li>• File size should be under 2MB</li>
                        <li>• This banner will appear at the top of the homepage</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center space-x-4 mt-8">
                    <a href="{{ route('admin.banners.index') }}" 
                       class="px-8 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="admin-btn px-8 py-3">
                        <i class="fas fa-upload mr-2"></i>
                        Upload Hero Banner
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('previewImg').src = '';
}
</script>
@endsection
