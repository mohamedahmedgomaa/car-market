<?php

namespace App\Http\Modules\Banners\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Banners\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        // If it's an admin, we show all banners. If user/public, we only show active banners.
        $isAdmin = $request->route()->getPrefix() === 'api/admin' || str_contains($request->route()->getPrefix(), 'admin');
        
        $query = Banner::query()->orderBy('created_at', 'desc');
        
        if (!$isAdmin) {
            $query->where('is_active', true);
        }

        return response()->json([
            'status' => 200,
            'data' => $query->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB
        ]);

        $url = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $result = cloudinary()->uploadApi()->upload(
                $image->getRealPath(),
                [
                    'folder' => 'banners',
                    'resource_type' => 'image',
                    'transformation' => [
                        'width' => 800,
                        'height' => 500,
                        'crop' => 'fill',
                        'gravity' => 'center'
                    ]
                ]
            );
            $url = $result['secure_url'] ?? null;
        }

        if (!$url) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to upload image'
            ], 500);
        }

        $banner = Banner::create([
            'image_path' => $url,
            'is_active' => true,
        ]);

        return response()->json([
            'status' => 201,
            'data' => $banner,
            'message' => 'Banner created successfully'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $banner = Banner::findOrFail($id);
        $banner->update([
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => 200,
            'data' => $banner,
            'message' => 'Banner updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        
        // Optional: delete from Cloudinary if needed, based on public_id
        // We are just deleting the record for simplicity
        $banner->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Banner deleted successfully'
        ]);
    }
}
