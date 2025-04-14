<?php

namespace App\Http\Controllers;

use App\Models\TemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteTemporaryImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // dd($request->all());
        $folder = $request->uniqueId;
        $pos = strpos($folder, '<');
        $folder = substr($folder, 0, $pos);
        $temporaryImage = TemporaryImage::where('folder', $folder)->first();
        // dd($temporaryImage);
        // check if the temp image exists
        if ($temporaryImage) {
            Storage::deleteDirectory('images/tmp/'.$temporaryImage->folder);
            $temporaryImage->delete();
        }

        return '';
    }
}
