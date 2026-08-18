@extends('admin.layouts.admin')

@section('title', 'Banner Section 2 Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Banner Section 2 Management</h2>
        <a href="{{ route('admin.banner-section2.create') }}" class="admin-btn text-white px-6 py-3 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Add New Banner Section 2
        </a>
    </div>

    <!-- Banners Grid with Drag & Drop -->
    <div class="admin-card">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-layer-group mr-2"></i>Banner Section 2 - Drag to Reorder
            </h3>
            <p class="text-sm text-gray-600 mb-6">Banners displayed in the middle section of the homepage. Drag and drop to change their order.</p>
            
            @if($banners->count() > 0)
                <div id="bannersContainer" class="space-y-6">
                    @foreach($banners as $banner)
                    <div class="banner-card border-2 border-gray-200 rounded-lg overflow-hidden bg-white cursor-move flex items-center" 
                         data-banner-id="{{ $banner->id }}" 
                         data-position="{{ $banner->position }}"
                         draggable="true">
                        
                        <!-- Drag Handle -->
                        <div class="flex-shrink-0 p-4 text-gray-400">
                            <i class="fas fa-grip-vertical text-2xl"></i>
                        </div>
                        
                        <!-- Position Badge -->
                        <div class="flex-shrink-0 px-4">
                            <div class="bg-purple-600 bg-opacity-75 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $banner->position }}
                            </div>
                        </div>
                        
                        <!-- Banner Image -->
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $banner->image) }}" 
                                 alt="Banner {{ $banner->position }}" 
                                 class="w-32 h-20 object-cover rounded">
                        </div>
                        
                        <!-- Banner Info -->
                        <div class="flex-grow px-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Section 2 Banner #{{ $banner->position }}</div>
                                    <div class="text-xs text-gray-500">Uploaded: {{ $banner->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex-shrink-0 p-4">
                            <div class="flex space-x-2">
                                <button onclick="toggleStatus({{ $banner->id }})" 
                                        class="px-3 py-2 {{ $banner->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition">
                                    <i class="fas fa-{{ $banner->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                                <button onclick="deleteBanner({{ $banner->id }})" 
                                        class="px-3 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-image text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">No Banner Section 2 Found</h3>
                    <p class="text-gray-500 mb-4">Start by creating your first banner for section 2</p>
                    <a href="{{ route('admin.banner-section2.create') }}" class="admin-btn text-white px-6 py-3 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Create First Banner Section 2
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.banner-card {
    transition: all 0.3s ease;
}

.banner-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.banner-card.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
}

.banner-card.drag-over {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.drag-handle {
    cursor: grab;
}

.drag-handle:active {
    cursor: grabbing;
}
</style>

<script>
// Simple direct implementation - no external dependencies
function deleteBanner(bannerId) {
    if (confirm('Are you sure you want to delete this banner?')) {
        console.log('Deleting banner with ID:', bannerId);
        fetch(`/admin/banner-section2/${bannerId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (response.ok) {
                // Try to parse JSON response
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(data => {
                        console.log('JSON response:', data);
                        if (data.success) {
                            console.log('Banner deleted successfully');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
                } else {
                    // HTML redirect response
                    console.log('HTML redirect response, reloading page');
                    location.reload();
                }
            } else {
                console.error('Server error:', response.status, response.statusText);
                alert('Error deleting banner: ' + response.status + ' ' + response.statusText);
            }
        })
        .catch(error => {
            console.error('Network error:', error);
            alert('Error deleting banner: ' + error.message);
        });
    }
}

function toggleStatus(bannerId) {
    console.log('Toggling status for banner ID:', bannerId);
    fetch(`/admin/banner-section2/${bannerId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Toggle response status:', response.status);
        if (response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => {
                    console.log('Toggle JSON response:', data);
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            } else {
                location.reload();
            }
        } else {
            alert('Error updating status: ' + response.status);
        }
    })
    .catch(error => {
        console.error('Toggle error:', error);
        alert('Error updating status: ' + error.message);
    });
}

function updateBannerPosition(bannerId, newPosition) {
    fetch('{{ route("admin.banner-section2.update-position") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            banner_id: bannerId,
            new_position: newPosition
        })
    })
    .then(response => {
        if (response.ok) {
            // Try to parse JSON response
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            } else {
                location.reload();
            }
        } else {
            alert('Error updating order: ' + response.status);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating order: ' + error.message);
    });
}

// Drag and Drop Functionality
let draggedElement = null;

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('bannersContainer');
    
    if (container) {
        // Add event listeners to all banner cards
        const bannerCards = container.querySelectorAll('.banner-card');
        bannerCards.forEach(card => {
            card.addEventListener('dragstart', handleDragStart);
            card.addEventListener('dragend', handleDragEnd);
            card.addEventListener('dragover', handleDragOver);
            card.addEventListener('dragleave', handleDragLeave);
            card.addEventListener('drop', handleDrop);
        });
    }
});

function handleDragStart(e) {
    draggedElement = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);
}

function handleDragEnd(e) {
    this.classList.remove('dragging');
    // Remove drag-over class from all elements
    document.querySelectorAll('.banner-card').forEach(card => {
        card.classList.remove('drag-over');
    });
    draggedElement = null;
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    this.classList.add('drag-over');
    return false;
}

function handleDragLeave(e) {
    this.classList.remove('drag-over');
}

function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    
    this.classList.remove('drag-over');
    
    if (draggedElement !== this) {
        const draggedId = parseInt(draggedElement.dataset.bannerId);
        const targetId = parseInt(this.dataset.bannerId);
        const draggedPosition = parseInt(draggedElement.dataset.position);
        const targetPosition = parseInt(this.dataset.position);
        
        // Update positions
        updateBannerPosition(draggedId, targetPosition);
    }
    
    return false;
}
</script>
@endsection
