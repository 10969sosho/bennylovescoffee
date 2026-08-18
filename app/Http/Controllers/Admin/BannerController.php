<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where('section', 'hero')->ordered()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get next position for hero section
        $nextPosition = Banner::where('section', 'hero')->max('position') + 1;

        // Handle image upload
        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => null,
            'description' => null,
            'image' => $imagePath,
            'position' => $nextPosition,
            'section' => 'hero',
            'is_active' => true,
            'link_url' => null,
            'link_text' => null,
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Hero banner uploaded successfully!');
    }

    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    // Edit and update methods removed - only drag & drop reordering in index

    public function destroy(Banner $banner)
    {
        try {
            // Delete image file
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }

            $banner->delete();

            // Check if request expects JSON response
            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Banner deleted successfully!'
                ]);
            }

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting banner: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.banners.index')
                ->with('error', 'Error deleting banner: ' . $e->getMessage());
        }
    }

    public function updatePosition(Request $request)
    {
        $request->validate([
            'banner_id' => 'required|exists:banners,id',
            'new_position' => 'required|integer|min:1',
        ]);

        $banner = Banner::findOrFail($request->banner_id);
        $oldPosition = $banner->position;
        $newPosition = $request->new_position;

        if ($oldPosition < $newPosition) {
            // Moving down: decrease position of banners between old and new position
            Banner::where('position', '>', $oldPosition)
                  ->where('position', '<=', $newPosition)
                  ->decrement('position');
        } else {
            // Moving up: increase position of banners between new and old position
            Banner::where('position', '>=', $newPosition)
                  ->where('position', '<', $oldPosition)
                  ->increment('position');
        }

        $banner->update(['position' => $newPosition]);

        return response()->json(['success' => true]);
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $banner->is_active
        ]);
    }
}