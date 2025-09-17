<?php

namespace App\Http\Controllers;

use App\Models\TemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadTemporaryImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            // Validate the image upload
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240' // 10MB max
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = $image->getClientOriginalName();
                $size = $image->getSize();
                $folder = uniqid('image-', true);
                
                // Store the image in temporary location
                $image->storeAs('images/tmp/'.$folder, $fileName);

                // Create temporary image record
                $temporaryImage = TemporaryImage::create([
                    'file' => $fileName,
                    'folder' => $folder,
                    'size' => $size,
                ]);

                Log::info('Temporary image uploaded', [
                    'folder' => $folder,
                    'file' => $fileName,
                    'size' => $size
                ]);

                return response($folder, 200);
            }

            Log::error('No image file found in request');
            return response('No image file provided', 400);
            
        } catch (\Exception $e) {
            Log::error('Error uploading temporary image', [
                'error' => $e->getMessage()
            ]);
            return response('Upload failed: ' . $e->getMessage(), 500);
        }
    }
}
