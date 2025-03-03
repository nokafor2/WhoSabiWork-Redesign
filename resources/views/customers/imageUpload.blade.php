<div class="mb-2 mt-2">
    @auth()
        {{-- 'users.comments.store', ['user' => $user->id] --}}
        <form method="POST" action=" {{ route('users.photo.store', ['user' => $user->id]) }}" enctype="multipart/form-data">
            @csrf
        
            <div class="form-group">
                <label for="thumbnail">Thumbnail</label>
                <input type="file" name="thumbnail" class="form-control-file"/>
            </div>
            <div class="form-group">
                <input type="text" name="caption" value="" placeholder="Image caption"
                class="mt-3 form-control {{ $errors->has('caption') ? 'is-invalid' : '' }}">
            </div>
        
            <button type="submit" class="btn btn-primary btn-block mt-3">Add photo</button>
        </form>
        @errors @enderrors
    @else
        <a href="{{ route('login') }}">Sign-in</a> to upload a photo!
    @endauth
</div>
<hr>