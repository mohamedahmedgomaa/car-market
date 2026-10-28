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
            @ini_set('max_execution_time', 600);
            @set_time_limit(600);
            @ini_set('memory_limit', '1024M');

            $request->validate([
                'type' => 'required|string|in:hero,sidebar,video',
                'video' => 'nullable|file|mimes:mp4,mov,ogg,webm,quicktime|max:512000', // max 500MB
                'image' => 'nullable',
                'video_url' => 'nullable|string|max:1000',
                'link' => 'nullable|string|max:1000',
            ]);

            $url = null;
            $videoFile = $request->file('video') ?? ($request->hasFile('image') ? $request->file('image') : null);

            if ($videoFile) {
                // 1. Try Cloudinary first (if configured and working)
                try {
                    if (function_exists('cloudinary') && config('cloudinary.cloud_url')) {
                        if (method_exists(cloudinary()->uploadApi(), 'uploadLarge')) {
                            $result = cloudinary()->uploadApi()->uploadLarge(
                                $videoFile->getRealPath(),
                                [
                                    'folder' => 'banners/videos',
                                    'resource_type' => 'video',
                                    'chunk_size' => 6000000,
                                ]
                            );
                        } else {
                            $result = cloudinary()->uploadApi()->upload(
                                $videoFile->getRealPath(),
                                [
                                    'folder' => 'banners/videos',
                                    'resource_type' => 'video',
                                ]
                            );
                        }
                        $url = $result['secure_url'] ?? null;
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Cloudinary video upload notice (saving locally on hosting server instead): ' . $e->getMessage());
                    $url = null;
                }

                // 2. Direct save on hosting server (Ultra-fast, zero-dependency, 100% reliable)
                if (!$url) {
                    try {
                        $destinationPath = public_path('uploads/banners/videos');
                        if (!file_exists($destinationPath)) {
                            @mkdir($destinationPath, 0755, true);
                        }
                        $extension = $videoFile->getClientOriginalExtension() ?: 'mp4';
                        $fileName = 'video_banner_' . time() . '_' . uniqid() . '.' . $extension;
                        $videoFile->move($destinationPath, $fileName);

                        $url = asset('uploads/banners/videos/' . $fileName);
                        if (request()->secure() || request()->header('x-forwarded-proto') === 'https') {
                            $url = str_replace('http://', 'https://', $url);
                        }
                    } catch (\Throwable $localErr) {
                        \Log::error('Local video storage error: ' . $localErr->getMessage());
                        return response()->json([
                            'status' => 500,
                            'message' => 'تعذر حفظ ملف الفيديو على السيرفر: ' . $localErr->getMessage(),
                        ], 500);
                    }
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
                'message' => 'تم إنشاء إعلان الفيديو بنجاح وحفظه على الخادم',
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

        if ($banner->image_path && str_contains($banner->image_path, 'uploads/banners/videos/')) {
            $parsedPath = parse_url($banner->image_path, PHP_URL_PATH);
            if ($parsedPath) {
                $localFile = public_path(ltrim($parsedPath, '/'));
                if (file_exists($localFile)) {
                    @unlink($localFile);
                }
            }
        }

        $banner->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Banner deleted successfully'
        ]);
    }
}
