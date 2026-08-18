<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FlashSale::with(['items.product']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'active':
                    $query->active();
                    break;
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'expired':
                    $query->expired();
                    break;
            }
        }

        $flashSales = $query->ordered()->paginate(10);
        return view('admin.flash-sales.index', compact('flashSales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.flash-sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'boolean',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.flash_price' => 'required|numeric|min:0',
            'products.*.flash_stock' => 'required|integer|min:1',
        ]);

        // Check for overlapping flash sales
        $overlappingFlashSale = FlashSale::where('is_active', true)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->first();

        if ($overlappingFlashSale) {
            return redirect()->back()
                ->withErrors([
                    'start_time' => "Flash sale time overlaps with existing flash sale: '{$overlappingFlashSale->title}' ({$overlappingFlashSale->start_time->format('M d, Y H:i')} - {$overlappingFlashSale->end_time->format('M d, Y H:i')})"
                ])
                ->withInput();
        }

        // Create flash sale
        $flashSale = FlashSale::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => $request->has('is_active'),
            'sort_order' => FlashSale::max('sort_order') + 1,
        ]);

        // Create flash sale items
        foreach ($request->products as $index => $productData) {
            FlashSaleItem::create([
                'flash_sale_id' => $flashSale->id,
                'product_id' => $productData['product_id'],
                'flash_price' => $productData['flash_price'],
                'flash_stock' => $productData['flash_stock'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FlashSale $flashSale)
    {
        $flashSale->load(['items.product']);
        return view('admin.flash-sales.show', compact('flashSale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FlashSale $flashSale)
    {
        $flashSale->load(['items.product']);
        $products = Product::where('is_active', true)->get();
        return view('admin.flash-sales.edit', compact('flashSale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'boolean',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.flash_price' => 'required|numeric|min:0',
            'products.*.flash_stock' => 'required|integer|min:1',
        ]);

        // Check for overlapping flash sales (excluding current one)
        $overlappingFlashSale = FlashSale::where('is_active', true)
            ->where('id', '!=', $flashSale->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->first();

        if ($overlappingFlashSale) {
            return redirect()->back()
                ->withErrors([
                    'start_time' => "Flash sale time overlaps with existing flash sale: '{$overlappingFlashSale->title}' ({$overlappingFlashSale->start_time->format('M d, Y H:i')} - {$overlappingFlashSale->end_time->format('M d, Y H:i')})"
                ])
                ->withInput();
        }

        // Update flash sale
        $flashSale->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => $request->has('is_active'),
        ]);

        // Delete existing items
        $flashSale->items()->delete();

        // Create new flash sale items
        foreach ($request->products as $index => $productData) {
            FlashSaleItem::create([
                'flash_sale_id' => $flashSale->id,
                'product_id' => $productData['product_id'],
                'flash_price' => $productData['flash_price'],
                'flash_stock' => $productData['flash_stock'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();
        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale deleted successfully!');
    }

    /**
     * Toggle flash sale status
     */
    public function toggleStatus(FlashSale $flashSale)
    {
        $flashSale->update(['is_active' => !$flashSale->is_active]);
        
        $status = $flashSale->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Flash sale {$status} successfully!");
    }

    /**
     * Get available time slots for flash sale scheduling
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'duration_hours' => 'required|integer|min:1|max:168' // Max 1 week
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $durationHours = $request->duration_hours;

        // Get all existing flash sales in the date range
        $existingFlashSales = FlashSale::where('is_active', true)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_time', [$startDate, $endDate])
                      ->orWhereBetween('end_time', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_time', '<=', $startDate)
                            ->where('end_time', '>=', $endDate);
                      });
            })
            ->orderBy('start_time')
            ->get();

        $availableSlots = [];
        $currentTime = $startDate->copy();

        while ($currentTime->addHours($durationHours)->lte($endDate)) {
            $slotStart = $currentTime->copy()->subHours($durationHours);
            $slotEnd = $currentTime->copy();

            // Check if this slot overlaps with any existing flash sale
            $hasOverlap = $existingFlashSales->contains(function($flashSale) use ($slotStart, $slotEnd) {
                return ($slotStart->between($flashSale->start_time, $flashSale->end_time)) ||
                       ($slotEnd->between($flashSale->start_time, $flashSale->end_time)) ||
                       ($slotStart->lte($flashSale->start_time) && $slotEnd->gte($flashSale->end_time));
            });

            if (!$hasOverlap) {
                $availableSlots[] = [
                    'start_time' => $slotStart->format('Y-m-d H:i:s'),
                    'end_time' => $slotEnd->format('Y-m-d H:i:s'),
                    'display' => $slotStart->format('M d, Y H:i') . ' - ' . $slotEnd->format('M d, Y H:i')
                ];
            }
        }

        return response()->json([
            'available_slots' => $availableSlots,
            'existing_flash_sales' => $existingFlashSales->map(function($flashSale) {
                return [
                    'title' => $flashSale->title,
                    'start_time' => $flashSale->start_time->format('M d, Y H:i'),
                    'end_time' => $flashSale->end_time->format('M d, Y H:i'),
                    'status' => $flashSale->getStatus()
                ];
            })
        ]);
    }
}
