@extends('admin.layouts.admin')

@section('title', 'Flash Sale Details')

@section('content')
    <div class="w-full">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $flashSale->title }}</h2>
                <p class="text-gray-600 mt-2">{{ $flashSale->description }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" 
                   class="admin-btn text-white px-6 py-3 rounded-lg">
                    <i class="fas fa-edit mr-2"></i>Edit Flash Sale
                </a>
                <a href="{{ route('admin.flash-sales.index') }}" 
                   class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>

        <!-- Flash Sale Info -->
        <div class="admin-card p-6 mb-6 border-2 border-orange-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600 mb-2">
                        <i class="fas fa-clock mr-2"></i>Time Period
                    </div>
                    <div class="text-sm text-gray-600">
                        <div class="font-semibold">{{ $flashSale->start_time->format('M d, Y H:i') }}</div>
                        <div class="text-gray-500">to {{ $flashSale->end_time->format('M d, Y H:i') }}</div>
                        @if($flashSale->isActive())
                            <div class="text-green-600 font-semibold mt-2">
                                {{ $flashSale->end_time->diffForHumans() }} left
                            </div>
                        @elseif($flashSale->isUpcoming())
                            <div class="text-blue-600 font-semibold mt-2">
                                Starts {{ $flashSale->start_time->diffForHumans() }}
                            </div>
                        @else
                            <div class="text-red-600 font-semibold mt-2">
                                Ended {{ $flashSale->end_time->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600 mb-2">
                        <i class="fas fa-box mr-2"></i>Products
                    </div>
                    <div class="text-3xl font-bold text-gray-800">{{ $flashSale->getTotalItems() }}</div>
                    <div class="text-sm text-gray-600">Total Products</div>
                </div>

                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600 mb-2">
                        <i class="fas fa-chart-line mr-2"></i>Performance
                    </div>
                    <div class="text-3xl font-bold text-gray-800">{{ $flashSale->getTotalSold() }}</div>
                    <div class="text-sm text-gray-600">Items Sold</div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="admin-card overflow-hidden border-2 border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-list mr-2 text-orange-500"></i>Products in Flash Sale
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Product</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Original Price</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Flash Price</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Discount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Stock</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y-2 divide-gray-200">
                        @forelse($flashSale->items as $item)
                        <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        @if($item->product->main_photo)
                                            <img src="{{ asset('storage/' . $item->product->main_photo) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="h-16 w-16 object-cover rounded-lg border-2 border-gray-200">
                                        @else
                                            <div class="h-16 w-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-gray-300">
                                                <i class="fas fa-image text-xl text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->product->category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900">
                                    <div class="font-semibold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900">
                                    <div class="text-lg font-bold text-red-600">Rp {{ number_format($item->flash_price, 0, ',', '.') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900">
                                    <div class="text-lg font-bold text-green-600">-{{ $item->getDiscountPercentage() }}%</div>
                                    <div class="text-xs text-gray-500">Save Rp {{ number_format($item->product->price - $item->flash_price, 0, ',', '.') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900">
                                    <div class="font-semibold">{{ $item->getRemainingStock() }} / {{ $item->flash_stock }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->sold_count }} sold</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                @php
                                    $status = $item->getStatus();
                                    $statusClasses = [
                                        'available' => 'bg-green-100 text-green-800 border-green-200',
                                        'sold_out' => 'bg-red-100 text-red-800 border-red-200',
                                        'inactive' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        'expired' => 'bg-orange-100 text-orange-800 border-orange-200'
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $statusClasses[$status] }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-box text-4xl mb-4"></i>
                                <p class="text-lg">No products in this flash sale</p>
                                <p class="text-sm">Add products to get started</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
