<?php

namespace App\Http\Controllers;

use App\Models\Photograph;
use App\Models\TemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // $validator = Validator::make($request->all, [
        //     'title' => 'required',
        //     'description' => 'required'
        // ]);
        // dd($request->all());

        $folder = array();
        foreach ($request->images as $image) {
            $pos = strpos($image, '<');
            $folder[] = substr($image, 0, $pos);
        }
        // dd($folder);
        
        $temporaryImages = TemporaryImage::whereIn('folder', $folder)->get();
        // dd($temporaryImages->toArray());
        $result = '';
        foreach ($temporaryImages as $temporaryImage) {
            Storage::copy('images/tmp/'.$temporaryImage->folder.'/'.$temporaryImage->file, 'images/'.$temporaryImage->folder.'/'.$temporaryImage->file);
            $result = Photograph::create([
                'user_id' => Auth::user()->id,
                // 'user_id' => 1,
                'filename' => $temporaryImage->file,
                'path' => $temporaryImage->folder.'/'.$temporaryImage->file,
                'size' => $temporaryImage->size,
                'caption' => '',
                'photo_type' => '',
                'visible' => true,
            ]);
            // Delete the temporary storage file
            Storage::deleteDirectory('images/tmp'.$temporaryImage->folder);
            $temporaryImage->delete();
        }

        // return to_route('home.index');
        return redirect()->back()->with('success', $result);
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
