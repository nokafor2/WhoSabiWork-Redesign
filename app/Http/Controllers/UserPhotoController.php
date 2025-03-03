<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Models\Photograph;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserPhotoController extends Controller
{
    public function __construct()
    {
        // Require authentication before users can send anything for this action
        $this->middleware('auth')->only(['store']);
    }
    
    public function store(User $user, StorePhoto $request) {
        // dd($user);
        // $user->id: gives the details of the user being commented on through the late route model binding
        // The user-id passed in from the route() method will be used to get the $user details from the database
        // Hence no need to do a findOfFail($id)
        // $request->user()->id: gives the details of the currently logged in user
        // $validated = $request->validated();
        // $user->photos()->create([
        //     'user_id' => $request->user()->id,
        //     'business_id' => $user->id,
        //     'body' => $request->input('body')
        // ]);
        
        // Check if the file uploaded exists
        if ($request->hasFile('thumbnail')) {
            // $file = $request->file('thumbnail');
            // dump($file);
            // dump($file->getClientMimeType());
            // dump($file->getClientOriginalExtension());

            // // Move the file to a permanent location
            // dump($file->store('thumbnails'));
            // dump(Storage::disk('public')->put('thumbnails', $file));

            // You can specify the name to save a file
            // dump($file->storeAs('thumbnails', $user->id.'.'.$file->guessExtension()));
            // dump(Storage::putFileAs('thumbnails', $file, $user->id.'.'.$file->guessExtension()));
            // Specify the disk to save to
            // dump(Storage::disk('local')->putFileAs('thumbnails', $file, $user->id.'.'.$file->guessExtension()));

            // This get save in the public disk
            // $name1 = $file->storeAs('thumbnails', $user->id.'.'.$file->guessExtension());
            // $name2 = Storage::disk('local')->putFileAs('thumbnails', $file, $user->id.'.'.$file->guessExtension());

            // dump(Storage::url($name1));
            // dump(Storage::disk('local')->url($name2));

            // Get the path and store the file with the auto-generated name.
            $file = $request->file('thumbnail');
            $path = $file->store('thumbnails');
            // validate image upload form
            $validated = $request->validated();
            // Save using the one to many relationship
            $user->photographs()->save(
                // Use the Photograph model to save the path of the image to be saved
                Photograph::create([
                    'user_id' => $user->id,
                    'path' => $path,
                    'filename' => $file->getClientOriginalName(),
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'caption' => $validated['caption'],
                    'photo_type' => 'avatar',
                    'visible' => 1
                ])
            );
        }
        // die;

        $request->session()->flash('status', 'Profile photo was uploaded!');

        return redirect()->back();
    }

    public function update(StorePhoto $request, $id) {
        // Check if the $id exists
        $user = User::findOrFail($id);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $path = $file->store('thumbnails');
            // validate image upload form
            $validated = $request->validated();
            // Check if image exists
            if ($user->photographs()) {
                // Delete the old image
                Storage::delete($user->photographs->path);
                // Modify the image path to the new one
                $user->photographs->path = $path;
                $user->photographs->filename = $file->getClientOriginalName();
                $user->photographs->type = $file->getClientMimeType();
                $user->photographs->size = $file->getSize();
                $user->photographs->caption = $validated['caption'];
                // Save the image
                $user->photographs->save();
            } else {
                // Save using the one to many relationship
                $user->photographs()->save(
                    // Use the Photograph model to save the path of the image to be saved
                    Photograph::create([
                        'user_id' => $user->id,
                        'path' => $path,
                        'filename' => $file->getClientOriginalName(),
                        'type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                        'caption' => $validated['caption'],
                        'photo_type' => 'avatar',
                        'visible' => 1
                    ])
                );
            }            
        }
    }
}
