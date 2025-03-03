<div class="card" style="width: 100%;">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <h6 class="card-subtitle text-muted">
            {{ $subtitle }}
        </h6>
    </div>
    <ul class="list-group list-group-flush">
        {{-- @foreach ($mostCommented as $user)
            <li class="list-group-item">
                <a href="{{ route('artisans.show', ['artisan' => $user->id]) }}">
                    {{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}
                </a>
            </li>
        @endforeach --}}

        {{-- Check if an array is a collection --}}
        @if (is_a($first_names, 'Illuminate\Support\Collection'))
            @foreach ($first_names as $key => $first_name)
                <li class="list-group-item">
                    <a href="{{ route('artisans.show', ['artisan' => $ids[$key]]) }}">
                        {{ $first_name }} {{ $last_names[$key] }}
                    </a>
                </li>
            @endforeach
        @else
            {{ $first_names }}
        @endif
    </ul>
</div>