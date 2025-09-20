<?php

namespace App\Http\Controllers;

use App\Models\Photograph;
use App\Models\TemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotographController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->captions);
        try {
            Log::info('PhotographController::store called', [
                'all_request_data' => $request->all(),
                'temp_images' => $request->tempImages ?? [],
                'captions' => $request->captions ?? [],
                'has_temp_images' => $request->has('tempImages'),
                'has_captions' => $request->has('captions'),
                'auth_user' => Auth::user() ? Auth::user()->id : 'Not authenticated'
            ]);

            // Check authentication
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'You must be logged in to upload images');
            }

            // Get raw temporary images data and captions
            $rawTempImages = $request->input('tempImages', []);
            $rawCaptions = $request->input('captions', []);
            
            // Clean caption keys the same way we clean temp image IDs
            $captions = [];
            foreach ($rawCaptions as $rawKey => $captionText) {
                if (is_string($rawKey)) {
                    // Find position of <link> tag and extract everything before it
                    $linkPosition = strpos($rawKey, '<link');
                    if ($linkPosition !== false) {
                        $cleanKey = trim(substr($rawKey, 0, $linkPosition));
                    } else {
                        // No <link> tag found, use the whole string
                        $cleanKey = trim($rawKey);
                    }
                    
                    // Only add if it's not empty and looks like our image format
                    if (!empty($cleanKey) && strpos($cleanKey, 'image-') === 0) {
                        $captions[$cleanKey] = $captionText;
                    }
                } else {
                    // If it's not a string, use as-is
                    $captions[$rawKey] = $captionText;
                }
            }
            
            Log::info('Raw temp images and captions received', [
                'raw_temp_images' => $rawTempImages,
                'temp_images_count' => count($rawTempImages),
                'raw_captions' => $rawCaptions,
                'raw_captions_count' => count($rawCaptions),
                'cleaned_captions' => $captions,
                'cleaned_captions_count' => count($captions),
                'raw_caption_keys' => array_keys($rawCaptions),
                'cleaned_caption_keys' => array_keys($captions),
                'first_caption_example' => !empty($captions) ? array_slice($captions, 0, 1, true) : 'No captions'
            ]);
            
            // Extract clean image folder names (remove everything after <link> tag)
            $tempImageIds = [];
            foreach ($rawTempImages as $rawImageData) {
                if (is_string($rawImageData)) {
                    // Find position of <link> tag and extract everything before it
                    $linkPosition = strpos($rawImageData, '<link');
                    if ($linkPosition !== false) {
                        $cleanImageId = trim(substr($rawImageData, 0, $linkPosition));
                    } else {
                        // No <link> tag found, use the whole string
                        $cleanImageId = trim($rawImageData);
                    }
                    
                    // Only add if it's not empty and looks like our image format
                    if (!empty($cleanImageId) && strpos($cleanImageId, 'image-') === 0) {
                        $tempImageIds[] = $cleanImageId;
                    }
                } else {
                    // If it's not a string, use as-is
                    $tempImageIds[] = $rawImageData;
                }
            }
            
            Log::info('Cleaned temp image IDs', [
                'cleaned_ids' => $tempImageIds,
                'count' => count($tempImageIds)
            ]);
            
            if (empty($tempImageIds)) {
                Log::error('No temporary images provided');
                return redirect()->back()->with('error', 'No images selected for upload');
            }

            // Get temporary images from database
            $temporaryImages = TemporaryImage::whereIn('folder', $tempImageIds)->get();
            Log::info('Found temporary images in database', [
                'requested_ids' => $tempImageIds,
                'found_count' => $temporaryImages->count(),
                'found_images' => $temporaryImages->toArray()
            ]);
            
            if ($temporaryImages->isEmpty()) {
                Log::error('No temporary images found in database');
                return redirect()->back()->with('error', 'No temporary images found. Please try uploading again.');
            }

            $savedPhotographs = [];
            $username = Auth::user()->username ?? Auth::user()->name ?? 'user';
            $timestamp = time();

            foreach ($temporaryImages as $index => $temporaryImage) {
                try {
                    // Get file extension from original filename
                    $pathInfo = pathinfo($temporaryImage->file);
                    $extension = $pathInfo['extension'] ?? 'jpg';
                    
                    // Generate filename with username_timestamp format
                    $filename = $username . '_' . $timestamp . '_' . $index . '.' . $extension;
                    
                    // Copy from temp to permanent location
                    $tempPath = 'images/tmp/' . $temporaryImage->folder . '/' . $temporaryImage->file;
                    $permanentPath = 'images/' . $filename;
                    
                    Log::info('Attempting to copy file', [
                        'temp_path' => $tempPath,
                        'permanent_path' => $permanentPath,
                        'temp_exists' => Storage::exists($tempPath),
                        'temp_image' => $temporaryImage->toArray()
                    ]);
                    
                    if (Storage::exists($tempPath)) {
                        $copyResult = Storage::copy($tempPath, $permanentPath);
                        Log::info('File copy attempt', [
                            'success' => $copyResult,
                            'from' => $tempPath, 
                            'to' => $permanentPath,
                            'permanent_exists_after_copy' => Storage::exists($permanentPath)
                        ]);
                        
                        if (!$copyResult) {
                            Log::error('File copy failed');
                            continue;
                        }
                    } else {
                        Log::error('Temporary file not found', [
                            'path' => $tempPath,
                            'available_temp_files' => Storage::allFiles('images/tmp/' . $temporaryImage->folder)
                        ]);
                        continue;
                    }

                    // Get caption for this image (if provided)
                    // dd($temporaryImage->folder);
                    $imageCaption = $captions[$temporaryImage->folder] ?? '';
                    // dd($imageCaption);
                    
                    Log::info('Caption mapping for image', [
                        'image_folder' => $temporaryImage->folder,
                        'available_caption_keys' => array_keys($captions),
                        'caption_exists_for_image' => array_key_exists($temporaryImage->folder, $captions),
                        'extracted_caption' => $imageCaption,
                        'caption_length' => strlen($imageCaption),
                        'is_caption_empty' => empty($imageCaption)
                    ]);
                    
                    // Create photograph record
                    $photograph = Photograph::create([
                        'user_id' => Auth::user()->id,
                        'filename' => $filename,
                        'path' => $permanentPath,
                        'size' => $temporaryImage->size,
                        'caption' => $imageCaption,
                        'photo_type' => 'gallery',
                        'visible' => true,
                    ]);
                    
                    Log::info('Photograph created with caption', [
                        'photograph_id' => $photograph->id,
                        'image_folder' => $temporaryImage->folder,
                        'saved_caption' => $photograph->caption,
                        'caption_length' => strlen($photograph->caption ?? ''),
                        'database_record' => $photograph->toArray()
                    ]);
                    
                    $savedPhotographs[] = $photograph;
                    Log::info('Photograph saved', ['id' => $photograph->id, 'filename' => $filename]);

                    // Clean up temporary files
                    $tempDirPath = 'images/tmp/' . $temporaryImage->folder;
                    $deleteResult = Storage::deleteDirectory($tempDirPath);
                    Log::info('Temporary directory cleanup', [
                        'path' => $tempDirPath,
                        'delete_success' => $deleteResult
                    ]);
                    
                    // Delete from database
                    $temporaryImage->delete();
                    Log::info('Temporary image record deleted from database', [
                        'folder' => $temporaryImage->folder
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error('Error processing temporary image', [
                        'temp_image' => $temporaryImage->toArray(),
                        'error' => $e->getMessage()
                    ]);
                }
            }

            if (empty($savedPhotographs)) {
                return redirect()->back()->with('error', 'Failed to save any images. Please try again.');
            }

            Log::info('Upload completed', ['saved_count' => count($savedPhotographs)]);
            return redirect()->back()->with('success', 'Successfully uploaded ' . count($savedPhotographs) . ' image(s)');
            
        } catch (\Exception $e) {
            Log::error('PhotographController::store exception', [
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Clean up old temporary files (called automatically or via scheduled task)
     */
    public function cleanupOldTempFiles()
    {
        try {
            // Delete temporary files older than 24 hours
            $oldTempImages = TemporaryImage::where('created_at', '<', now()->subHours(24))->get();
            
            foreach ($oldTempImages as $tempImage) {
                // Delete the file from storage
                Storage::deleteDirectory('images/tmp/' . $tempImage->folder);
                
                // Delete the database record
                $tempImage->delete();
                
                Log::info('Cleaned up old temporary image', [
                    'folder' => $tempImage->folder,
                    'file' => $tempImage->file
                ]);
            }
            
            Log::info('Temporary file cleanup completed', [
                'cleaned_count' => $oldTempImages->count()
            ]);
            
            return response()->json([
                'success' => true,
                'cleaned_count' => $oldTempImages->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error during temporary file cleanup', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
