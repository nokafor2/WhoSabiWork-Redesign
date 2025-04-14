<?php

namespace App\Http\Controllers;

use App\Models\TemporaryImage;
use Illuminate\Http\Request;

class UploadTemporaryImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Upload the temporary image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = $image->getClientOriginalName();
            // This is the filesize in bytes
            $size = $image->getSize();
            $folder = uniqid('image-', true);
            $image->storeAs('/images/tmp/'.$folder, $fileName);

            TemporaryImage::create([
                'file' => $fileName,
                'folder' => $folder,
                'size' => $size,
            ]);

            return $folder;
        }

        return '';
    }
}
