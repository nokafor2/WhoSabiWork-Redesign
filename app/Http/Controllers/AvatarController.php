<?php

namespace App\Http\Controllers;

use App\Models\Photograph;
use App\Models\TemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AvatarController extends Controller
{
    /**
     * Upload avatar using temporary image workflow
     */
    public function upload(Request $request)
    {
        try {
            Log::info('AvatarController::upload called', [
                'has_image' => $request->hasFile('image'),
                'auth_user' => Auth::user() ? Auth::user()->id : 'Not authenticated'
            ]);

            // Check authentication
            if (!Auth::check()) {
                return response()->json([
                    'error' => 'You must be logged in to upload images'
                ], 401);
            }

            // Validate the cropped image blob
            $validator = Validator::make($request->all(), [
                'image' => 'required|file|mimes:jpeg,jpg,png|max:5120', // 5MB max
                'photoType' => 'sometimes|string|in:avatar'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Invalid image file',
                    'details' => $validator->errors()
                ], 422);
            }

            if (!$request->hasFile('image')) {
                return response()->json([
                    'error' => 'No image file provided'
                ], 400);
            }

            $user = Auth::user();
            $image = $request->file('image');
            
            // Generate unique filename
            $username = $user->username ?? $user->name ?? 'user';
            $timestamp = time();
            $extension = $image->getClientOriginalExtension() ?: 'jpg';
            $filename = $username . '_avatar_' . $timestamp . '.' . $extension;
            
            // Delete existing avatar first (one avatar per user)
            $this->deleteExistingAvatar($user->id);
            
            // Store the image directly in permanent location (consistent with PhotographController)
            $permanentPath = 'images/' . $filename;
            $image->storeAs('images', $filename);
            
            // Create photograph record with avatar type
            $photograph = Photograph::create([
                'user_id' => $user->id,
                'filename' => $filename,
                'path' => $permanentPath,
                'size' => $image->getSize(),
                'caption' => '', // Avatars don't need captions
                'photo_type' => 'avatar',
                'visible' => true,
            ]);
            
            Log::info('Avatar uploaded successfully', [
                'user_id' => $user->id,
                'photograph_id' => $photograph->id,
                'filename' => $filename,
                'size' => $image->getSize()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar uploaded successfully',
                'avatar' => [
                    'id' => $photograph->id,
                    'filename' => $photograph->filename,
                    'path' => $photograph->path,
                    'url' => '/storage/' . $photograph->path
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('AvatarController::upload exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get current user avatar
     */
    public function getCurrent(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'error' => 'You must be logged in'
                ], 401);
            }
            
            $user = Auth::user();
            $avatar = Photograph::where('user_id', $user->id)
                ->where('photo_type', 'avatar')
                ->where('visible', true)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($avatar) {
                return response()->json([
                    'success' => true,
                    'avatar' => [
                        'id' => $avatar->id,
                        'filename' => $avatar->filename,
                        'path' => $avatar->path,
                        'url' => '/storage/' . $avatar->path,
                        'created_at' => $avatar->created_at
                    ]
                ]);
            }
            
            return response()->json([
                'success' => true,
                'avatar' => null
            ]);
            
        } catch (\Exception $e) {
            Log::error('AvatarController::getCurrent exception', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Failed to retrieve avatar'
            ], 500);
        }
    }
    
    /**
     * Delete current user avatar
     */
    public function delete(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'error' => 'You must be logged in'
                ], 401);
            }
            
            $user = Auth::user();
            $deletedCount = $this->deleteExistingAvatar($user->id);
            
            Log::info('Avatar deleted by user request', [
                'user_id' => $user->id,
                'deleted_count' => $deletedCount
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('AvatarController::delete exception', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Failed to delete avatar'
            ], 500);
        }
    }
    
    /**
     * Delete existing avatar for user (helper method)
     */
    private function deleteExistingAvatar($userId)
    {
        try {
            $existingAvatars = Photograph::where('user_id', $userId)
                ->where('photo_type', 'avatar')
                ->get();
            
            $deletedCount = 0;
            foreach ($existingAvatars as $avatar) {
                // Delete file from storage
                if (Storage::exists($avatar->path)) {
                    Storage::delete($avatar->path);
                    Log::info('Avatar file deleted from storage', [
                        'path' => $avatar->path
                    ]);
                }
                
                // Delete database record
                $avatar->delete();
                $deletedCount++;
                
                Log::info('Avatar database record deleted', [
                    'id' => $avatar->id,
                    'user_id' => $userId
                ]);
            }
            
            return $deletedCount;
            
        } catch (\Exception $e) {
            Log::error('Error deleting existing avatar', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }
}
