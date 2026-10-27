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

        if ($request->has('type')) {
            $query->where('type', $request->query('type'));
        }

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
        $type = $request->input('type', 'hero');

        if ($type === 'video') {
            $request->validate([
                'type' => 'required|string|in:hero,sidebar,video',
                'video' => 'nullable|file|mimes:mp4,mov,ogg,webm,quicktime|max:61440',
                'image' => 'nullable',
                'video_url' => 'nullable|string|max:1000',
                'link' => 'nullable|string|max:1000',
            ]);

            $url = null;
            $videoFile = $request->file('video') ?? ($request->hasFile('image') ? $request->file('image') : null);

            if ($videoFile) {
                try {
                    $result = cloudinary()->uploadApi()->upload(
                        $videoFile->getRealPath(),
                        [
                            'folder' => 'banners/videos',
                            'resource_type' => 'video',
                        ]
                    );
                    $url = $result['secure_url'] ?? null;
                } catch (\Throwable $e) {
                    \Log::error('Cloudinary video upload error: ' . $e->getMessage());
                    return response()->json([
                        'status' => 500,
                        'message' => 'تعذر رفع ملف الفيديو إلى الخادم: ' . $e->getMessage() . '. يمكنك استخدام رابط يوتيوب بدلاً من ذلك.',
                    ], 500);
                }
            } elseif ($request->filled('video_url')) {
                $url = trim($request->input('video_url'));
            }

            if (!$url) {
                return response()->json([
                    'status' => 422,
                    'message' => 'يرجى تحميل ملف فيديو صالح أو إدخال رابط فيديو / يوتيوب',
                ], 422);
            }

            $banner = Banner::create([
                'image_path' => $url,
                'is_active' => true,
                'type' => 'video',
                'link' => $request->input('link'),
            ]);

            return response()->json([
                'status' => 201,
                'data' => $banner,
                'message' => 'تم إنشاء إعلان الفيديو بنجاح',
            ], 201);
        }

        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,webp,heic,heif|max:20480',
            'type' => 'nullable|string|in:hero,sidebar,video',
            'link' => 'nullable|string|max:1000',
        ]);

        $url = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            $transformation = [
                'crop' => 'fill',
                'gravity' => 'center'
            ];

            if ($type === 'sidebar') {
                $transformation['width'] = 640;
                $transformation['height'] = 420;
            } else {
                $transformation['width'] = 1600;
                $transformation['height'] = 800; // 2:1 Aspect Ratio
            }

            $result = cloudinary()->uploadApi()->upload(
                $image->getRealPath(),
                [
                    'folder' => 'banners',
                    'resource_type' => 'image',
                    'transformation' => $transformation
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
            'type' => $type,
            'link' => $request->input('link'),
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
            'is_active' => 'nullable|boolean',
            'link' => 'nullable|string|max:1000',
            'video_url' => 'nullable|string|max:1000',
            'image_path' => 'nullable|string|max:1000',
        ]);

        $banner = Banner::findOrFail($id);
        $data = [];
        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }
        if ($request->exists('link') || $request->has('link')) {
            $data['link'] = $request->input('link');
        }
        if ($request->filled('video_url')) {
            $data['image_path'] = $request->input('video_url');
        } elseif ($request->filled('image_path')) {
            $data['image_path'] = $request->input('image_path');
        }

        $banner->update($data);

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
