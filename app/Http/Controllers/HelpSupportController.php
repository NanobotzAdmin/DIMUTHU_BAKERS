<?php

namespace App\Http\Controllers;

use App\Models\HsGuideVideo;
use App\Models\PmUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HelpSupportController extends Controller
{
    /**
     * Display the guide videos management page.
     */
    public function index()
    {
        $videos = HsGuideVideo::with('userRoles')
            ->orderBy('display_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $userRoles = PmUserRole::orderBy('user_role_name')->get();

        return view('helpSupport.index', [
            'pageTitle' => 'Help & Support Videos',
            'videos' => $videos,
            'userRoles' => $userRoles,
        ]);
    }

    /**
     * Store a new guide video.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|string|url|max:500',
            'thumbnail_url' => 'nullable|string|url|max:500',
            'display_order' => 'nullable|integer|min:0',
            'user_roles' => 'required|array|min:1',
            'user_roles.*' => 'exists:pm_user_role,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $video = HsGuideVideo::create([
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => $request->video_url,
                'thumbnail_url' => $request->thumbnail_url,
                'display_order' => $request->display_order ?? 0,
                'status' => 1,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Attach user roles
            $video->userRoles()->sync($request->user_roles);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Guide video created successfully',
                'data' => $video->load('userRoles'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide video store error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating the video',
            ], 500);
        }
    }

    /**
     * Update an existing guide video.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:hs_guide_videos,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|string|url|max:500',
            'thumbnail_url' => 'nullable|string|url|max:500',
            'display_order' => 'nullable|integer|min:0',
            'user_roles' => 'required|array|min:1',
            'user_roles.*' => 'exists:pm_user_role,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $video = HsGuideVideo::findOrFail($request->id);
            $video->update([
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => $request->video_url,
                'thumbnail_url' => $request->thumbnail_url,
                'display_order' => $request->display_order ?? 0,
                'updated_by' => Auth::id(),
            ]);

            // Sync user roles
            $video->userRoles()->sync($request->user_roles);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Guide video updated successfully',
                'data' => $video->load('userRoles'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide video update error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the video',
            ], 500);
        }
    }

    /**
     * Toggle video status (active/inactive).
     */
    public function toggleStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:hs_guide_videos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
            ], 422);
        }

        try {
            $video = HsGuideVideo::findOrFail($request->id);
            $video->status = $video->status == 1 ? 0 : 1;
            $video->updated_by = Auth::id();
            $video->save();

            return response()->json([
                'status' => true,
                'message' => $video->status == 1 ? 'Video activated' : 'Video deactivated',
                'data' => $video,
            ]);

        } catch (\Exception $e) {
            Log::error('Guide video toggle error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
            ], 500);
        }
    }

    /**
     * Delete a guide video.
     */
    public function deleteVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:hs_guide_videos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
            ], 422);
        }

        try {
            $video = HsGuideVideo::findOrFail($request->id);
            $video->userRoles()->detach();
            $video->delete();

            return response()->json([
                'status' => true,
                'message' => 'Guide video deleted successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Guide video delete error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the video',
            ], 500);
        }
    }
}
