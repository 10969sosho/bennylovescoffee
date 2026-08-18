<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerSection2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerSection2Controller extends Controller
{
    public function index()
    {
        $banners = BannerSection2::ordered()->get();
        return view('admin.banner-section2.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner-section2.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get next position
        $nextPosition = BannerSection2::max('position') + 1;

        // Handle image upload
        $imagePath = $request->file('image')->store('banners', 'public');

        BannerSection2::create([
            'title' => null,
            'description' => null,
            'image' => $imagePath,
            'position' => $nextPosition,
            'is_active' => true,
            'link_url' => null,
            'link_text' => null,
        ]);

        return redirect()->route('admin.banner-section2.index')
            ->with('success', 'Banner Section 2 uploaded successfully!');
    }

    public function show(BannerSection2 $bannerSection2)
    {
        return view('admin.banner-section2.show', compact('bannerSection2'));
    }

    public function destroy(BannerSection2 $bannerSection2)
    {
        try {
            // Delete image file
            if ($bannerSection2->image) {
                Storage::disk('public')->delete($bannerSection2->image);
            }

            $bannerSection2->delete();

            // Check if request expects JSON response
            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Banner Section 2 deleted successfully!'
                ]);
            }

            return redirect()->route('admin.banner-section2.index')
                ->with('success', 'Banner Section 2 deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting banner: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.banner-section2.index')
                ->with('error', 'Error deleting banner: ' . $e->getMessage());
        }
    }

    public function updatePosition(Request $request)
    {
        $request->validate([
            'banner_id' => 'required|exists:banner_section2s,id',
            'new_position' => 'required|integer|min:1',
        ]);

        $banner = BannerSection2::findOrFail($request->banner_id);
        $oldPosition = $banner->position;
        $newPosition = $request->new_position;

        if ($oldPosition < $newPosition) {
            // Moving down: decrease position of banners between old and new position
            BannerSection2::where('position', '>', $oldPosition)
                  ->where('position', '<=', $newPosition)
                  ->decrement('position');
        } else {
            // Moving up: increase position of banners between new and old position
            BannerSection2::where('position', '>=', $newPosition)
                  ->where('position', '<', $oldPosition)
                  ->increment('position');
        }

        $banner->update(['position' => $newPosition]);

        return response()->json(['success' => true]);
    }

    public function toggleStatus(BannerSection2 $bannerSection2)
    {
        $bannerSection2->update(['is_active' => !$bannerSection2->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $bannerSection2->is_active
        ]);
    }
}