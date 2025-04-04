<div class="mb-2 mt-2">
    @auth()
        <form method="POST" action="{{ route('users.comments.store', ['user' => $user->id]) }}">
            @csrf
        
            <div class="form-group">
                <textarea name="body" type="text" class="form-control"></textarea>
            </div>
        
            <button type="submit" class="btn btn-primary btn-block mt-3">Add comment</button>
        </form>
        @errors @enderrors
    @else
        <a href="{{ route('login') }}">Sign-in</a> to post comments!
    @endauth
</div>
<hr>