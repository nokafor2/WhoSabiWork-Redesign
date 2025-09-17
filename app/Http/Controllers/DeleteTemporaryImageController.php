<?php

namespace App\Http\Controllers;

use App\Models\TemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteTemporaryImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Log::info('DeleteTemporaryImageController called', [
            'uniqueId' => $request->uniqueId,
            'all_input' => $request->all()
        ]);
        
        $folder = $request->uniqueId;
        
        // Handle both old format (with '<') and new format (just folder ID)
        $pos = strpos($folder, '<');
        if ($pos !== false) {
            $folder = substr($folder, 0, $pos);
        }
        
        $temporaryImage = TemporaryImage::where('folder', $folder)->first();
        
        // Check if the temp image exists
        if ($temporaryImage) {
            // Delete the temporary file from storage
            Storage::deleteDirectory('images/tmp/'.$temporaryImage->folder);
            
            // Delete the temporary image record from database
            $temporaryImage->delete();
            
            Log::info('Temporary image deleted', [
                'folder' => $folder,
                'file' => $temporaryImage->file
            ]);
        } else {
            Log::warning('Temporary image not found for deletion', ['folder' => $folder]);
        }

        return response()->json(['success' => true]);
    }
}
