<p>
    @foreach ($tags as $tag)
        <a href="{{ route('users.tags.index', ['tag' => $tag->id]) }}" 
            class="bg-success badge badge-success badge-lg">
            {{ $tag->name }}
        </a>
        {{-- <span class="bg-success badge badge-success badge-lg">{{ $tag->name }}</span> --}}
    @endforeach
</p>